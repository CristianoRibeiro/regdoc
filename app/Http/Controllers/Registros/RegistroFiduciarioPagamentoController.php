<?php

namespace App\Http\Controllers\Registros;

use App\Domain\RegistroFiduciario\Contracts\RegistroFiduciarioPagamentoRepositoryInterface;
use App\Domain\RegistroFiduciario\Models\registro_fiduciario_pagamento;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Exception;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;
use LogDB;
use DB;
use Auth;
use stdClass;
use Crypt;
use Helper;
use Upload;
use Carbon\Carbon;

use App\Http\Requests\RegistroFiduciario\Pagamentos\StoreRegistroFiduciarioPagamento;

use App\Domain\Pedido\Contracts\HistoricoPedidoServiceInterface;
use App\Domain\RegistroFiduciario\Contracts\RegistroFiduciarioServiceInterface;
use App\Domain\RegistroFiduciario\Contracts\RegistroFiduciarioPagamentoServiceInterface;
use App\Domain\RegistroFiduciario\Contracts\RegistroFiduciarioPagamentoTipoServiceInterface;
use App\Domain\RegistroFiduciario\Contracts\RegistroFiduciarioParteServiceInterface;
use App\Domain\Configuracao\Contracts\ConfiguracaoPessoaServiceInterface;
use App\Domain\RegistroFiduciario\Models\registro_fiduciario;
use App\Domain\RegistroFiduciario\Models\registro_fiduciario_pagamento_situacao;
use App\Traits\EmailRegistro;

class RegistroFiduciarioPagamentoController extends Controller
{
    use EmailRegistro;

    /**
     * @var HistoricoPedidoServiceInterface
     * @var RegistroFiduciarioServiceInterface
     * @var RegistroFiduciarioPagamentoServiceInterface
     * @var RegistroFiduciarioPagamentoTipoServiceInterface
     * @var RegistroFiduciarioPagamentoTipoServiceInterface
     * @var RegistroFiduciarioParteServiceInterface
     * @var ConfiguracaoPessoaServiceInterface
     */
    protected $HistoricoPedidoServiceInterface;
    protected $RegistroFiduciarioPagamentoServiceInterface;
    protected $RegistroFiduciarioPagamentoTipoServiceInterface;
    protected $RegistroFiduciarioParteServiceInterface;
    protected $RegistroFiduciarioServiceInterface;
    protected $ConfiguracaoPessoaServiceInterface;

    /**
     * RegistroFiduciarioPagamentoController constructor.
     * @param HistoricoPedidoServiceInterface $HistoricoPedidoServiceInterface
     * @param RegistroFiduciarioServiceInterface $RegistroFiduciarioServiceInterface
     * @param RegistroFiduciarioPagamentoServiceInterface $RegistroFiduciarioPagamentoServiceInterface
     * @param RegistroFiduciarioPagamentoTipoServiceInterface $RegistroFiduciarioPagamentoTipoServiceInterface
     * @param RegistroFiduciarioParteServiceInterface $RegistroFiduciarioParteServiceInterface
     * @param ConfiguracaoPessoaServiceInterface $ConfiguracaoPessoaServiceInterface
     */
    public function __construct(HistoricoPedidoServiceInterface $HistoricoPedidoServiceInterface,
                                RegistroFiduciarioServiceInterface $RegistroFiduciarioServiceInterface,
                                RegistroFiduciarioPagamentoServiceInterface $RegistroFiduciarioPagamentoServiceInterface,
                                RegistroFiduciarioPagamentoTipoServiceInterface $RegistroFiduciarioPagamentoTipoServiceInterface,
                                RegistroFiduciarioParteServiceInterface $RegistroFiduciarioParteServiceInterface,
                                ConfiguracaoPessoaServiceInterface $ConfiguracaoPessoaServiceInterface)
    {
        $this->HistoricoPedidoServiceInterface = $HistoricoPedidoServiceInterface;
        $this->RegistroFiduciarioServiceInterface = $RegistroFiduciarioServiceInterface;
        $this->RegistroFiduciarioPagamentoServiceInterface = $RegistroFiduciarioPagamentoServiceInterface;
        $this->RegistroFiduciarioPagamentoTipoServiceInterface = $RegistroFiduciarioPagamentoTipoServiceInterface;
        $this->RegistroFiduciarioParteServiceInterface = $RegistroFiduciarioParteServiceInterface;
        $this->ConfiguracaoPessoaServiceInterface = $ConfiguracaoPessoaServiceInterface;
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function create(Request $request)
    {
        $registro_fiduciario = $this->RegistroFiduciarioServiceInterface->buscar($request->registro);

        Gate::authorize('registros-detalhes-pagamentos-novo', $registro_fiduciario);

        if($registro_fiduciario) {
            $tipo_pagamentos = $this->RegistroFiduciarioPagamentoTipoServiceInterface->listar();

            // Argumentos para o retorno da view
            $compact_args = [
                'registro_fiduciario' => $registro_fiduciario,
                'tipo_pagamentos' => $tipo_pagamentos,
                'arquivo_token' => Str::random(30),
            ];

            return view('app.produtos.registro-fiduciario.detalhes.pagamentos.geral-registro-pagamentos-novo', $compact_args);
        }
    }

    /**
     * @param  StoreRegistroFiduciarioPagamento  $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory
     */
    public function store(StoreRegistroFiduciarioPagamento $request)
    {
        $registro_fiduciario = $this->RegistroFiduciarioServiceInterface->buscar($request->registro);

        Gate::authorize('registros-detalhes-pagamentos-novo', $registro_fiduciario);

        if($registro_fiduciario) {
            DB::beginTransaction();

            try {
                $pedido = $registro_fiduciario->registro_fiduciario_pedido->pedido;

                // Argumentos novo pagamento!
                $args_pagamento = new stdClass();
                $args_pagamento->id_registro_fiduciario = $registro_fiduciario->id_registro_fiduciario;
                if ($request->in_isento=='S') {
                    $args_pagamento->id_registro_fiduciario_pagamento_situacao = config('constants.REGISTRO_FIDUCIARIO.PAGAMENTOS.SITUACOES.ISENTO');
                } else {
                    $args_pagamento->id_registro_fiduciario_pagamento_situacao = config('constants.REGISTRO_FIDUCIARIO.PAGAMENTOS.SITUACOES.AGUARDANDO_GUIA');
                }
                $args_pagamento->id_registro_fiduciario_pagamento_tipo = $request->id_registro_fiduciario_pagamento_tipo;
                $args_pagamento->de_observacao =  $request->de_observacao;
                $args_pagamento->in_isento = $request->in_isento ?? 'N';

                $novo_pagamento = $this->RegistroFiduciarioPagamentoServiceInterface->inserir($args_pagamento);

                // Insere o histórico do pedido
                $this->HistoricoPedidoServiceInterface->inserir_historico($pedido, 'O pagamento de '.$novo_pagamento->registro_fiduciario_pagamento_tipo->no_registro_fiduciario_pagamento_tipo.' foi inserido com sucesso.');

                // Atualizar data de alteração
                $args_registro_fiduciario = new stdClass();
                $args_registro_fiduciario->dt_alteracao = Carbon::now();

                $this->RegistroFiduciarioServiceInterface->alterar($registro_fiduciario, $args_registro_fiduciario);

                if($novo_pagamento->in_isento == 'S') {
                    // Inserir os arquivos
                    $arquivos = $request->session()->get('arquivos_' . $request->registro_token);

                    $destino = '/registro-fiduciario/'.$registro_fiduciario->id_registro_fiduciario.'/pagamentos/'.$novo_pagamento->id_registro_fiduciario_pagamento;
                    foreach ($arquivos as $key => $arquivo) {
                        $novo_arquivo_grupo_produto = Upload::insere_arquivo($arquivo, config('constants.REGISTRO_FIDUCIARIO.ID_GRUPO_PRODUTO'), $destino);
                        if ($novo_arquivo_grupo_produto) {
                            $args_pagamento_alterar = new stdClass();
                            $args_pagamento_alterar->id_arquivo_grupo_produto_isencao = $novo_arquivo_grupo_produto->id_arquivo_grupo_produto;

                            $this->RegistroFiduciarioPagamentoServiceInterface->alterar($novo_pagamento, $args_pagamento_alterar);
                        }
                    }
                } else {
                    if ($registro_fiduciario->registro_fiduciario_parte->count()>0) {
                        $registro_fiduciario_partes = $registro_fiduciario->registro_fiduciario_parte()
                            ->whereIn('id_tipo_parte_registro_fiduciario', [
                                config('constants.REGISTRO_FIDUCIARIO.PARTES.ID_TIPO_PARTE_ADQUIRENTE'),
                                config('constants.REGISTRO_FIDUCIARIO.PARTES.ID_TIPO_PARTE_DEVEDOR'),
                                config('constants.REGISTRO_FIDUCIARIO.PARTES.ID_TIPO_PARTE_PARTE')
                            ])
                            ->get();

                        foreach ($registro_fiduciario_partes as $registro_fiduciario_parte) {
                            if (count($registro_fiduciario_parte->registro_fiduciario_procurador)>0) {
                                foreach ($registro_fiduciario_parte->registro_fiduciario_procurador as $procurador) {
                                    if ($procurador->pedido_usuario) {
                                        $args_email = [
                                            'no_email_contato' => $procurador->no_email_contato,
                                            'no_contato' => $procurador->no_parte,
                                            'senha' => Crypt::decryptString($procurador->pedido_usuario->pedido_usuario_senha->senha_crypt),
                                            'token' => $procurador->pedido_usuario->token,
                                        ];
                                        $this->enviar_email_novo_pagamento($registro_fiduciario, $novo_pagamento, $args_email);
                                    }
                                }
                            } else {
                                if ($registro_fiduciario_parte->pedido_usuario) {

                                    $args_email = [
                                        'no_email_contato' => $registro_fiduciario_parte->no_email_contato,
                                        'no_contato' => $registro_fiduciario_parte->no_parte,
                                        'senha' => Crypt::decryptString($registro_fiduciario_parte->pedido_usuario->pedido_usuario_senha->senha_crypt),
                                        'token' => $registro_fiduciario_parte->pedido_usuario->token,
                                    ];

                                    if($pedido->id_pessoa_origem == config('parceiros.BANCOS.BRADESCO_AGRO')){

                                        switch ($novo_pagamento->registro_fiduciario_pagamento_tipo->no_registro_fiduciario_pagamento_tipo) {
                                            case "ITBI":
                                                $this->enviar_email_novo_pagamento_itbi($registro_fiduciario, $args_email);
                                                break;
                                            case "Prenotação":
                                                $this->enviar_email_guia_prenotacao($registro_fiduciario, $args_email);
                                                break;
                                            case "Emolumentos":
                                                $this->enviar_email_guia_emolumentos($registro_fiduciario, $args_email);    
                                                break;
                                            default:
                                                # code...
                                                break;
                                        }

                                    }else{
             
                                        $this->enviar_email_novo_pagamento($registro_fiduciario, $novo_pagamento, $args_email);

                                    }
                                    
                                }
                            }
                        }
                    }

                    $mensagem = "O pagamento de ".$novo_pagamento->registro_fiduciario_pagamento_tipo->no_registro_fiduciario_pagamento_tipo." foi inserido na plataforma para envio dos comprovantes.";

                    $tipoPagamento = $novo_pagamento->registro_fiduciario_pagamento_tipo->no_registro_fiduciario_pagamento_tipo;

                    if($tipoPagamento == 'ITBI') {

                        $mensagemBradesco = 'A assinatura do contrato foi concluída e as informações para pagamento do ITBI estão disponíveis.<br>O <i>link</i> para visualizar, pagar e enviar o comprovante foi enviado por e-mail ao comprador.';

                    } elseif ($tipoPagamento == 'Prenotação') {

                        $mensagemBradesco = 'As informações para pagamento das custas cartorárias (prenotação) estão disponíveis.<br>O <i>link</i> para visualizar, pagar e enviar o comprovante foi enviado por e-mail ao comprador.';

                    } else {

                        $mensagemBradesco = "A guia do ".$novo_pagamento->registro_fiduciario_pagamento_tipo->no_registro_fiduciario_pagamento_tipo." está disponível para envio do(s) comprovante(s) de pagamento.";
                    }

                    $this->enviar_email_observador_registro($registro_fiduciario, $mensagem, $mensagemBradesco);
                    $this->enviar_email_operadores_registro($registro_fiduciario, $mensagem, $mensagemBradesco);
                }

                LogDB::insere(
                    Auth::user()->id_usuario,
                    6,
                    'O pagamento foi inserido com sucesso.',
                    'Registro - Pagamentos',
                    'N',
                    request()->ip()
                );

                DB::commit();

                $response_json = [
                    'status'=> 'sucesso',
                    'recarrega' => 'true',
                    'message' => 'O pagamento foi inserido com sucesso.',
                ];
                return response()->json($response_json, 200);

            } catch(Exception $e) {
                DB::rollback();

                LogDB::insere(
                    Auth::user()->id_usuario,
                    4,
                    'Erro na inserção de um novo pagamento.',
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

            return view('app.produtos.registro-fiduciario.detalhes.pagamentos.geral-registro-pagamentos-detalhes', $compact_args);
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function show_tab_pagamentos(registro_fiduciario $registro)
    {
        $situacoes = registro_fiduciario_pagamento_situacao::get(['id_registro_fiduciario_pagamento_situacao', 'no_registro_fiduciario_pagamento_situacao', 'in_registro_ativo']);
        
        $compact_args = [
            'registro_fiduciario' => $registro,
            'situacoes' => $situacoes
        ];
        
        return view('app.produtos.registro-fiduciario.detalhes.geral-registro-detalhes-pagamentos', $compact_args);
    }

    public function destroy(Request $request)
    {
        $registro_fiduciario = $this->RegistroFiduciarioServiceInterface->buscar($request->registro);
        $registro_fiduciario_pagamento = $this->RegistroFiduciarioPagamentoServiceInterface->buscar($request->pagamento);

        Gate::authorize('registros-pagamentos-cancelar', $registro_fiduciario_pagamento);
   
        if($registro_fiduciario_pagamento){
            DB::beginTransaction();

            try {
                $pedido = $registro_fiduciario->registro_fiduciario_pedido->pedido;

                // Argumentos novo cancelamento!
                $args_pagamento = new stdClass();
                $args_pagamento->id_registro_fiduciario_pagamento_situacao = config('constants.REGISTRO_FIDUCIARIO.PAGAMENTOS.SITUACOES.CANCELADO');

                $this->RegistroFiduciarioPagamentoServiceInterface->alterar($registro_fiduciario_pagamento, $args_pagamento);

                // Insere o histórico do pedido
                $this->HistoricoPedidoServiceInterface->inserir_historico($pedido, 'O pagamento de ' . $registro_fiduciario_pagamento->registro_fiduciario_pagamento_tipo->no_registro_fiduciario_pagamento_tipo . ' foi cancelado com sucesso');

                LogDB::insere(
                    Auth::user()->id_usuario,
                    6,
                    'O pagamento foi cancelado com sucesso.',
                    'Registro - Pagamentos',
                    'N',
                    request()->ip()
                );

                DB::commit();

                $response_json = [
                    'status'=> 'sucesso',
                    'recarrega' => 'true',
                    'message' => 'O pagamento foi cancelado com sucesso.',
                ];
                return response()->json($response_json, 200);
            } catch(Exception $e) {
                DB::rollback();

                LogDB::insere(
                    Auth::user()->id_usuario,
                    4,
                    'Erro na cancelamento do pagamento.',
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

    public function update($registro, registro_fiduciario_pagamento $pagamento, Request $request, RegistroFiduciarioPagamentoRepositoryInterface $pagamentoRepository)
    {
        Gate::authorize('atualizar-registro-itbi');

        $args = new stdClass();
        $args->id_registro_fiduciario_pagamento_situacao = $request->situacao;

        if ($pagamentoRepository->alterar($pagamento, $args)) {
            $response_json = [
                'status' => 'sucesso',
                'message' => 'Situação do registro efetuado com sucesso.',
                'recarrega' => 'true'
            ];
            return response()->json($response_json, 204);
        }

        $response_json = [
            'status' => 'erro',
            'message' => 'Erro interno, tente novamente mais tarde.',
            'recarrega' => 'true'
        ];
        return response()->json($response_json, 500);
    }
}
