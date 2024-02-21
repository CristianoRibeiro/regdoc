<?php

namespace App\Http\Controllers\Protocolo;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Exception;
use Illuminate\Support\Str;
use LogDB;
use DB;
use Auth;
use stdClass;
use Upload;
use Helper;
use Carbon\Carbon;

use App\Http\Requests\RegistroFiduciario\Pagamentos\StoreRegistroFiduciarioPagamentoComprovante;

use App\Domain\Pedido\Contracts\HistoricoPedidoServiceInterface;
use App\Domain\RegistroFiduciario\Contracts\RegistroFiduciarioServiceInterface;
use App\Domain\RegistroFiduciario\Contracts\RegistroFiduciarioPagamentoServiceInterface;
use App\Domain\RegistroFiduciario\Contracts\RegistroFiduciarioPagamentoGuiaServiceInterface;

class RegistroPagamentoController extends Controller
{

    /**
     * @var HistoricoPedidoServiceInterface
     * @var RegistroFiduciarioServiceInterface
     * @var RegistroFiduciarioPagamentoServiceInterface
     * @var RegistroFiduciarioPagamentoGuiaServiceInterface
     */
    private $HistoricoPedidoServiceInterface;
    private $RegistroFiduciarioServiceInterface;
    private $RegistroFiduciarioPagamentoServiceInterface;
    private $RegistroFiduciarioPagamentoGuiaServiceInterface;

    /**
     * RegistroFiduciarioPagamentoController constructor.
     * @param HistoricoPedidoServiceInterface $HistoricoPedidoServiceInterface
     * @param RegistroFiduciarioServiceInterface $RegistroFiduciarioServiceInterface
     * @param RegistroFiduciarioPagamentoServiceInterface $RegistroFiduciarioPagamentoServiceInterface
     * @param RegistroFiduciarioPagamentoGuiaServiceInterface $RegistroFiduciarioPagamentoGuiaServiceInterface
     */
    public function __construct(HistoricoPedidoServiceInterface $HistoricoPedidoServiceInterface,
                                RegistroFiduciarioServiceInterface $RegistroFiduciarioServiceInterface,
                                RegistroFiduciarioPagamentoServiceInterface $RegistroFiduciarioPagamentoServiceInterface,
                                RegistroFiduciarioPagamentoGuiaServiceInterface $RegistroFiduciarioPagamentoGuiaServiceInterface)
    {
        $this->HistoricoPedidoServiceInterface = $HistoricoPedidoServiceInterface;
        $this->RegistroFiduciarioServiceInterface = $RegistroFiduciarioServiceInterface;
        $this->RegistroFiduciarioPagamentoServiceInterface = $RegistroFiduciarioPagamentoServiceInterface;
        $this->RegistroFiduciarioPagamentoGuiaServiceInterface = $RegistroFiduciarioPagamentoGuiaServiceInterface;
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function show(Request $request)
    {
        $registro_fiduciario_pagamento = $this->RegistroFiduciarioPagamentoServiceInterface->buscar($request->pagamento);

        if($registro_fiduciario_pagamento) {
            // Argumentos para o retorno da view

            $compact_args = [
                'registro_fiduciario_pagamento' => $registro_fiduciario_pagamento,
            ];

            return view('protocolo.produtos.registro-fiduciario.pagamentos.geral-registro-pagamentos-detalhes', $compact_args);
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory
     */
    public function enviar_comprovante(Request $request)
    {
        $registro_fiduciario_pagamento_guia = $this->RegistroFiduciarioPagamentoGuiaServiceInterface->buscar($request->guia);
        $registro_fiduciario_pagamento = $this->RegistroFiduciarioPagamentoServiceInterface->buscar($request->pagamento);
        if($registro_fiduciario_pagamento_guia) {
            // Argumentos para o retorno da view
            $compact_args = [
                'registro_fiduciario_pagamento_guia' => $registro_fiduciario_pagamento_guia,
                'registro_fiduciario_pagamento' => $registro_fiduciario_pagamento,
                'registro_token' => Str::random(30),
            ];

            return view('protocolo.produtos.registro-fiduciario.pagamentos.geral-registro-pagamentos-guia-comprovante-novo', $compact_args);
        }
    }

    /**
     * @param  StoreRegistroFiduciarioPagamentoComprovante  $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory
     */
    public function salvar_comprovante(StoreRegistroFiduciarioPagamentoComprovante $request)
    {
        $registro_fiduciario_pagamento_guia = $this->RegistroFiduciarioPagamentoGuiaServiceInterface->buscar($request->guia);

        if($registro_fiduciario_pagamento_guia) {
            DB::beginTransaction();

            try {
                $registro_fiduciario_pagamento = $registro_fiduciario_pagamento_guia->registro_fiduciario_pagamento;
                $registro_fiduciario = $registro_fiduciario_pagamento->registro_fiduciario;
                $pedido = $registro_fiduciario->registro_fiduciario_pedido->pedido;

                // Inserir os arquivos
                $arquivos = $request->session()->get('arquivos_' . $request->registro_token);

                $destino = '/registro-fiduciario/pagamentos/comprovante/' . $registro_fiduciario_pagamento_guia->id_registro_fiduciario_pagamento;
                foreach ($arquivos as $key => $arquivo) {
                    $novo_arquivo_grupo_produto = Upload::insere_arquivo($arquivo, config('constants.REGISTRO_FIDUCIARIO.ID_GRUPO_PRODUTO'), $destino);
                    if ($novo_arquivo_grupo_produto) {
                        $args_pagamento_guia_alterar = new stdClass();
                        $args_pagamento_guia_alterar->id_arquivo_grupo_produto_comprovante = $novo_arquivo_grupo_produto->id_arquivo_grupo_produto;

                        $this->RegistroFiduciarioPagamentoGuiaServiceInterface->alterar($registro_fiduciario_pagamento_guia, $args_pagamento_guia_alterar);
                    }
                }

                $args_alterar_pagamento = new stdClass();
                $args_alterar_pagamento->id_registro_fiduciario_pagamento_situacao = config('constants.REGISTRO_FIDUCIARIO.PAGAMENTOS.SITUACOES.AGUARDANDO_VALIDACAO');
                $this->RegistroFiduciarioPagamentoServiceInterface->alterar($registro_fiduciario_pagamento, $args_alterar_pagamento);

                // Insere o histórico do pedido
                $this->HistoricoPedidoServiceInterface->inserir_historico($pedido, 'O comprovante da guia de pagamento '.$registro_fiduciario_pagamento_guia->nu_guia.' foi inserida com sucesso.');

                // Atualizar data de alteração
                $args_registro_fiduciario = new stdClass();
                $args_registro_fiduciario->dt_alteracao = Carbon::now();

                $this->RegistroFiduciarioServiceInterface->alterar($registro_fiduciario, $args_registro_fiduciario);

                DB::commit();

                LogDB::insere(
                    Auth::user()->id_usuario,
                    6,
                    'Inseriu o comprovante do pagamento.',
                    'Registro - Pagamentos',
                    'N',
                    request()->ip()
                );

                $response_json = [
                    'status'=> 'sucesso',
                    'recarrega' => 'true',
                    'message' => 'O comprovante do pagamento foi inserido com sucesso.',
                ];

                return response()->json($response_json, 200);
            } catch(Exception $e) {
                DB::rollback();

                LogDB::insere(
                    Auth::user()->id_usuario,
                    4,
                    'Erro na inserção do comprovante do pagamento.',
                    'Registro - Pagamentos',
                    'N',
                    request()->ip(),
                    $e->getMessage() . ' na linha ' . $e->getLine() . ' do arquivo ' . $e->getFile()
                );

                $response_json = [
                    'status'=> 'erro',
                    'recarrega' => 'false',
                    'message' => $e->getMessage() . (config('app.env')!='production'?' Linha ' . $e->getLine() . ' do arquivo ' . $e->getFile() . '.':'')
                ];
                return response()->json($response_json);
            }
        }
    }
}
