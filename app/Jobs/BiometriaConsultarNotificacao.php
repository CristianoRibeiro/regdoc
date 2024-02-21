<?php

namespace App\Jobs;

use App\Domain\VScore\Models\vscore_transacao_lote;

use App\Helpers\Helper;
use App\Helpers\LogDB;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;

use Exception;

class BiometriaConsultarNotificacao implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $vscore_transacao_lote;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(vscore_transacao_lote $vscore_transacao_lote)
    {
        $this->vscore_transacao_lote = $vscore_transacao_lote;

        $this->onQueue('vscore');
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
            $response = Http::post($this->vscore_transacao_lote->url_notificacao, [
                'uuid' => $this->vscore_transacao_lote->uuid,
                'status' => 'finalizado',
                'data_finalizacao' => Helper::formata_data_hora($this->vscore_transacao_lote->dt_finalizacao)
            ]);

            if (!$response->successful())
                throw new Exception('Erro ao enviar a notificação de biometria com o status ' . $response->status() . ' com o body ' . $response->body() . '.');
                
        } catch (Exception $e) {
            LogDB::insere(
                1,
                4,
                $e->getMessage(),
                'Notificação da consulta da Biometria',
                'N',
                null,
                $e->getMessage() . ' na linha ' . $e->getLine() . ' do arquivo ' . $e->getFile()
            );
            $this->fail($e->getMessage());
        }
    }
}
