<?php

namespace App\Listeners;

use App\Domain\Pedido\Contracts\HistoricoPedidoServiceInterface;
use App\Domain\RegistroFiduciario\Contracts\RegistroFiduciarioServiceInterface;

use App\Events\ParteCertificadoEvent;

use App\Helpers\LogDB;

use Exception;

final class VerificaSeTodasAsPartesEmitiramCertificadoEAvancaProcesso
{
    private RegistroFiduciarioServiceInterface $RegistroFiduciarioService;

    private HistoricoPedidoServiceInterface $HistoricoPedidoService;

    public function __construct(RegistroFiduciarioServiceInterface $RegistroFiduciarioService,
                                HistoricoPedidoServiceInterface $HistoricoPedidoService
    )
    {
        $this->RegistroFiduciarioService = $RegistroFiduciarioService;
        $this->HistoricoPedidoService = $HistoricoPedidoService;
    }

    public function handle(ParteCertificadoEvent $event): void
    {
        $registro_fiduciario = $event->getRegistroFiduciario();
        $pedido = $registro_fiduciario->registro_fiduciario_pedido->pedido;

        if($pedido->situacao_pedido_grupo_produto->id_situacao_pedido_grupo_produto !== config('constants.SITUACAO.11.ID_CONTRATO_CADASTRADO')) return;
        
        try {
            // verifica se todas as partes emitiram o certificado ou foram canceladas por um operador.
            $verifica_todas_partes_emitiram = $this->RegistroFiduciarioService->verifica_todas_partes_emitiram($registro_fiduciario);

            if ($verifica_todas_partes_emitiram)
            {
                $this->RegistroFiduciarioService->iniciar_documentacao($registro_fiduciario);
                $this->HistoricoPedidoService->inserir_historico($pedido, 'A documentação do Registro foi iniciada automaticamente.');
            }
            // dd($pedido->situacao_pedido_grupo_produto->id_situacao_pedido_grupo_produto);
        } catch (Exception $e) {
            LogDB::insere(
                1,
                4,
                'Evento Automático Emissão Certificado',
                'Event',
                'N',
                null,
                $e->getMessage()
            );
        }
    }
}
