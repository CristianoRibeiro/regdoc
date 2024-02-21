<?php

namespace App\Http\Controllers\Registros;

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
use Gate;

use App\Http\Requests\RegistroFiduciario\Pagamentos\StoreRegistroFiduciarioPagamentoGuia;
use App\Http\Requests\RegistroFiduciario\Pagamentos\StoreRegistroFiduciarioPagamentoComprovante;
use App\Http\Requests\RegistroFiduciario\Pagamentos\StoreRegistroFiduciarioPagamentoComprovanteValidacao;

use App\Domain\Pedido\Contracts\HistoricoPedidoServiceInterface;
use App\Domain\RegistroFiduciario\Contracts\RegistroFiduciarioServiceInterface;
use App\Domain\RegistroFiduciario\Contracts\RegistroFiduciarioPagamentoServiceInterface;
use App\Domain\RegistroFiduciario\Contracts\RegistroFiduciarioPagamentoGuiaServiceInterface;

class RegistroFiduciarioPagamentoGuiaController extends Controller
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
     * RegistroFiduciarioPagamentoGuiaController constructor.
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
    public function create(Request $request)
    {
        $registro_fiduciario_pagamento = $this->RegistroFiduciarioPagamentoServiceInterface->buscar($request->pagamento);

        if($registro_fiduciario_pagamento) {
            // Argumentos para o retorno da view
            $compact_args = [
                'registro_fiduciario_pagamento' => $registro_fiduciario_pagamento,
                'registro_token' => Str::random(30),
            ];

            return view('app.produtos.registro-fiduciario.detalhes.pagamentos.geral-registro-pagamentos-guia-nova', $compact_args);
        }
    }

    /**
     * @param  StoreRegistroFiduciarioPagamentoGuia  $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory
     */
    public function store(StoreRegistroFiduciarioPagamentoGuia $request)
    {
        $registro_fiduciario_pagamento = $this->RegistroFiduciarioPagamentoServiceInterface->buscar($request->pagamento);

        if($registro_fiduciario_pagamento) {
            DB::beginTransaction();

            try {
                $registro_fiduciario = $registro_fiduciario_pagamento->registro_fiduciario;
                $pedido = $registro_fiduciario->registro_fiduciario_pedido->pedido;

                // Argumentos nova guia do pagamento
                $args_pagamento_guia = new stdClass();
                $args_pagamento_guia->id_registro_fiduciario_pagamento = $registro_fiduciario_pagamento->id_registro_fiduciario_pagamento;
                $args_pagamento_guia->nu_guia = $request->nu_guia;
                $args_pagamento_guia->nu_serie = $request->nu_serie;
                $args_pagamento_guia->va_guia = Helper::converte_float($request->va_guia);
                $args_pagamento_guia->dt_vencimento = Carbon::createFromFormat('d/m/Y', $request->dt_vencimento)->endOfDay();
                $args_pagamento_guia->no_emissor =  $request->no_emissor;

                $registro_fiduciario_pagamento_guia = $this->RegistroFiduciarioPagamentoGuiaServiceInterface->inserir($args_pagamento_guia);

                // Inserir os arquivos
                $arquivos = $request->session()->get('arquivos_' . $request->registro_token);

                $destino = '/registro-fiduciario/'.$registro_fiduciario_pagamento->registro_fiduciario->id_registro_fiduciario.'/pagamentos/'.$registro_fiduciario_pagamento->id_registro_fiduciario_pagamento;
                foreach ($arquivos as $key => $arquivo) {
                    $novo_arquivo_grupo_produto = Upload::insere_arquivo($arquivo, config('constants.REGISTRO_FIDUCIARIO.ID_GRUPO_PRODUTO'), $destino);
                    if ($novo_arquivo_grupo_produto) {
                        $args_pagamento_guia_alterar = new stdClass();
                        $args_pagamento_guia_alterar->id_arquivo_grupo_produto_guia = $novo_arquivo_grupo_produto->id_arquivo_grupo_produto;

                        $this->RegistroFiduciarioPagamentoGuiaServiceInterface->alterar($registro_fiduciario_pagamento_guia, $args_pagamento_guia_alterar);
                    }
                }

                // Atualizar a situação do pagamento
                $args_pagamento = new stdClass();
                $args_pagamento->id_registro_fiduciario_pagamento_situacao = config('constants.REGISTRO_FIDUCIARIO.PAGAMENTOS.SITUACOES.AGUARDANDO_COMPROVANTE');

                $this->RegistroFiduciarioPagamentoServiceInterface->alterar($registro_fiduciario_pagamento, $args_pagamento);

                // Insere o histórico do pedido
                $this->HistoricoPedidoServiceInterface->inserir_historico($pedido, 'A guia do pagamento '.$request->nu_guia.' foi inserida com sucesso.');

                // Atualizar data de alteração
                $args_registro_fiduciario = new stdClass();
                $args_registro_fiduciario->dt_alteracao = Carbon::now();

                $this->RegistroFiduciarioServiceInterface->alterar($registro_fiduciario, $args_registro_fiduciario);

                DB::commit();

                LogDB::insere(
                    Auth::user()->id_usuario,
                    6,
                    'Guia de pagamento foi inserida com sucesso.',
                    'Registro - Pagamentos',
                    'N',
                    request()->ip()
                );

                $response_json = [
                    'status'=> 'sucesso',
                    'recarrega' => 'true',
                    'message' => 'A guia do pagamento foi inserida com sucesso.',
                ];
                return response()->json($response_json);
            } catch(Exception $e) {
                DB::rollback();

                LogDB::insere(
                    Auth::user()->id_usuario,
                    4,
                    'Erro na inserção da guia do pagamento.',
                    'Registro - Pagamentos',
                    'N',
                    request()->ip(),
                    $e->getMessage() . ' na linha ' . $e->getLine() . ' do arquivo ' . $e->getFile()
                );

                $response_json = [
                    'status'=> 'erro',
                    'recarrega' => 'false',
                    'message' => 'Erro interno, tente novamente mais tarde. '.(config('app.env')!='production'?$e->getMessage() . ' na linha ' . $e->getLine() . ' do arquivo ' . $e->getFile():''),
                ];
                return response()->json($response_json);
            }
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory
     */
    public function enviar_comprovante(Request $request)
    {
        $registro_fiduciario_pagamento = $this->RegistroFiduciarioPagamentoServiceInterface->buscar($request->pagamento);

        if ($registro_fiduciario_pagamento) {
            $registro_fiduciario_pagamento_guia = $this->RegistroFiduciarioPagamentoGuiaServiceInterface->buscar($request->guia);

            if($registro_fiduciario_pagamento_guia) {
                // Argumentos para o retorno da view
                $compact_args = [
                    'registro_fiduciario_pagamento_guia' => $registro_fiduciario_pagamento_guia,
                    'registro_fiduciario_pagamento' => $registro_fiduciario_pagamento,
                    'registro_token' => Str::random(30),
                ];

                return view('app.produtos.registro-fiduciario.detalhes.pagamentos.geral-registro-pagamentos-guia-comprovante-novo', $compact_args);
            }
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

                $destino = '/registro-fiduciario/'.$registro_fiduciario_pagamento->registro_fiduciario->id_registro_fiduciario.'/pagamentos/'.$registro_fiduciario_pagamento->id_registro_fiduciario_pagamento;
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
                    'O comprovante do pagamento foi inserido com sucesso.',
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
                    'Erro na Inserção do comprovante do pagamento.',
                    'Registro - Pagamentos',
                    'N',
                    request()->ip(),
                    $e->getMessage() . ' na linha ' . $e->getLine() . ' do arquivo ' . $e->getFile()
                );

                $response_json = [
                    'status'=> 'erro',
                    'recarrega' => 'false',
                    'message' => 'Erro interno, tente novamente mais tarde. '.(config('app.env')!='production'?$e->getMessage() . ' na linha ' . $e->getLine() . ' do arquivo ' . $e->getFile():''),
                ];
                return response()->json($response_json);
            }
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory
     */
    public function verificar_comprovante(Request $request)
    {
        $registro_fiduciario_pagamento = $this->RegistroFiduciarioPagamentoServiceInterface->buscar($request->pagamento);
        $registro_fiduciario = $registro_fiduciario_pagamento->registro_fiduciario;

        Gate::authorize('registros-detalhes-pagamentos-validar-comprovante', $registro_fiduciario_pagamento);

        if ($registro_fiduciario_pagamento) {
            $registro_fiduciario_pagamento_guia = $this->RegistroFiduciarioPagamentoGuiaServiceInterface->buscar($request->guia);

            if($registro_fiduciario_pagamento_guia) {
                // Argumentos para o retorno da view
                $compact_args = [
                    'registro_fiduciario_pagamento_guia' => $registro_fiduciario_pagamento_guia,
                    'registro_fiduciario_pagamento' => $registro_fiduciario_pagamento,
                    'registro_token' => Str::random(30),
                ];

                return view('app.produtos.registro-fiduciario.detalhes.pagamentos.geral-registro-pagamentos-guia-comprovante-validar', $compact_args);
            }
        }
    }


     /**
     * @param  StoreRegistroFiduciarioPagamentoComprovanteValidacao  $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory
     */
    public function salvar_validacao_comprovante(StoreRegistroFiduciarioPagamentoComprovanteValidacao $request)
    {
        $registro_fiduciario_pagamento_guia = $this->RegistroFiduciarioPagamentoGuiaServiceInterface->buscar($request->guia);
        $registro_fiduciario_pagamento = $registro_fiduciario_pagamento_guia->registro_fiduciario_pagamento;
        $registro_fiduciario = $registro_fiduciario_pagamento->registro_fiduciario;

        Gate::authorize('registros-detalhes-pagamentos-validar-comprovante', $registro_fiduciario_pagamento);

        if ($registro_fiduciario_pagamento_guia) {
            DB::beginTransaction();

            try {
                $pedido = $registro_fiduciario->registro_fiduciario_pedido->pedido;

                $args_alterar_pagamento = new stdClass();
                switch ($request->tipo_situacao) {
                    case 'aceitar':
                        $args_alterar_pagamento->id_registro_fiduciario_pagamento_situacao = config('constants.REGISTRO_FIDUCIARIO.PAGAMENTOS.SITUACOES.PAGO');
                        break;
                    case 'rejeitar':
                        $args_alterar_pagamento->id_registro_fiduciario_pagamento_situacao = config('constants.REGISTRO_FIDUCIARIO.PAGAMENTOS.SITUACOES.AGUARDANDO_COMPROVANTE');

                        $this->RegistroFiduciarioPagamentoGuiaServiceInterface->remover_comprovante($registro_fiduciario_pagamento_guia);
                        break;
                }

                $this->RegistroFiduciarioPagamentoServiceInterface->alterar($registro_fiduciario_pagamento, $args_alterar_pagamento);

                // Insere o histórico do pedido
                $this->HistoricoPedidoServiceInterface->inserir_historico($pedido, 'O comprovante da guia '.$registro_fiduciario_pagamento_guia->nu_guia.' foi validado como "'.$request->tipo_situacao.'" com sucesso.');

                // Atualizar data de alteração
                $args_registro_fiduciario = new stdClass();
                $args_registro_fiduciario->dt_alteracao = Carbon::now();

                $this->RegistroFiduciarioServiceInterface->alterar($registro_fiduciario, $args_registro_fiduciario);

                DB::commit();

                LogDB::insere(
                    Auth::user()->id_usuario,
                    6,
                    'A validação de comprovante do pagamento foi salva com sucesso.',
                    'Registro - Pagamentos',
                    'N',
                    request()->ip()
                );

                $response_json = [
                    'status'=> 'sucesso',
                    'recarrega' => 'true',
                    'message' => 'A validação de comprovante do pagamento foi salva com sucesso.',
                ];
                return response()->json($response_json, 200);
            } catch(Exception $e) {
                DB::rollback();

                LogDB::insere(
                    Auth::user()->id_usuario,
                    4,
                    'Erro na validação do comprovante de pagamento.',
                    'Registro - Pagamentos',
                    'N',
                    request()->ip(),
                    $e->getMessage() . ' na linha ' . $e->getLine() . ' do arquivo ' . $e->getFile()
                );

                $response_json = [
                    'status'=> 'erro',
                    'recarrega' => 'false',
                    'message' => 'Erro interno, tente novamente mais tarde. '.(config('app.env')!='production'?$e->getMessage() . ' na linha ' . $e->getLine() . ' do arquivo ' . $e->getFile():''),
                ];
                return response()->json($response_json);
            }
        }
    }
}
