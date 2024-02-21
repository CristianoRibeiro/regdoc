<?php

namespace App\Http\Controllers\Registros;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Illuminate\Support\Str;
use LogDB;
use DB;
use Auth;
use stdClass;
use Upload;
use Exception;
use Helper;
use Carbon\Carbon;
use Gate;
use Crypt;

use App\Http\Requests\RegistroFiduciario\NotasDevolutivas\StoreRegistroFiduciarioNotaDevolutiva;
use App\Http\Requests\RegistroFiduciario\NotasDevolutivas\UpdateRegistroFiduciarioNotaDevolutiva;
use App\Http\Requests\RegistroFiduciario\NotasDevolutivas\SalvarRegistroFiduciarioNotaDevolutivaCategorizar;

use App\Domain\Pedido\Contracts\PedidoServiceInterface;
use App\Domain\Pedido\Contracts\HistoricoPedidoServiceInterface;
use App\Domain\RegistroFiduciario\Contracts\RegistroFiduciarioServiceInterface;
use App\Domain\RegistroFiduciario\Contracts\RegistroFiduciarioNotaDevolutivaServiceInterface;
use App\Domain\RegistroFiduciario\Contracts\RegistroFiduciarioNotaDevolutivaArquivoGrupoServiceInterface;
use App\Domain\Configuracao\Contracts\ConfiguracaoPessoaServiceInterface;
use App\Domain\NotaDevolutiva\Contracts\NotaDevolutivaCausaClassificacaoServiceInterface;
use App\Domain\NotaDevolutiva\Contracts\NotaDevolutivaCumprimentoServiceInterface;
use App\Domain\NotaDevolutiva\Contracts\NotaDevolutivaCausaRaizServiceInterface;
use App\Domain\RegistroFiduciario\Models\registro_fiduciario;
use App\Domain\RegistroFiduciario\Models\registro_fiduciario_nota_devolutiva_situacao;

use App\Jobs\RegistroSituacaoNotificacao;

use App\Traits\EmailRegistro;

class RegistroFiduciarioNotaDevolutivaController extends Controller
{
    use EmailRegistro;

    /**
     * @var PedidoServiceInterface
     * @var HistoricoPedidoServiceInterface
     * @var RegistroFiduciarioServiceInterface
     * @var RegistroFiduciarioNotaDevolutivaServiceInterface
     * @var RegistroFiduciarioNotaDevolutivaArquivoGrupoServiceInterface
     * @var ConfiguracaoPessoaServiceInterface
     * @var NotaDevolutivaCausaClassificacaoServiceInterface
     * @var NotaDevolutivaCumprimentoServiceInterface
     * @var NotaDevolutivaCausaRaizServiceInterface
     */
    protected $PedidoServiceInterface;
    protected $HistoricoPedidoServiceInterface;
    protected $RegistroFiduciarioServiceInterface;
    protected $RegistroFiduciarioNotaDevolutivaServiceInterface;
    protected $RegistroFiduciarioNotaDevolutivaArquivoGrupoServiceInterface;
    protected $ConfiguracaoPessoaServiceInterface;
    protected $NotaDevolutivaCausaClassificacaoServiceInterface;
    protected $NotaDevolutivaCumprimentoServiceInterface;
    protected $NotaDevolutivaCausaRaizServiceInterface;

    /**
     * RegistroFiduciarioNotaDevolutivaController constructor.
     * @param PedidoServiceInterface $PedidoServiceInterface
     * @param HistoricoPedidoServiceInterface $HistoricoPedidoServiceInterface
     * @param RegistroFiduciarioServiceInterface $RegistroFiduciarioServiceInterface
     * @param RegistroFiduciarioNotaDevolutivaServiceInterface $RegistroFiduciarioNotaDevolutivaServiceInterface
     * @param RegistroFiduciarioNotaDevolutivaArquivoGrupoServiceInterface $RegistroFiduciarioNotaDevolutivaArquivoGrupoServiceInterface
     * @param ConfiguracaoPessoaServiceInterface $ConfiguracaoPessoaServiceInterface
     * @param NotaDevolutivaCausaClassificacaoServiceInterface $NotaDevolutivaCausaClassificacaoServiceInterface
     * @param NotaDevolutivaCumprimentoServiceInterface $NotaDevolutivaCumprimentoServiceInterface
     * @param NotaDevolutivaCausaRaizServiceInterface $NotaDevolutivaCausaRaizServiceInterface
     */
    public function __construct(PedidoServiceInterface $PedidoServiceInterface,
                                HistoricoPedidoServiceInterface $HistoricoPedidoServiceInterface,
                                RegistroFiduciarioServiceInterface $RegistroFiduciarioServiceInterface,
                                RegistroFiduciarioNotaDevolutivaServiceInterface $RegistroFiduciarioNotaDevolutivaServiceInterface,
                                RegistroFiduciarioNotaDevolutivaArquivoGrupoServiceInterface $RegistroFiduciarioNotaDevolutivaArquivoGrupoServiceInterface,
                                ConfiguracaoPessoaServiceInterface $ConfiguracaoPessoaServiceInterface,
                                NotaDevolutivaCausaClassificacaoServiceInterface $NotaDevolutivaCausaClassificacaoServiceInterface,
                                NotaDevolutivaCumprimentoServiceInterface $NotaDevolutivaCumprimentoServiceInterface,
                                NotaDevolutivaCausaRaizServiceInterface $NotaDevolutivaCausaRaizServiceInterface)
    {
        $this->PedidoServiceInterface = $PedidoServiceInterface;
        $this->HistoricoPedidoServiceInterface = $HistoricoPedidoServiceInterface;
        $this->RegistroFiduciarioServiceInterface = $RegistroFiduciarioServiceInterface;
        $this->RegistroFiduciarioNotaDevolutivaServiceInterface = $RegistroFiduciarioNotaDevolutivaServiceInterface;
        $this->RegistroFiduciarioNotaDevolutivaArquivoGrupoServiceInterface = $RegistroFiduciarioNotaDevolutivaArquivoGrupoServiceInterface;
        $this->ConfiguracaoPessoaServiceInterface = $ConfiguracaoPessoaServiceInterface;
        $this->NotaDevolutivaCausaClassificacaoServiceInterface = $NotaDevolutivaCausaClassificacaoServiceInterface;
        $this->NotaDevolutivaCumprimentoServiceInterface = $NotaDevolutivaCumprimentoServiceInterface;
        $this->NotaDevolutivaCausaRaizServiceInterface = $NotaDevolutivaCausaRaizServiceInterface;
    }

    /**
     * Exibe o formulário de arquivos do registro
     * @param Request $request
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function show(Request $request)
    {
        $registro_fiduciario_nota_devolutiva = $this->RegistroFiduciarioNotaDevolutivaServiceInterface->buscar($request->devolutiva);

        if ($registro_fiduciario_nota_devolutiva) {
            $permite_visualizar_causas_raizes = false;
            switch (Auth::User()->pessoa_ativa->id_tipo_pessoa) {
                case 1:
                case 13:
                    $permite_visualizar_causas_raizes = true;
                    break;
                case 8:
                    if((config('global.permitir-visualizacao-causas-nota-devolutiva') ?? 'N') == 'S') {
                        $permite_visualizar_causas_raizes = true;
                    }
                    break;
            }

            // Argumentos para o retorno da view
            $compact_args = [
                'registro_fiduciario_nota_devolutiva' => $registro_fiduciario_nota_devolutiva,
                'permite_visualizar_causas_raizes' => $permite_visualizar_causas_raizes
            ];

            return view('app.produtos.registro-fiduciario.detalhes.devolutivas.geral-registro-devolutivas-detalhes', $compact_args);
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function create(Request $request)
    {
        $registro_fiduciario = $this->RegistroFiduciarioServiceInterface->buscar($request->registro);

        Gate::authorize('registros-detalhes-devolutivas-nova', $registro_fiduciario);

        if($registro_fiduciario) {
            $nota_devolutiva_cumprimento = $this->NotaDevolutivaCumprimentoServiceInterface->listar();
            $nota_devolutiva_causa_classificacao = $this->NotaDevolutivaCausaClassificacaoServiceInterface->listar();

            // Argumentos para o retorno da view
            $compact_args = [
                'registro_fiduciario' => $registro_fiduciario,
                'registro_token' => Str::random(30),
                'nota_devolutiva_cumprimentos' => $nota_devolutiva_cumprimento,
                'nota_devolutiva_causa_classificacoes' => $nota_devolutiva_causa_classificacao
            ];

            return view('app.produtos.registro-fiduciario.detalhes.devolutivas.geral-registro-devolutivas-novo', $compact_args);
        }
    }

    /**
     * @param  StoreRegistroFiduciarioNotaDevolutiva  $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory
     */
    public function store(StoreRegistroFiduciarioNotaDevolutiva $request)
    {
        $registro_fiduciario = $this->RegistroFiduciarioServiceInterface->buscar($request->registro);

        Gate::authorize('registros-detalhes-devolutivas-nova', $registro_fiduciario);

        if($registro_fiduciario) {
            DB::beginTransaction();

            try {
                $pedido = $registro_fiduciario->registro_fiduciario_pedido->pedido;

                // Alterar o pedido
                $args_pedido = new stdClass();
                $args_pedido->id_situacao_pedido_grupo_produto = config('constants.SITUACAO.11.ID_NOTA_DEVOLUTIVA');

                $pedido = $this->PedidoServiceInterface->alterar($pedido, $args_pedido);

                // Argumentos nova nota devolutiva
                $args_nota_devolutiva = new stdClass();
                $args_nota_devolutiva->id_registro_fiduciario_nota_devolutiva_situacao = config('constants.REGISTRO_FIDUCIARIO.NOTA_DEVOLUTIVA.SITUACOES.AGUARDANDO_RESPOSTA');
                $args_nota_devolutiva->id_registro_fiduciario = $registro_fiduciario->id_registro_fiduciario;
                $args_nota_devolutiva->de_nota_devolutiva = $request->de_nota_devolutiva;
                $args_nota_devolutiva->id_nota_devolutiva_cumprimento = $request->id_nota_devolutiva_cumprimento;

                $registro_fiduciario_nota_devolutiva = $this->RegistroFiduciarioNotaDevolutivaServiceInterface->inserir($args_nota_devolutiva);

                // Inserir causas raizes na nota devolutiva
                if (count($request->id_nota_devolutiva_causa_raizes)>0) {
                    foreach ($request->id_nota_devolutiva_causa_raizes as $id_nota_devolutiva_causa_raiz) {
                        $nota_devolutiva_causa_raiz = $this->NotaDevolutivaCausaRaizServiceInterface->buscar($id_nota_devolutiva_causa_raiz);
    
                        $registro_fiduciario_nota_devolutiva->causas_raiz()->attach($nota_devolutiva_causa_raiz, [
                            'id_nota_devolutiva_causa_grupo' => $nota_devolutiva_causa_raiz->id_nota_devolutiva_causa_grupo,
                            'id_usuario_cad' => Auth::id()
                        ]);
                    }
                }
                                
                // Inserir os arquivos
                $arquivos = $request->session()->get('arquivos_' . $request->registro_token);

                $destino = '/registro-fiduciario/'.$registro_fiduciario->id_registro_fiduciario.'/devolutivas/'.$registro_fiduciario_nota_devolutiva->id_registro_fiduciario_nota_devolutiva;
                foreach ($arquivos as $arquivo) {
                    $novo_arquivo_grupo_produto = Upload::insere_arquivo($arquivo, config('constants.REGISTRO_FIDUCIARIO.ID_GRUPO_PRODUTO'), $destino);
                    if ($novo_arquivo_grupo_produto) {
                        $registro_fiduciario_nota_devolutiva->arquivos_grupo()->attach($novo_arquivo_grupo_produto, ['id_usuario_cad' => Auth::id()]);
                    }
                }

                //Se o pedido pertencer ao bradesco agro   
                if($pedido->id_pessoa_origem == config('parceiros.BANCOS.BRADESCO_AGRO')){

                    $documentos = [];
                    foreach ($arquivos as $key => $arquivo) {
                        $documentos[$key] = $arquivo['no_descricao_arquivo'];
                    }


                    if ($registro_fiduciario->registro_fiduciario_parte->count()>0) {

                        $registro_fiduciario_partes = $registro_fiduciario->registro_fiduciario_parte()->get();

                        foreach ($registro_fiduciario_partes as $registro_fiduciario_parte) {

                            $args_email = [
                                'no_email_contato' => $registro_fiduciario_parte->no_email_contato,
                                'no_contato' => $registro_fiduciario_parte->no_parte,
                                'senha' => Crypt::decryptString($registro_fiduciario_parte->pedido_usuario->pedido_usuario_senha->senha_crypt),
                                'token' => $registro_fiduciario_parte->pedido_usuario->token,
                                'documentos' => $documentos ?? NULL
                            ];
    
                            $this->enviar_email_nota_devolutiva($registro_fiduciario, $args_email);
            
                        }    

                    }    

                }

                // Insere o histórico do pedido
                $this->HistoricoPedidoServiceInterface->inserir_historico($pedido, 'A nota devolutiva foi inserida com sucesso.');

                // Atualizar data de alteração
                $args_registro_fiduciario = new stdClass();
                $args_registro_fiduciario->dt_alteracao = Carbon::now();

                $this->RegistroFiduciarioServiceInterface->alterar($registro_fiduciario, $args_registro_fiduciario);

                LogDB::insere(
                    Auth::user()->id_usuario,
                    6,
                    'A nota devolutiva foi inserida com sucesso.',
                    'Registro - Notas devolutivas',
                    'N',
                    request()->ip()
                );

                $mensagem = "Uma nota devolutiva foi inserida no sistema, para visualizar, acesse a aba “Notas devolutivas” nos detalhes do Registro.";
                $mensagemBradesco = "Após avaliação do contrato pelo Cartório de Registro de Imóveis foi identificado a necessidade de apresentar documentação adicional e/ou ressalvar o contrato.<br>Se necessário, acionaremos as partes.";
                $this->enviar_email_observador_registro($registro_fiduciario, $mensagem, $mensagemBradesco);
                $this->enviar_email_operadores_registro($registro_fiduciario, $mensagem, $mensagemBradesco);

                // Enviar Notificação
                if(!empty($pedido->url_notificacao)) {
                    RegistroSituacaoNotificacao::dispatch($registro_fiduciario);
                }

                DB::commit();

                $response_json = [
                    'status'=> 'sucesso',
                    'recarrega' => 'true',
                    'message' => 'Nota devolutiva inserida com sucesso.',
                ];
                return response()->json($response_json, 200);

            } catch (Exception $e) {
                DB::rollback();

                LogDB::insere(
                    Auth::user()->id_usuario,
                    4,
                    'Erro na inserção de nota devolutiva.',
                    'Registro - Notas devolutivas',
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
    public function edit(Request $request)
    {
        $registro_fiduciario_nota_devolutiva = $this->RegistroFiduciarioNotaDevolutivaServiceInterface->buscar($request->devolutiva);

        if ($registro_fiduciario_nota_devolutiva) {
            // Argumentos para o retorno da view
            $compact_args = [
                'registro_fiduciario_nota_devolutiva' => $registro_fiduciario_nota_devolutiva,
                'registro_token' => Str::random(30),
            ];

            return view('app.produtos.registro-fiduciario.detalhes.devolutivas.geral-registro-devolutivas-responder', $compact_args);
        }
    }

    /**
     * @param  UpdateRegistroFiduciarioNotaDevolutiva  $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory
     */
    public function update(UpdateRegistroFiduciarioNotaDevolutiva $request)
    {
        $registro_fiduciario_nota_devolutiva = $this->RegistroFiduciarioNotaDevolutivaServiceInterface->buscar($request->devolutiva);

        if ($registro_fiduciario_nota_devolutiva) {
            DB::beginTransaction();

            try {
                $registro_fiduciario = $registro_fiduciario_nota_devolutiva->registro_fiduciario;
                $pedido = $registro_fiduciario->registro_fiduciario_pedido->pedido;

                // Argumentos atualizar nota devolutiva
                $args_atualizar_nota_devolutiva = new stdClass();
                $args_atualizar_nota_devolutiva->id_registro_fiduciario_nota_devolutiva_situacao = config('constants.REGISTRO_FIDUCIARIO.NOTA_DEVOLUTIVA.SITUACOES.FINALIZADA');

                $this->RegistroFiduciarioNotaDevolutivaServiceInterface->alterar($registro_fiduciario_nota_devolutiva, $args_atualizar_nota_devolutiva);

                $registro_fiduciario_nota_devolutivas = $registro_fiduciario->registro_fiduciario_nota_devolutivas()
                    ->whereIn("id_registro_fiduciario_nota_devolutiva_situacao" , [
                        config('constants.REGISTRO_FIDUCIARIO.NOTA_DEVOLUTIVA.SITUACOES.AGUARDANDO_RESPOSTA'),
                        config('constants.REGISTRO_FIDUCIARIO.NOTA_DEVOLUTIVA.SITUACOES.AGUARDANDO_ENVIO_RESPOSTA')
                    ])
                    ->count();

                // Alterar o pedido
                if($registro_fiduciario_nota_devolutivas == 0) {
                    $args_pedido = new stdClass();
                    $args_pedido->id_situacao_pedido_grupo_produto = config('constants.SITUACAO.11.ID_EM_PROCESSAMENTO');

                    $pedido = $this->PedidoServiceInterface->alterar($pedido, $args_pedido);
                }

                // Inserir os arquivos
                $arquivos = $request->session()->get('arquivos_' . $request->registro_token);

                $destino = '/registro-fiduciario/'.$registro_fiduciario_nota_devolutiva->registro_fiduciario->id_registro_fiduciario.'/devolutivas/'.$registro_fiduciario_nota_devolutiva->id_registro_fiduciario_nota_devolutiva;
                foreach ($arquivos as $arquivo) {
                    $novo_arquivo_grupo_produto = Upload::insere_arquivo($arquivo, config('constants.REGISTRO_FIDUCIARIO.ID_GRUPO_PRODUTO'), $destino);
                    if ($novo_arquivo_grupo_produto) {
                        $registro_fiduciario_nota_devolutiva->arquivos_grupo()->attach($novo_arquivo_grupo_produto, ['id_usuario_cad' => Auth::id()]);
                    }
                }

                // Insere o histórico do pedido
                $this->HistoricoPedidoServiceInterface->inserir_historico($pedido, "A resposta da nota devolutiva nº {$registro_fiduciario_nota_devolutiva->id_registro_fiduciario_nota_devolutiva} foi inserida com sucesso.");

                // Atualizar data de alteração
                $args_registro_fiduciario = new stdClass();
                $args_registro_fiduciario->dt_alteracao = Carbon::now();

                $this->RegistroFiduciarioServiceInterface->alterar($registro_fiduciario, $args_registro_fiduciario);

                LogDB::insere(
                    Auth::user()->id_usuario,
                    6,
                    'A resposta da nota devolutiva foi inserida com sucesso.',
                    'Registro - Notas devolutivas',
                    'N',
                    request()->ip()
                );

                DB::commit();

                $response_json = [
                    'status'=> 'sucesso',
                    'recarrega' => 'true',
                    'message' => 'Resposta da nota devolutiva inserida com sucesso.',
                ];
                return response()->json($response_json, 200);

            } catch (Exception $e) {
                DB::rollback();

                LogDB::insere(
                    Auth::user()->id_usuario,
                    4,
                    'Erro na inserção da resposta da nota devolutiva.',
                    'Registro - Notas devolutivas',
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
    
    public function categorizar(Request $request)
    {
        $registro_fiduciario = $this->RegistroFiduciarioServiceInterface->buscar($request->registro);
        $registro_fiduciario_nota_devolutiva = $this->RegistroFiduciarioNotaDevolutivaServiceInterface->buscar($request->devolutiva);

        $nota_devolutiva_cumprimento = $this->NotaDevolutivaCumprimentoServiceInterface->listar();
        $nota_devolutiva_causa_classificacao = $this->NotaDevolutivaCausaClassificacaoServiceInterface->listar();

        // Argumentos para o retorno da view
        $compact_args = [
            'registro_fiduciario' => $registro_fiduciario,
            'nota_devolutiva_cumprimentos' => $nota_devolutiva_cumprimento,
            'nota_devolutiva_causa_classificacoes' => $nota_devolutiva_causa_classificacao,
            'registro_fiduciario_nota_devolutiva' => $registro_fiduciario_nota_devolutiva
        ];

        return view('app.produtos.registro-fiduciario.detalhes.devolutivas.geral-registro-devolutivas-categorizar', $compact_args);
    }

    /**
     * @param  SalvarRegistroFiduciarioNotaDevolutivaCategorizar $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory
     */
    public function salvar_categorizar(SalvarRegistroFiduciarioNotaDevolutivaCategorizar $request)
    {
        $registro_fiduciario = $this->RegistroFiduciarioServiceInterface->buscar($request->id_registro_fiduciario);
        $registro_fiduciario_nota_devolutiva = $this->RegistroFiduciarioNotaDevolutivaServiceInterface->buscar($request->id_registro_fiduciario_nota_devolutiva);

        $pedido = $registro_fiduciario->registro_fiduciario_pedido->pedido;

        DB::beginTransaction();

        try {
            // Argumentos para alterar nota devolutiva
            $args_nota_devolutiva = new stdClass();
            $args_nota_devolutiva->id_registro_fiduciario_nota_devolutiva_situacao = config('constants.REGISTRO_FIDUCIARIO.NOTA_DEVOLUTIVA.SITUACOES.AGUARDANDO_RESPOSTA');
            $args_nota_devolutiva->de_nota_devolutiva = $request->de_nota_devolutiva;
            $args_nota_devolutiva->id_nota_devolutiva_cumprimento = $request->id_nota_devolutiva_cumprimento;

            $registro_fiduciario_nota_devolutiva = $this->RegistroFiduciarioNotaDevolutivaServiceInterface->alterar($registro_fiduciario_nota_devolutiva, $args_nota_devolutiva);

            // Inserir causas raizes na nota devolutiva
            if (count($request->id_nota_devolutiva_causa_raizes)>0) {
                foreach ($request->id_nota_devolutiva_causa_raizes as $id_nota_devolutiva_causa_raiz) {
                    $nota_devolutiva_causa_raiz = $this->NotaDevolutivaCausaRaizServiceInterface->buscar($id_nota_devolutiva_causa_raiz);

                    $registro_fiduciario_nota_devolutiva->causas_raiz()->attach($nota_devolutiva_causa_raiz, [
                        'id_nota_devolutiva_causa_grupo' => $nota_devolutiva_causa_raiz->id_nota_devolutiva_causa_grupo,
                        'id_usuario_cad' => Auth::id()
                    ]);
                }
            }

            // Insere o histórico do pedido
            $this->HistoricoPedidoServiceInterface->inserir_historico($pedido, 'A nota devolutiva foi categorizada com sucesso.');

            // Atualizar data de alteração
            $args_registro_fiduciario = new stdClass();
            $args_registro_fiduciario->dt_alteracao = Carbon::now();

            $this->RegistroFiduciarioServiceInterface->alterar($registro_fiduciario, $args_registro_fiduciario);

            LogDB::insere(
                Auth::user()->id_usuario,
                6,
                'A nota devolutiva foi categorizada com sucesso.',
                'Registro - Notas devolutivas',
                'N',
                request()->ip()
            );

            $mensagem = 'Uma nota devolutiva foi inserida no sistema, para visualizar, acesse a aba "Notas devolutivas" nos detalhes do Registro.';
            $mensagemBradesco = "Após avaliação do contrato pelo Cartório de Registro de Imóveis foi identificado a necessidade de apresentar documentação adicional e/ou ressalvar o contrato.<br>Se necessário, acionaremos as partes.";
            $this->enviar_email_observador_registro($registro_fiduciario, $mensagem, $mensagemBradesco);
            $this->enviar_email_operadores_registro($registro_fiduciario, $mensagem, $mensagemBradesco);

            // Enviar Notificação
            if(!empty($pedido->url_notificacao)) {
                RegistroSituacaoNotificacao::dispatch($registro_fiduciario);
            }

            DB::commit();

            $response_json = [
                'status'=> 'sucesso',
                'recarrega' => 'true',
                'message' => 'A nota devolutiva foi categorizada com sucesso.',
            ];
            return response()->json($response_json, 200);

        } catch (Exception $e) {
            DB::rollback();

            LogDB::insere(
                Auth::user()->id_usuario,
                4,
                'Erro na inserção da categorização da nota devolutiva.',
                'Registro - Notas devolutivas',
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

        /**
     * @param Request $request
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function show_tab_nota_devolutiva(registro_fiduciario $registro)
    {
        $situacoes_notas_devolutivas = registro_fiduciario_nota_devolutiva_situacao::get(['id_registro_fiduciario_nota_devolutiva_situacao', 'no_nota_devolutiva_situacao', 'in_registro_ativo']);
        
        $compact_args = [
            'registro_fiduciario' => $registro,
            'situacoes_notas_devolutivas' => $situacoes_notas_devolutivas
        ];
        
        return view('app.produtos.registro-fiduciario.detalhes.geral-registro-detalhes-devolutivas', $compact_args);
    }

}
