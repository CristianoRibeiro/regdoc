<?php

namespace App\Jobs;

use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

use stdClass;
use DB;
use VALIDScore;
use Exception;
use Carbon\Carbon;

use App\Domain\VScore\Contracts\VScoreTransacaoServiceInterface;
use App\Domain\VScore\Contracts\VScoreTransacaoLoteServiceInterface;
use App\Domain\VScore\Models\vscore_transacao;

class BiometriaConsultarVScore implements ShouldQueue
{
    use Batchable, Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $vscore_transacao;
    public $vscore_transacao_lote;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(vscore_transacao $vscore_transacao)
    {
        $this->vscore_transacao = $vscore_transacao;
        $this->vscore_transacao_lote = $vscore_transacao->vscore_transacao_lote;

        $this->onQueue('vscore');
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(VScoreTransacaoServiceInterface $VScoreTransacaoServiceInterface,
        VScoreTransacaoLoteServiceInterface $VScoreTransacaoLoteServiceInterface)
    {
        DB::beginTransaction();

        try {
            if (!in_array($this->vscore_transacao->id_vscore_transacao_situacao, [config('constants.VSCORE.SITUACOES.AGUARDANDO_PROCESSAMENTO'), config('constants.VSCORE.SITUACOES.ERRO')]))
                throw new Exception('A situação da transação '.$this->vscore_transacao->uuid.' não permite o processamento / reprocessamento, provavelmente ela já foi processada / reprocessada por outro meio.');

            $consulta_vscore = VALIDScore::consultar_vscore($this->vscore_transacao->nu_cpf_cnpj);

            $enviado_consulta_vscore = $consulta_vscore['enviado'];
            $response_consulta_vscore = $consulta_vscore['response'];

            sleep(config('vscore.sleep_api_time'));

            $consultar_status_vscore = $this->consultar_status_vscore($response_consulta_vscore['data']['subjectUuid']);
            $biometria = $consultar_status_vscore['status']['vscore']['biometric'] != null && $consultar_status_vscore['status']['vscore']['biometric'] > 0;

            if ($biometria===false) {
                $consulta_dvalid = VALIDScore::consultar_dvalid($this->vscore_transacao->nu_cpf_cnpj);

                $enviado_consulta_dvalid = $consulta_dvalid['enviado'];
                $response_consulta_dvalid = $consulta_dvalid['response'];
    
                sleep(config('vscore.sleep_api_time'));
                $consultar_status_dvalid = $this->consultar_status_dvalid($response_consulta_dvalid['data']['subjectUuid']);
                $biometria = $consultar_status_dvalid['status']['vscore']['biometric'] != null && $consultar_status_dvalid['status']['vscore']['biometric'] > 0;
            }            


            $args_alterar_transacao = new stdClass();
            $args_alterar_transacao->id_vscore_transacao_situacao = config('constants.VSCORE.SITUACOES.FINALIZADO');
            if(isset($consulta_vscore)) {
                $args_alterar_transacao->nu_transacao_vscore = $response_consulta_vscore['data']['subjectUuid'];
                $args_alterar_transacao->de_enviado_vscore = $enviado_consulta_vscore;
                $args_alterar_transacao->de_resultado_vscore = json_encode($consultar_status_vscore['status']['vscore']['status']);
            }
            if ($biometria===true) {
                $args_alterar_transacao->in_biometria_cpf = $biometria;
            }
            if(isset($consulta_dvalid)) {
                $args_alterar_transacao->nu_transacao_dvalid = $response_consulta_dvalid['data']['subjectUuid'];
                $args_alterar_transacao->de_enviado_dvalid = $enviado_consulta_dvalid;
                $args_alterar_transacao->de_resultado_dvalid = json_encode($consultar_status_dvalid['status']['vscore']['status']);
            }

            $VScoreTransacaoServiceInterface->alterar($this->vscore_transacao, $args_alterar_transacao);

            if ($this->vscore_transacao_lote->vscore_transacoes->count() == $this->vscore_transacao_lote->vscore_transacoes_processadas->count()) {
                $args_alterar_lote = new stdClass();
                $args_alterar_lote->in_completado = 'S';
                $args_alterar_lote->dt_finalizacao = Carbon::now();

                $VScoreTransacaoLoteServiceInterface->alterar($this->vscore_transacao_lote, $args_alterar_lote);
                    
                if($this->vscore_transacao_lote->url_notificacao) {
                    BiometriaConsultarNotificacao::dispatch($this->vscore_transacao_lote);
                }
            }

            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            
            if (in_array($this->vscore_transacao->id_vscore_transacao_situacao, [config('constants.VSCORE.SITUACOES.AGUARDANDO_PROCESSAMENTO'), config('constants.VSCORE.SITUACOES.PROCESSANDO')])) {
                $args_alterar_transacao = new stdClass();
                $args_alterar_transacao->id_vscore_transacao_situacao = config('constants.VSCORE.SITUACOES.ERRO');

                $VScoreTransacaoServiceInterface->alterar($this->vscore_transacao, $args_alterar_transacao);
            }                
            
            $this->fail($e->getMessage());
        }
    }

    private function consultar_status_vscore($nu_transacao_vscore) 
    {
        $biometria = false;

        for($i=1;$i<=3;$i++) {
            $status = VALIDScore::consultar_status($nu_transacao_vscore)['data'];
    
            if ($status['status'] != 'Em processamento') {
                if (isset($status['vscore'])) {
                    $value = $status['vscore']['biometric'];
                    if ($value != NULL && $value > 0) {
                        $biometria = true;
                    }
                }

                break;
            }

            sleep(config('vscore.sleep_api_time'));
        }

        return [
            'biometria' => $biometria,
            'status' => $status
        ];
    }
    
    private function consultar_status_dvalid($nu_transacao_dvalid) 
    {
        $biometria = false;

        for($i=1;$i<=3;$i++) {
            $status = VALIDScore::consultar_status($nu_transacao_dvalid)['data'];
            if ($status['status'] != 'Em processamento') {
                $biometria = $status['vscore']['biometric'] > 0 ?? false;
                
                break;
            }

            sleep(config('vscore.sleep_api_time'));
        }

        return [
            'biometria' => $biometria,
            'status' => $status
        ];
    }
}
