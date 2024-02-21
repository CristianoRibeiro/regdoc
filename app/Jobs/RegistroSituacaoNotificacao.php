<?php

namespace App\Jobs;

use App\Helpers\CAProxy;
use App\Helpers\Helper;
use App\Helpers\LogDB;

use Carbon\Carbon;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Http;

use Exception;

class RegistroSituacaoNotificacao implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public $registro_fiduciario;
    public $pedido;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($registro_fiduciario)
    {
        $this->registro_fiduciario = $registro_fiduciario;
        $this->pedido = $registro_fiduciario->registro_fiduciario_pedido->pedido;

        $this->onQueue('notificacoes');
    }

    private function payload()
    {
        return [
            "tipo" => 1,
            "uuid" => $this->registro_fiduciario->uuid,
            "protocolo" => $this->pedido->protocolo_pedido,
            "situacao" => $this->pedido->situacao_pedido_grupo_produto->co_situacao_pedido_grupo_produto,
            "data" => Helper::formata_data_hora(Carbon::now())
        ];
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
            match ($this->pedido->pedido_pessoa->id_pessoa) {
                config('parceiros.BANCOS.BRADESCO_AGRO') => $response = CAProxy::request(
                    $this->pedido->url_notificacao,
                    $this->payload()
                ),
                default => $response = Http::post($this->pedido->url_notificacao, $this->payload())
            };

            if (!$response->successful()) {
                throw new Exception(
                    'Erro ao enviar a notificação do pedido '.$this->pedido->protocolo_pedido.' com o status '.$response->status(
                    ).' com o body '.$response->body().'.'
                );
            }
        } catch (Exception $e) {
            LogDB::insere(
                1,
                4,
                $e->getMessage(),
                'Notificação de mudança de situação do Registro',
                'N',
                null,
                $e->getMessage() . ' na linha ' . $e->getLine() . ' do arquivo ' . $e->getFile()
            );
            $this->fail($e->getMessage());
        }
    }
}
