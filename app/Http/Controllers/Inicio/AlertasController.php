<?php

namespace App\Http\Controllers\Inicio;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Exception;

use App\Domain\RegistroFiduciario\Contracts\SituacaoPedidoServiceInterface;

class AlertasController extends Controller
{
    /**
     * @var SituacaoPedidoServiceInterface
     */
    protected $SituacaoPedidoServiceInterface;

    /**
     * SituacoesController constructor.
     * @param SituacaoPedidoServiceInterface $SituacaoPedidoServiceInterface
     */
    public function __construct(SituacaoPedidoServiceInterface $SituacaoPedidoServiceInterface)
    {
        $this->SituacaoPedidoServiceInterface = $SituacaoPedidoServiceInterface;
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request) {
        switch ($request->produto) {
            case 'fiduciario':
                $id_grupo_produto = config('constants.REGISTRO_FIDUCIARIO.ID_GRUPO_PRODUTO');
                $id_produto = config('constants.REGISTRO_FIDUCIARIO.ID_PRODUTO');
                break;
            case 'garantias':
                $id_grupo_produto = config('constants.REGISTRO_FIDUCIARIO.ID_GRUPO_PRODUTO');
                $id_produto = config('constants.REGISTRO_CONTRATO.ID_PRODUTO');
                break;
            default:
                throw new Exception('Produto desconhecido');
                break;
        }
        $todas_situacoes = $this->SituacaoPedidoServiceInterface->lista_situacoes_totais_produto($id_grupo_produto, $id_produto);

        // Argumentos para o retorno da view
        $compact_args = [
            'todas_situacoes' => $todas_situacoes
        ];

        return view('app.inicio.alertas.registro.geral-alerta-registro', $compact_args);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index_documentos(Request $request) {
        $id_grupo_produto = config('constants.DOCUMENTO.PRODUTO.ID_GRUPO_PRODUTO');
        $id_produto = config('constants.DOCUMENTO.PRODUTO.ID_PRODUTO');

        $todas_situacoes = $this->SituacaoPedidoServiceInterface->lista_situacoes_totais_produto($id_grupo_produto, $id_produto);

        // Argumentos para o retorno da view
        $compact_args = [
            'todas_situacoes' => $todas_situacoes
        ];

        return view('app.inicio.alertas.documentos.geral-alerta-documentos', $compact_args);
    }
}
