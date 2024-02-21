<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

use DB;
use VALIDTicket;
use Exception;
use Carbon\Carbon;
use stdClass;

use App\Domain\VTicket\Contracts\VTicketSituacaoServiceInterface;
use App\Domain\Parte\Contracts\ParteEmissaoCertificadoServiceInterface;

use App\Domain\Parte\Models\parte_emissao_certificado;

class ConsultarTicket implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $parte_emissao_certificado; 

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(parte_emissao_certificado $parte_emissao_certificado)
    {
        $this->parte_emissao_certificado = $parte_emissao_certificado;

        $this->onQueue('tickets');
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(VTicketSituacaoServiceInterface $VTicketSituacaoServiceInterface, 
                           ParteEmissaoCertificadoServiceInterface $ParteEmissaoCertificadoServiceInterface)
    {
        // Verificar se emissão possui um ticket
        if (!$this->parte_emissao_certificado->nu_ticket_vidaas)
            throw new Exception('Emissão sem ticket');
        
        DB::beginTransaction();

        try {
            $retorno = VALIDTicket::status($this->parte_emissao_certificado->nu_ticket_vidaas);
            if ($retorno->dateStatus) {
                $date_status = Carbon::createFromFormat('d/m/Y H:i:s', $retorno->dateStatus);
            }

            $vticket_situacao = $VTicketSituacaoServiceInterface->buscar_situacao($retorno->status);
            
            if ($this->parte_emissao_certificado->id_parte_emissao_certificado_situacao != $vticket_situacao->id_parte_emissao_certificado_situacao ||
                $this->parte_emissao_certificado->de_situacao_ticket != $retorno->status) {
                    
                $args_alterar_emissao_certificado = new stdClass();
                $args_alterar_emissao_certificado->id_parte_emissao_certificado_situacao = $vticket_situacao->id_parte_emissao_certificado_situacao;
                $args_alterar_emissao_certificado->de_situacao_ticket = $retorno->status;
                $args_alterar_emissao_certificado->de_observacao_situacao = $vticket_situacao->de_traducao_valid_ticket_situacao;
                $args_alterar_emissao_certificado->dt_situacao = $date_status ?? NULL;

                $ParteEmissaoCertificadoServiceInterface->alterar($this->parte_emissao_certificado, $args_alterar_emissao_certificado);
            }

            DB::commit();
        } catch (Exception $e) {
            DB::rollback();

            $this->fail($e->getMessage());
        }
    }    
}
