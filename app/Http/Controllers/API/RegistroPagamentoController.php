<?php

namespace App\Http\Controllers\API;

use App\Domain\Configuracao\Contracts\ConfiguracaoPessoaServiceInterface;
use App\Http\Controllers\Controller;

use Helper;
use DB;
use LogDB;
use Upload;
use stdClass;
use Carbon\Carbon;
use Auth;
use Crypt;
use Gate;
use Exception;

use App\Domain\Pedido\Contracts\PedidoServiceInterface;
use App\Domain\Pedido\Contracts\HistoricoPedidoServiceInterface;

use App\Domain\RegistroFiduciario\Contracts\RegistroFiduciarioServiceInterface;
use App\Domain\RegistroFiduciario\Contracts\RegistroFiduciarioPagamentoServiceInterface;
use App\Domain\RegistroFiduciario\Contracts\RegistroFiduciarioPagamentoGuiaServiceInterface;
use App\Exceptions\RegdocException;
use App\Http\Requests\API\InserirPagamento;
use App\Http\Requests\API\StoreRegistroArquivosComprovante;

use App\Traits\EmailRegistro;

class RegistroPagamentoController extends Controller
{
    use EmailRegistro;

    public function __construct(protected PedidoServiceInterface $PedidoServiceInterface,
                                protected HistoricoPedidoServiceInterface $HistoricoPedidoServiceInterface,
                                protected RegistroFiduciarioServiceInterface $RegistroFiduciarioServiceInterface,
                                protected RegistroFiduciarioPagamentoServiceInterface $RegistroFiduciarioPagamentoServiceInterface,
                                protected RegistroFiduciarioPagamentoGuiaServiceInterface $RegistroFiduciarioPagamentoGuiaServiceInterface,
                                protected ConfiguracaoPessoaServiceInterface $ConfiguracaoPessoaServiceInterface)
    {
        parent::__construct();
    }

    public function index($uuid)
    {
        $registro_fiduciario = $this->RegistroFiduciarioServiceInterface->buscar_uuid($uuid);

        if(!$registro_fiduciario)
            throw new Exception('Registro não encontrado');

        Gate::authorize('api-registros-pagamentos', $registro_fiduciario);

        $pedido = $registro_fiduciario->registro_fiduciario_pedido->pedido;

        $pagamentos = [];
        foreach($pedido->registro_fiduciario_pedido->registro_fiduciario->registro_fiduciario_pagamentos as $key => $registro_fiduciario_pagamento) {
            $pagamentos[$key] = [
                'uuid' => $registro_fiduciario_pagamento->uuid,
                'tipo' => $registro_fiduciario_pagamento->id_registro_fiduciario_pagamento_tipo,
                'situacao' => $registro_fiduciario_pagamento->id_registro_fiduciario_pagamento_situacao,
                'isento' => ($registro_fiduciario_pagamento->in_isento ?? 'N'),
                'observacao' => $registro_fiduciario_pagamento->de_observacao,
                'total_guias' => count($registro_fiduciario_pagamento->registro_fiduciario_pagamento_guia)
            ];
        }

        $response_json = [
            'pagamentos' => $pagamentos
        ];
        return response()->json($response_json, 200);
    }

    public function show($uuid, $pagamento_uuid)
    {
        $registro_fiduciario_pagamento = $this->RegistroFiduciarioPagamentoServiceInterface->buscar_uuid($pagamento_uuid);

        if(!$registro_fiduciario_pagamento)
            throw new Exception('Pagamento não encontrado');

        $registro_fiduciario = $registro_fiduciario_pagamento->registro_fiduciario;

        Gate::authorize('api-registros-pagamentos', $registro_fiduciario);

        $pagamento = [
            'uuid' => $registro_fiduciario_pagamento->uuid,
            'tipo' => $registro_fiduciario_pagamento->id_registro_fiduciario_pagamento_tipo,
            'situacao' => $registro_fiduciario_pagamento->id_registro_fiduciario_pagamento_situacao,
            'isento' => ($registro_fiduciario_pagamento->in_isento ?? 'N'),
            'observacao' => $registro_fiduciario_pagamento->de_observacao,
            'total_guias' => count($registro_fiduciario_pagamento->registro_fiduciario_pagamento_guia)
        ];

        foreach($registro_fiduciario_pagamento->registro_fiduciario_pagamento_guia as $guia) {
            $arquivo = $this->array_arquivo($guia->arquivo_grupo_produto_guia);

            $pagamento['guias'][] = [
                'uuid' => $guia->uuid,
                'numero' => $guia->nu_guia,
                'serie' => $guia->nu_serie,
                'emissor' => $guia->no_emissor,
                'valor' => Helper::converte_float($guia->va_guia),
                'data_vencimento'=> ($guia->dt_vencimento ? Helper::formata_data_hora($guia->dt_vencimento, 'Y-m-d') : NULL),
                'arquivo' => $arquivo,
                'url_boleto' => ($guia->arisp_boleto ? $guia->arisp_boleto->url_boleto : NULL)
            ];
        }

        $response_json = [
            'pagamento' => $pagamento
        ];
        return response()->json($response_json, 200);
    }

    public function store(InserirPagamento $request, $uuid)
    {
        $registro_fiduciario = $this->RegistroFiduciarioServiceInterface->buscar_uuid($uuid);

        if(!$registro_fiduciario)
            throw new Exception('Registro não encontrado');

        Gate::authorize('api-registros-pagamento-novo', $registro_fiduciario);

        if ($registro_fiduciario) {
            DB::beginTransaction();

            try {
                $pedido = $registro_fiduciario->registro_fiduciario_pedido->pedido;

                // Argumentos novo pagamento!
                $args_pagamento = new stdClass();
                $args_pagamento->id_registro_fiduciario = $registro_fiduciario->id_registro_fiduciario;
                if ($request->in_isento === 'S') {
                    $args_pagamento->id_registro_fiduciario_pagamento_situacao = config('constants.REGISTRO_FIDUCIARIO.PAGAMENTOS.SITUACOES.ISENTO');
                } else {
                    $args_pagamento->id_registro_fiduciario_pagamento_situacao = config('constants.REGISTRO_FIDUCIARIO.PAGAMENTOS.SITUACOES.AGUARDANDO_GUIA');
                }
                $args_pagamento->id_registro_fiduciario_pagamento_tipo = $request->tipo_pagamento;
                $args_pagamento->de_observacao =  $request->de_observacao;
                $args_pagamento->in_isento = $request->in_isento;

                $registro_fiduciario_pagamento = $this->RegistroFiduciarioPagamentoServiceInterface->inserir($args_pagamento);

                // Insere o histórico do pedido
                $this->HistoricoPedidoServiceInterface->inserir_historico($pedido, 'O pagamento de '.$registro_fiduciario_pagamento->registro_fiduciario_pagamento_tipo->no_registro_fiduciario_pagamento_tipo.' foi inserido pela API com sucesso.');

                // Atualizar data de alteração
                $args_registro_fiduciario = new stdClass();
                $args_registro_fiduciario->dt_alteracao = Carbon::now();

                $this->RegistroFiduciarioServiceInterface->alterar($registro_fiduciario, $args_registro_fiduciario);

                if($registro_fiduciario_pagamento->in_isento === 'S') {
                    $destino = '/registro-fiduciario/'.$registro_fiduciario->id_registro_fiduciario.'/pagamentos/'.$registro_fiduciario_pagamento->id_registro_fiduciario_pagamento;
                    foreach ($request->arquivos as $arquivo) {
                        $novo_arquivo_grupo_produto = Upload::insere_arquivo_api($arquivo, config('constants.REGISTRO_FIDUCIARIO.ID_GRUPO_PRODUTO'), $destino, $arquivo['tipo']);
                        if ($novo_arquivo_grupo_produto) {
                            $args_pagamento_alterar = new stdClass();
                            $args_pagamento_alterar->id_arquivo_grupo_produto_isencao = $novo_arquivo_grupo_produto->id_arquivo_grupo_produto;

                            $this->RegistroFiduciarioPagamentoServiceInterface->alterar($registro_fiduciario_pagamento, $args_pagamento_alterar);
                        }
                    }
                } else {

                    $destino = '/registro-fiduciario/'.$registro_fiduciario_pagamento->registro_fiduciario->id_registro_fiduciario.'/pagamentos/'.$registro_fiduciario_pagamento->id_registro_fiduciario_pagamento;
                    foreach ($request->arquivos as $arquivo) {

                        $args_pagamento_guia = new stdClass();
                        $args_pagamento_guia->id_registro_fiduciario_pagamento = $registro_fiduciario_pagamento->id_registro_fiduciario_pagamento;
                        $args_pagamento_guia->nu_guia = $arquivo['nu_guia'];
                        $args_pagamento_guia->nu_serie = $arquivo['nu_serie'];
                        $args_pagamento_guia->va_guia = Helper::converte_float($arquivo['va_guia']);
                        $args_pagamento_guia->dt_vencimento = Carbon::createFromFormat('d/m/Y', $arquivo['dt_vencimento'])->endOfDay();
                        $args_pagamento_guia->no_emissor =  $arquivo['no_emissor'];  

                        $registro_fiduciario_pagamento_guia = $this->RegistroFiduciarioPagamentoGuiaServiceInterface->inserir($args_pagamento_guia);

                        $novo_arquivo_grupo_produto = Upload::insere_arquivo_api($arquivo, config('constants.REGISTRO_FIDUCIARIO.ID_GRUPO_PRODUTO'), $destino, $arquivo['tipo']);
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
                    $this->HistoricoPedidoServiceInterface->inserir_historico($pedido, 'A guia do pagamento '.$request->nu_guia.' foi inserida API com sucesso.');

                    // Atualizar data de alteração
                    $args_registro_fiduciario = new stdClass();
                    $args_registro_fiduciario->dt_alteracao = Carbon::now();

                    $this->RegistroFiduciarioServiceInterface->alterar($registro_fiduciario, $args_registro_fiduciario);
                    
                    
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
                                        $this->enviar_email_novo_pagamento($registro_fiduciario, $registro_fiduciario_pagamento, $args_email);
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
                                    $this->enviar_email_novo_pagamento($registro_fiduciario, $registro_fiduciario_pagamento, $args_email);
                                }
                            }
                        }
                    }

                    $mensagem = "O pagamento de ".$registro_fiduciario_pagamento->registro_fiduciario_pagamento_tipo->no_registro_fiduciario_pagamento_tipo." foi inserido na plataforma para envio dos comprovantes.";

                    $tipoPagamento = $registro_fiduciario_pagamento->registro_fiduciario_pagamento_tipo->no_registro_fiduciario_pagamento_tipo;

                    if($tipoPagamento == 'ITBI') {

                        $mensagemBradesco = 'A assinatura do contrato foi concluída e as informações para pagamento do ITBI estão disponíveis. O link para visualizar, pagar e enviar o comprovante foi enviado por e-mail ao comprador.';

                    } elseif ($tipoPagamento == 'Prenotação') {

                        $mensagemBradesco = 'As informações para pagamento das custas cartorárias (prenotação) estão disponíveis.<br>O <i>link</i> para visualizar, pagar e enviar o comprovante foi enviado por e-mail ao comprador.';

                    } else {

                        $mensagemBradesco = "A guia do ".$registro_fiduciario_pagamento->registro_fiduciario_pagamento_tipo->no_registro_fiduciario_pagamento_tipo." está disponível para envio do(s) comprovante(s) de pagamento.";
                    }

                    $this->enviar_email_observador_registro($registro_fiduciario, $mensagem, $mensagemBradesco);
                    $this->enviar_email_operadores_registro($registro_fiduciario, $mensagem, $mensagemBradesco);
                }

                LogDB::insere(
                    Auth::user()->id_usuario,
                    6,
                    'O pagamento via API foi inserido com sucesso.',
                    'Registro - Pagamentos',
                    'N',
                    request()->ip()
                );

                DB::commit();

                $response_json = [
                    'status'=> 'sucesso',
                    'message' => 'O pagamento foi inserido com sucesso.',
                ];
                return response()->json($response_json, 200);

            }catch (RegdocException $e) {
                DB::rollback();
    
                $response_json = [
                    'message' => $e->getMessage()
                ];
    
                return response()->json($response_json, 400);
            }    
        }
    }

    public function salvar_comprovante(StoreRegistroArquivosComprovante $request)
    {
        $registro_fiduciario_pagamento_guia = $this->RegistroFiduciarioPagamentoGuiaServiceInterface->buscar_uuid($request->guia);

        if(!$registro_fiduciario_pagamento_guia)
            throw new Exception('Guia não encontrada');

        Gate::authorize('api-registros-pagamentos-salvar-comprovante', $registro_fiduciario_pagamento_guia);

        DB::beginTransaction();

        try {
            $registro_fiduciario_pagamento = $registro_fiduciario_pagamento_guia->registro_fiduciario_pagamento;
            $registro_fiduciario = $registro_fiduciario_pagamento->registro_fiduciario;
            $pedido = $registro_fiduciario->registro_fiduciario_pedido->pedido;

            $destino = '/registro-fiduciario/'.$registro_fiduciario->id_registro_fiduciario.'/pagamentos/'.$registro_fiduciario_pagamento->id_registro_fiduciario_pagamento;
            $novo_arquivo_grupo_produto = Upload::insere_arquivo_api($request->arquivo_comprovante, config('constants.REGISTRO_FIDUCIARIO.ID_GRUPO_PRODUTO'), $destino, config('constants.TIPO_ARQUIVO.11.ID_GUIA_COMPROVANTE'));

            $args_pagamento_guia_alterar = new stdClass();
            $args_pagamento_guia_alterar->id_arquivo_grupo_produto_comprovante = $novo_arquivo_grupo_produto->id_arquivo_grupo_produto;
            $this->RegistroFiduciarioPagamentoGuiaServiceInterface->alterar($registro_fiduciario_pagamento_guia, $args_pagamento_guia_alterar);

            $args_alterar_pagamento = new stdClass();
            $args_alterar_pagamento->id_registro_fiduciario_pagamento_situacao = config('constants.REGISTRO_FIDUCIARIO.PAGAMENTOS.SITUACOES.AGUARDANDO_VALIDACAO');
            $this->RegistroFiduciarioPagamentoServiceInterface->alterar($registro_fiduciario_pagamento, $args_alterar_pagamento);

            // Insere o histórico do pedido
            $this->HistoricoPedidoServiceInterface->inserir_historico($pedido, 'O comprovante da guia de pagamento '.$registro_fiduciario_pagamento_guia->nu_guia.' foi inserido com sucesso.');

            // Atualizar data de alteração
            $args_registro_fiduciario = new stdClass();
            $args_registro_fiduciario->dt_alteracao = Carbon::now();
            $this->RegistroFiduciarioServiceInterface->alterar($registro_fiduciario, $args_registro_fiduciario);

            DB::commit();

            LogDB::insere(
                Auth::user()->id_usuario,
                6,
                'O comprovante do pagamento foi inserido com sucesso via API.',
                'Registro - Pagamentos',
                'N',
                request()->ip()
            );

            $response_json = [
                'message' => 'O comprovante do pagamento foi inserido com sucesso.',
            ];
            return response()->json($response_json, 200);
        } catch(Exception $e) {
            DB::rollback();

            LogDB::insere(
                Auth::user()->id_usuario,
                4,
                'Erro na inserção do comprovante do pagamento via API.',
                'Registro - Pagamentos',
                'N',
                request()->ip(),
                $e->getMessage() . ' na linha ' . $e->getLine() . ' do arquivo ' . $e->getFile()
            );

            $response_json = [
                'message' => 'Erro ao processar a requisição. '.(config('app.env')!='production'?$e->getMessage() . ' na linha ' . $e->getLine() . ' do arquivo ' . $e->getFile():'')
            ];
            return response()->json($response_json);
        }
    }

    private function array_arquivo($arquivo)
    {
        if ($arquivo) {
            $path_arquivo = 'public'.$arquivo->no_local_arquivo.'/'.$arquivo->no_arquivo;
            $array_arquivo = [
                "uuid" => $arquivo->uuid,
                "nome" => $arquivo->no_descricao_arquivo,
                'tamanho' => intval($arquivo->nu_tamanho_kb),
                'extensao' => $arquivo->no_extensao
            ];

            return $array_arquivo;
        } else {
            return [];
        }
    }
}
