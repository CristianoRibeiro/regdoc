<?php

namespace App\Jobs;

use App\Helpers\CAProxy;
use App\Helpers\Helper;
use App\Helpers\LogDB;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Http;

use Exception;

class RegistroComentarioNotificacao implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public $registro_fiduciario_comentario;
    public $registro_fiduciario;
    public $pedido;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($registro_fiduciario_comentario)
    {
        $this->registro_fiduciario_comentario = $registro_fiduciario_comentario;
        $this->registro_fiduciario = $registro_fiduciario_comentario->registro_fiduciario;
        $this->pedido = $this->registro_fiduciario->registro_fiduciario_pedido->pedido;

        $this->onQueue('notificacoes');
    }

    private function payload()
    {
        return [
            "tipo" => 2,
            "registro" => [
                "uuid" => $this->registro_fiduciario->uuid,
                "protocolo" => $this->pedido->protocolo_pedido,
                "situacao" => $this->pedido->situacao_pedido_grupo_produto->co_situacao_pedido_grupo_produto
            ],
            "comentario" => [
                "uuid" => $this->registro_fiduciario_comentario->uuid,
                "usuario" => $this->registro_fiduciario_comentario->usuario->no_usuario,
                "data" => Helper::formata_data_hora($this->registro_fiduciario_comentario->dt_cadastro, 'Y-m-d H:i:s')
            ]
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
                    'Erro ao enviar a notificação de comentário do pedido '.$this->pedido->protocolo_pedido.' com o status '.$response->status(
                    ).' com o body '.$response->body().'.'
                );
            }
        } catch (Exception $e) {
            LogDB::insere(
                1,
                4,
                $e->getMessage(),
                'Notificação de comentário do Registro',
                'N',
                null,
                $e->getMessage() . ' na linha ' . $e->getLine() . ' do arquivo ' . $e->getFile()
            );

            $this->fail($e->getMessage());
        }
    }
}
