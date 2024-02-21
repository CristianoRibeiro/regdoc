<?php

namespace App\Listeners;

use App\Events\SkipStatus;
use App\Helpers\LogDB;
use Exception;

class ChangeStatus
{
    private function checkRules($registro_fiduciario): bool
    {
        $constType = config('constants.REGISTRO_FIDUCIARIO.TIPOS.CEDULA_CREDITO_ASSINATURA');
        $constSituation = config('constants.SITUACAO.11.ID_DOCUMENTACAO');
        $constUser = config('parceiros.BANCOS.BRADESCO_AGRO');

        if ($registro_fiduciario->id_registro_fiduciario_tipo != $constType) {
            return false;
        }

        if ($registro_fiduciario->registro_fiduciario_pedido->pedido->id_pessoa_origem != $constUser) {
            return false;
        }

        if ($registro_fiduciario->registro_fiduciario_pedido->pedido->id_situacao_pedido_grupo_produto != $constSituation) {
            return false;
        }

        if (!$registro_fiduciario->partes_arquivos_nao_assinados->isEmpty()) {
            return false;
        }
        
        return true;
    }

    /**
     * Handle the event.
     *
     * @param SkipStatus $event
     * @return void
     */
    public function handle(SkipStatus $event)
    {
        try {
            if ($this->checkRules($event->registro_fiduciario)) {
                $event->registro_fiduciario->registro_fiduciario_pedido
                    ->pedido()
                    ->update(
                        [
                            'id_situacao_pedido_grupo_produto' => config('constants.SITUACAO.11.ID_FINALIZADO')
                        ]
                    );
            }
        } catch (Exception $exception) {
            LogDB::insere(
                1,
                4,
                $exception->getMessage(),
                'EVENTO DE TROCA DE STATUS AUTOMATICO',
                'N',
                null
            );
        }
    }
}
