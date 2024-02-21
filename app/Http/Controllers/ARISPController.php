<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Storage;
use DB;
use Carbon\Carbon;
use Exception;
use stdClass;
use Crypt;

use SoapFault;

use App\Exceptions\RegdocException;
use App\Exceptions\ArispException;

use App\Helpers\ARISP;
use Helper;

use App\Models\arisp_arquivo;
use App\Models\arisp_arquivo_download;
use App\Models\arquivo_grupo_produto;
use App\Models\arisp_pedido;
use App\Models\arisp_pedido_historico;

use App\Domain\Arisp\Contracts\ArispAnexoServiceInterface;
use App\Domain\Arisp\Contracts\ArispBoletoServiceInterface;
use App\Domain\Arquivo\Contracts\ArquivoServiceInterface;
use App\Domain\Pedido\Contracts\PedidoServiceInterface;
use App\Domain\Pedido\Contracts\HistoricoPedidoServiceInterface;
use App\Domain\RegistroFiduciario\Contracts\RegistroFiduciarioNotaDevolutivaServiceInterface;
use App\Domain\RegistroFiduciario\Contracts\RegistroFiduciarioPagamentoServiceInterface;
use App\Domain\RegistroFiduciario\Contracts\RegistroFiduciarioPagamentoGuiaServiceInterface;
use App\Domain\RegistroFiduciario\Contracts\RegistroFiduciarioServiceInterface;
use App\Domain\Configuracao\Contracts\ConfiguracaoPessoaServiceInterface;

use App\Jobs\RegistroSituacaoNotificacao;

use App\Traits\EmailRegistro;

class ARISPController extends Controller
{
    use EmailRegistro;

    /**
     * @var ArispAnexoServiceInterface
     * @var ArispBoletoServiceInterface
     * @var ArquivoServiceInterface
     * @var PedidoServiceInterface
     * @var HistoricoPedidoServiceInterface
     * @var RegistroFiduciarioNotaDevolutivaServiceInterface
     * @var RegistroFiduciarioPagamentoServiceInterface
     * @var RegistroFiduciarioPagamentoGuiaServiceInterface
     * @var RegistroFiduciarioServiceInterface
     * @var ConfiguracaoPessoaServiceInterface
     */
    protected $ArispAnexoServiceInterface;
    protected $ArispBoletoServiceInterface;
    protected $ArquivoServiceInterface;
    protected $PedidoServiceInterface;
    protected $HistoricoPedidoServiceInterface;
    protected $RegistroFiduciarioNotaDevolutivaServiceInterface;
    protected $RegistroFiduciarioPagamentoServiceInterface;
    protected $RegistroFiduciarioPagamentoGuiaServiceInterface;
    protected $RegistroFiduciarioServiceInterface;
    protected $ConfiguracaoPessoaServiceInterface;

    public function __construct(ArispAnexoServiceInterface $ArispAnexoServiceInterface,
                                ArispBoletoServiceInterface $ArispBoletoServiceInterface,
                                ArquivoServiceInterface $ArquivoServiceInterface,
                                PedidoServiceInterface $PedidoServiceInterface,
                                HistoricoPedidoServiceInterface $HistoricoPedidoServiceInterface,
                                RegistroFiduciarioNotaDevolutivaServiceInterface $RegistroFiduciarioNotaDevolutivaServiceInterface,
                                RegistroFiduciarioPagamentoServiceInterface $RegistroFiduciarioPagamentoServiceInterface,
                                RegistroFiduciarioPagamentoGuiaServiceInterface $RegistroFiduciarioPagamentoGuiaServiceInterface,
                                RegistroFiduciarioServiceInterface $RegistroFiduciarioServiceInterface,
                                ConfiguracaoPessoaServiceInterface $ConfiguracaoPessoaServiceInterface)
    {
        $this->ArispAnexoServiceInterface = $ArispAnexoServiceInterface;
        $this->ArispBoletoServiceInterface = $ArispBoletoServiceInterface;
        $this->ArquivoServiceInterface = $ArquivoServiceInterface;
        $this->PedidoServiceInterface = $PedidoServiceInterface;
        $this->HistoricoPedidoServiceInterface = $HistoricoPedidoServiceInterface;
        $this->RegistroFiduciarioNotaDevolutivaServiceInterface = $RegistroFiduciarioNotaDevolutivaServiceInterface;
        $this->RegistroFiduciarioPagamentoServiceInterface = $RegistroFiduciarioPagamentoServiceInterface;
        $this->RegistroFiduciarioPagamentoGuiaServiceInterface = $RegistroFiduciarioPagamentoGuiaServiceInterface;
        $this->RegistroFiduciarioServiceInterface = $RegistroFiduciarioServiceInterface;
        $this->ConfiguracaoPessoaServiceInterface = $ConfiguracaoPessoaServiceInterface;
    }

    public function download_arquivo(Request $request)
    {
        DB::beginTransaction();

        try {
            $arisp_arquivo = new arisp_arquivo();
            $arisp_arquivo = $arisp_arquivo->where('codigo_arquivo', $request->codigo_arquivo)->first();

            if ($arisp_arquivo) {
                if ($request->hash!='HashManual#ValidHub@2021') {
                    $verifica_hash = ARISP::verifica_hash($request->hash);
                    if ($verifica_hash['status']!='sucesso') {
                        throw new RegdocException('Erro ao verificar a origem da requisição (Hash). Motivo: '.$verifica_hash['message']);
                    }
                }

                $arisp_arquivo->dt_ultimo_download = Carbon::now();
                if(!$arisp_arquivo->save()) {
                    throw new RegdocException('Erro ao atualizar o arquivo.');
                }

                $novo_arquivo_download = new arisp_arquivo_download();
                $novo_arquivo_download->id_arisp_arquivo = $arisp_arquivo->id_arisp_arquivo;
                $novo_arquivo_download->hash_verificacao = $request->hash;
                $novo_arquivo_download->token_verificacao = $verifica_hash['token'] ?? NULL;
                $novo_arquivo_download->id_arisp_transacao_verificacao = $verifica_hash['transacao']->id_arisp_transacao ?? NULL;
                $novo_arquivo_download->id_usuario_cad = 1;
                $novo_arquivo_download->dt_cadastro = Carbon::now();
                if(!$novo_arquivo_download->save()) {
                    throw new RegdocException('Erro ao salvar o download do arquivo.');
                }

                $arquivo_grupo_produto = new arquivo_grupo_produto();
				$arquivo_grupo_produto = $arquivo_grupo_produto->find($arisp_arquivo->arquivo_grupo_produto->id_arquivo_grupo_produto);

				if ($arquivo_grupo_produto) {
					$arquivo = Storage::get('/public/'.$arquivo_grupo_produto->no_local_arquivo.'/'.$arquivo_grupo_produto->no_arquivo);
					$retorno = response($arquivo, 200)->header('Content-Disposition', 'attachment; filename="'.$arquivo_grupo_produto->no_arquivo.'"');
    			} else {
                    throw new RegdocException('O arquivo não foi encontrado.');
    	        }
            } else {
                throw new RegdocException('O código informado não existe.');
            }

            $this->salvar_log(
                'Download do arquivo',
                $request,
                false,
                'download do arquivo realizado com sucesso!'
            );

            DB::commit();

            return $retorno;
        } catch(SoapFault $e) {
            $this->salvar_log(
                'Download do arquivo',
                $request,
                true,
                'Erro no envio ao WebService! Descrição: '.$e->getMessage()
            );
            DB::commit();

            return 'Erro no envio ao WebService, por favor, tente novamente mais tarde.' . (config('app.env')!='production'? ' Descrição: '.$e->getMessage() : '');
        } catch(ArispException $e) {
            $this->salvar_log(
                'Download do arquivo',
                $request,
                true,
                'Erro ao verificar a origem da requisição (Hash). Motivo: '.$e->getMessage()
            );

            DB::commit();

            return 'Erro ao verificar a origem da requisição (Hash). Motivo: '.$e->getMessage();
        } catch(RegdocException $e) {
            DB::rollback();

            $this->salvar_log(
                'Download do arquivo',
                $request,
                true,
                'Motivo: ' . $e->getMessage()
            );

            return $e->getMessage();
        } catch(Exception $e) {
            DB::rollback();

            $this->salvar_log(
                'Download do arquivo',
                $request,
                true,
                'Erro interno, por favor, tente novamente mais tarde. Descrição: '.$e->getMessage().' - Linha: '.$e->getLine().' - Arquivo: '.$e->getFile()
            );

            return 'Erro interno, por favor, tente novamente mais tarde.' . (config('app.env')!='production'? ' Descrição: '.$e->getMessage().' - Linha: '.$e->getLine().' - Arquivo: '.$e->getFile() : '');
        }
    }

    public function notificacao(Request $request)
    {
        DB::beginTransaction();

        try {
            if ($request->tp) {
                switch ($request->tp) {
                    case '1':
                        $retorno = $this->notificacao_cadastro($request);
                        $titulo = 'Notificação de Cadastro';
                        break;
                    case '2':
                        $retorno = $this->notificacao_status($request);
                        $titulo = 'Notificação de Status';
                        break;
                    default:
                        throw new RegdocException('O tipo de notificação desconhecido.');
                        break;
                }
            } else {
                throw new RegdocException('O tipo de notificação não foi informado.');
            }

            $this->salvar_log(
                $titulo,
                $request,
                false,
                'Notificação realizada com sucesso!'
            );

            DB::commit();

            return $retorno;
        } catch(SoapFault $e) {
            $this->salvar_log(
                'Notificação',
                $request,
                true,
                'Erro no envio ao WebService, por favor, tente novamente mais tarde. Descrição: '.$e->getMessage()
            );
            DB::commit();

            return 'Erro no envio ao WebService, por favor, tente novamente mais tarde.' . (config('app.env')!='production'? ' Descrição: '.$e->getMessage() : '');
        } catch(ArispException $e) {
            $this->salvar_log(
                'Notificação',
                $request,
                true,
                'Erro ao verificar a origem da requisição (Hash). Motivo: '.$e->getMessage()
            );
            DB::commit();

            return 'Erro ao verificar a origem da requisição (Hash). Motivo: '.$e->getMessage();
        } catch(RegdocException $e) {
            DB::rollback();

            $this->salvar_log(
                'Notificação',
                $request,
                true,
              'Motivo: '. $e->getMessage()
            );

            return $e->getMessage();
        } catch(Exception $e) {
            DB::rollback();

            $this->salvar_log(
                'Notificação',
                $request,
                true,
                'Erro interno, por favor, tente novamente mais tarde. Descrição: '.$e->getMessage().' - Linha: '.$e->getLine().' - Arquivo: '.$e->getFile()
            );

            return 'Erro interno, por favor, tente novamente mais tarde.' . (config('app.env')!='production'? ' Descrição: '.$e->getMessage().' - Linha: '.$e->getLine().' - Arquivo: '.$e->getFile() : '');
        }
    }

    private function notificacao_cadastro($request)
    {
        if ($request->hash!='HashManual#ValidHub@2021') {
            $verifica_hash = ARISP::verifica_hash($request->hash);
            if ($verifica_hash['status']!='sucesso') {
                throw new RegdocException('Erro ao verificar a origem da requisição (Hash). Motivo: '.$verifica_hash['message']);
            }
        }

        $arisp_pedido = new arisp_pedido();
        $arisp_pedido = $arisp_pedido->where('protocolo_temporario', $request->t)
                                     ->first();

        if ($arisp_pedido) {
            $arisp_pedido->id_pedido_arisp = $request->id;
            $arisp_pedido->pedido_protocolo = $request->p;
            $arisp_pedido->id_usuario_alt = 1;
            $arisp_pedido->dt_atualizacao = Carbon::now();
            if(!$arisp_pedido->save()) {
                throw new RegdocException('Erro ao atualizar o pedido.');
            }

            return 'Pedido atualizado com sucesso.';
        } else {
            throw new RegdocException('O pedido não foi encontrado.');
        }
    }

    private function notificacao_status($request)
    {
        if ($request->hash!='HashManual#ValidHub@2021') {
            $verifica_hash = ARISP::verifica_hash($request->hash);
            if ($verifica_hash['status']!='sucesso') {
                throw new RegdocException('Erro ao verificar a origem da requisição (Hash). Motivo: '.$verifica_hash['message']);
            }
        }

        $arisp_pedido = new arisp_pedido();
        $arisp_pedido = $arisp_pedido->where('id_pedido_arisp', $request->id)
                                     ->first();

        if ($arisp_pedido) {
            $pedido = $this->PedidoServiceInterface->buscar($arisp_pedido->id_pedido);

            $registro_fiduciario = $pedido->registro_fiduciario_pedido->registro_fiduciario;

            if(!in_array($registro_fiduciario->id_integracao, [
                config('constants.INTEGRACAO.XML_ARISP'),
                config('constants.INTEGRACAO.ARISP')
                ])) {
                throw new RegdocException('O tipo de integração não permite atualização do pedido.');
            }

            $arisp_pedido->id_arisp_pedido_status = $request->IDStatus;
            $arisp_pedido->vl_taxa_admin = Helper::converte_float($request->VlTaxaAdmin);
            $arisp_pedido->vl_prenotacao = Helper::converte_float($request->VlPrenotacao);
            $arisp_pedido->vl_registro = Helper::converte_float($request->VLRegistro);
            $arisp_pedido->vl_exame_calculo = Helper::converte_float($request->VLExameCalculo);
            $arisp_pedido->vl_taxa_iss = Helper::converte_float($request->VlTaxaISS);
            $arisp_pedido->vl_total = Helper::converte_float($request->VLTotal);
            $arisp_pedido->id_usuario_alt = 1;
            $arisp_pedido->dt_atualizacao = Carbon::now();
            if(!$arisp_pedido->save()) {
                throw new RegdocException('Erro ao atualizar o pedido.');
            }

            $novo_arisp_pedido_historico = new arisp_pedido_historico();
            $novo_arisp_pedido_historico->id_arisp_pedido = $arisp_pedido->id_arisp_pedido;
            $novo_arisp_pedido_historico->id_arisp_pedido_status = $request->IDStatus;
            $novo_arisp_pedido_historico->vl_taxa_admin = Helper::converte_float($request->VlTaxaAdmin);
            $novo_arisp_pedido_historico->vl_prenotacao = Helper::converte_float($request->VlPrenotacao);
            $novo_arisp_pedido_historico->vl_registro = Helper::converte_float($request->VLRegistro);
            $novo_arisp_pedido_historico->vl_exame_calculo = Helper::converte_float($request->VLExameCalculo);
            $novo_arisp_pedido_historico->vl_taxa_iss = Helper::converte_float($request->VlTaxaISS);
            $novo_arisp_pedido_historico->vl_total = Helper::converte_float($request->VLTotal);
            $novo_arisp_pedido_historico->hash_verificacao = $request->hash;
            $novo_arisp_pedido_historico->token_verificacao = $verifica_hash['token'] ?? NULL;
            $novo_arisp_pedido_historico->id_arisp_transacao_verificacao = $verifica_hash['transacao']->id_arisp_transacao ?? NULL;
            $novo_arisp_pedido_historico->id_usuario_cad = 1;
            $novo_arisp_pedido_historico->dt_cadastro = Carbon::now();
            if(!$novo_arisp_pedido_historico->save()) {
                throw new RegdocException('Erro ao salvar o histórico do pedido.');
            }

            $dt_prenotacao = null;
            $dt_vencto_prenotacao = null;
            $id_situacao_pedido_grupo_produto = 0;
            switch ($arisp_pedido->id_arisp_pedido_status) {
                case 3: // Prenotado
                    $id_situacao_pedido_grupo_produto = config('constants.SITUACAO.11.ID_EM_PROCESSAMENTO');
                    $consulta = ARISP::consulta_pedido($arisp_pedido->id_pedido_arisp);
                    $numero_prenotacao = $consulta['pedido']['numero_prenotacao'] ?? NULL;
                    $dt_prenotacao = $consulta['pedido']['data_prenotacao'] ?? NULL;
                    $dt_vencto_prenotacao = $consulta['pedido']['data_vencto_prenotacao'] ?? NULL;
                     
                    // Enviar e-mail de prenotação
                    if ($registro_fiduciario->registro_fiduciario_parte->count()>0) {
                        $registro_fiduciario_partes = $registro_fiduciario->registro_fiduciario_parte()
                            ->whereIn('id_tipo_parte_registro_fiduciario', [
                                config('constants.REGISTRO_FIDUCIARIO.PARTES.ID_TIPO_PARTE_ADQUIRENTE'),
                                config('constants.REGISTRO_FIDUCIARIO.PARTES.ID_TIPO_PARTE_TRANSMITENTE'),
                                config('constants.REGISTRO_FIDUCIARIO.PARTES.ID_TIPO_PARTE_DEVEDOR'),
                                config('constants.REGISTRO_FIDUCIARIO.PARTES.ID_TIPO_PARTE_PARTE')
                            ])
                            ->get();

                        foreach ($registro_fiduciario_partes as $registro_fiduciario_parte) {
                            if (count($registro_fiduciario_parte->registro_fiduciario_procurador)>0) {
                                foreach ($registro_fiduciario_parte->registro_fiduciario_procurador as $procurador) {
                                    $args_email = [
                                        'no_email_contato' => $procurador->no_email_contato,
                                        'no_contato' => $procurador->no_parte,
                                        'senha' => Crypt::decryptString($procurador->pedido_usuario->pedido_usuario_senha->senha_crypt),
                                        'token' => $procurador->pedido_usuario->token,
                                        'numero_prenotacao' => $numero_prenotacao
                                    ];
                                    $this->enviar_email_registro_prenotado($registro_fiduciario, $args_email);
                                }
                            } else {
                                $args_email = [
                                    'no_email_contato' => $registro_fiduciario_parte->no_email_contato,
                                    'no_contato' => $registro_fiduciario_parte->no_parte,
                                    'senha' => Crypt::decryptString($registro_fiduciario_parte->pedido_usuario->pedido_usuario_senha->senha_crypt),
                                    'token' => $registro_fiduciario_parte->pedido_usuario->token,
                                    'numero_prenotacao' => $numero_prenotacao
                                ];
                                $this->enviar_email_registro_prenotado($registro_fiduciario, $args_email);
                            }
                        }
                    }
                  
                    //Enviar email observador
                    $mensagem = "O cartório realizou a prenotação do registro " . $registro_fiduciario->registro_fiduciario_pedido->pedido->protocolo_pedido . ($numero_prenotacao ? ' com o número ' . $numero_prenotacao : '') . "), acesse o sistema para mais detalhes.";

                    $mensagemBradesco = "As informações para pagamento das custas cartorárias (prenotação) estão disponíveis.<br>O <i>link</i> para visualizar, pagar e enviar o comprovante foi enviado por e-mail ao comprador.";

                    $this->enviar_email_observador_registro($registro_fiduciario, $mensagem, $mensagemBradesco);
                    $this->enviar_email_operadores_registro($registro_fiduciario, $mensagem, $mensagemBradesco);
                    break;
                case 1: // Em aberto
                case 2: // Processando
                case 8: // Reaberto - Não Concluído
                case 10: // Pagamento Efetivado
                case 12: // Pagto Complementar Efetivado
                    $id_situacao_pedido_grupo_produto = config('constants.SITUACAO.11.ID_EM_PROCESSAMENTO');
                    break;
                case 5: // Devolvido
                    $id_situacao_pedido_grupo_produto = config('constants.SITUACAO.11.ID_DEVOLVIDO');
                    break;
                case 6: // Nota de Exigência
                    // Argumentos nova nota devolutiva
                    $args_nota_devolutiva = new stdClass();
                    $args_nota_devolutiva->id_registro_fiduciario_nota_devolutiva_situacao = config('constants.REGISTRO_FIDUCIARIO.NOTA_DEVOLUTIVA.SITUACOES.AGUARDANDO_CATEGORIZACAO');
                    $args_nota_devolutiva->id_registro_fiduciario = $registro_fiduciario->id_registro_fiduciario;
                    $args_nota_devolutiva->de_nota_devolutiva = 'Nota devolutiva recebida via integração.';
                    $args_nota_devolutiva->id_usuario_cad = 1;

                    $nova_registro_fiduciario_nota_devolutiva = $this->RegistroFiduciarioNotaDevolutivaServiceInterface->inserir($args_nota_devolutiva);

                    $arquivos = $this->download_anexos('anexos_exigencia', $arisp_pedido->id_pedido_arisp);
                    if ($arquivos) {
                        $destino = '/registro-fiduciario/'.$registro_fiduciario->id_registro_fiduciario.'/devolutivas/'.$nova_registro_fiduciario_nota_devolutiva->id_registro_fiduciario_nota_devolutiva;
                        foreach ($arquivos as $arquivo) {
                            if (!$this->ArispAnexoServiceInterface->buscar_codigo($arquivo['codigo_anexo'])) {
                                $args_novo_arquivo = new stdClass();
                                $args_novo_arquivo->id_grupo_produto = config('constants.REGISTRO_FIDUCIARIO.ID_GRUPO_PRODUTO');
                                $args_novo_arquivo->id_tipo_arquivo_grupo_produto = config('constants.TIPO_ARQUIVO.11.ID_NOTA_DEVOLUTIVA');
                                $args_novo_arquivo->no_arquivo = $arquivo['nome_anexo'];
                                $args_novo_arquivo->no_local_arquivo = $destino;
                                $args_novo_arquivo->no_descricao_arquivo = $arquivo['descricao'];
                                $args_novo_arquivo->no_extensao = $arquivo['extensao_anexo'];
                                $args_novo_arquivo->nu_tamanho_kb = Storage::size($arquivo['local_arquivo']);
                                $args_novo_arquivo->no_hash = hash('md5', Helper::raw_arquivo($arquivo['local_arquivo']));
                                $args_novo_arquivo->no_mime_type = Helper::mime_arquivo($arquivo['local_arquivo']);
                                $args_novo_arquivo->id_usuario = 1;

                                $novo_arquivo_grupo_produto = $this->ArquivoServiceInterface->inserir($args_novo_arquivo);
                                $nova_registro_fiduciario_nota_devolutiva->arquivos_grupo()->attach($novo_arquivo_grupo_produto, ['id_usuario_cad' => 1]);

                                $args_novo_arisp_anexo = new stdClass();
                                $args_novo_arisp_anexo->id_arisp_pedido = $arisp_pedido->id_arisp_pedido;
                                $args_novo_arisp_anexo->id_arquivo_grupo_produto = $novo_arquivo_grupo_produto->id_arquivo_grupo_produto;
                                $args_novo_arisp_anexo->id_arisp_anexo_tipo = config('constants.REGISTRO_FIDUCIARIO.ARISP.TIPO_ANEXO.EXIGENCIA');
                                $args_novo_arisp_anexo->descricao = $arquivo['descricao'];
                                $args_novo_arisp_anexo->nome_anexo = $arquivo['nome_anexo'];
                                $args_novo_arisp_anexo->codigo_anexo = $arquivo['codigo_anexo'];
                                $args_novo_arisp_anexo->url_anexo = $arquivo['url_anexo'];
                                $args_novo_arisp_anexo->dt_anexo = $arquivo['dt_anexo'];
                                $args_novo_arisp_anexo->id_usuario_cad = 1;

                                $this->ArispAnexoServiceInterface->inserir($args_novo_arisp_anexo);

                                $destino_final = 'public/'.$destino.'/'.$arquivo['nome_anexo'];
                                Storage::copy($arquivo['local_arquivo'], $destino_final);
                            }
                        }
                    }

                    $id_situacao_pedido_grupo_produto = config('constants.SITUACAO.11.ID_NOTA_DEVOLUTIVA');
                    break;
                case 7: // Registrado/Averbado
                    $arquivos = $this->download_anexos('anexos_averbacao', $arisp_pedido->id_pedido_arisp);
                    if ($arquivos) {
                        $destino = '/registro-fiduciario/'.$registro_fiduciario->id_registro_fiduciario.'/resultado';
                        foreach ($arquivos as $arquivo) {
                            if (!$this->ArispAnexoServiceInterface->buscar_codigo($arquivo['codigo_anexo'])) {
                                $args_novo_arquivo = new stdClass();
                                $args_novo_arquivo->id_grupo_produto = config('constants.REGISTRO_FIDUCIARIO.ID_GRUPO_PRODUTO');
                                $args_novo_arquivo->id_tipo_arquivo_grupo_produto = config('constants.TIPO_ARQUIVO.11.ID_RESULTADO');
                                $args_novo_arquivo->no_arquivo = $arquivo['nome_anexo'];
                                $args_novo_arquivo->no_local_arquivo = $destino;
                                $args_novo_arquivo->no_descricao_arquivo = $arquivo['descricao'];
                                $args_novo_arquivo->no_extensao = $arquivo['extensao_anexo'];
                                $args_novo_arquivo->nu_tamanho_kb = Storage::size($arquivo['local_arquivo']);
                                $args_novo_arquivo->no_hash = hash('md5', Helper::raw_arquivo($arquivo['local_arquivo']));
                                $args_novo_arquivo->no_mime_type = Helper::mime_arquivo($arquivo['local_arquivo']);
                                $args_novo_arquivo->id_usuario = 1;

                                $novo_arquivo_grupo_produto = $this->ArquivoServiceInterface->inserir($args_novo_arquivo);
                                $registro_fiduciario->arquivos_grupo()->attach($novo_arquivo_grupo_produto);

                                $args_novo_arisp_anexo = new stdClass();
                                $args_novo_arisp_anexo->id_arisp_pedido = $arisp_pedido->id_arisp_pedido;
                                $args_novo_arisp_anexo->id_arquivo_grupo_produto = $novo_arquivo_grupo_produto->id_arquivo_grupo_produto;
                                $args_novo_arisp_anexo->id_arisp_anexo_tipo = config('constants.REGISTRO_FIDUCIARIO.ARISP.TIPO_ANEXO.AVERBACAO');
                                $args_novo_arisp_anexo->descricao = $arquivo['descricao'];
                                $args_novo_arisp_anexo->nome_anexo = $arquivo['nome_anexo'];
                                $args_novo_arisp_anexo->codigo_anexo = $arquivo['codigo_anexo'];
                                $args_novo_arisp_anexo->url_anexo = $arquivo['url_anexo'];
                                $args_novo_arisp_anexo->dt_anexo = $arquivo['dt_anexo'];
                                $args_novo_arisp_anexo->id_usuario_cad = 1;

                                $this->ArispAnexoServiceInterface->inserir($args_novo_arisp_anexo);

                                $destino_final = 'public/'.$destino.'/'.$arquivo['nome_anexo'];
                                Storage::copy($arquivo['local_arquivo'], $destino_final);
                            }
                        }
                    }

                    // Add a data do registro finalizado.
                    $args_registro_fiduciario = new stdClass();
                    $args_registro_fiduciario->dt_registro = Carbon::now();

                    $this->RegistroFiduciarioServiceInterface->alterar($registro_fiduciario,$args_registro_fiduciario);

                    $id_situacao_pedido_grupo_produto = config('constants.SITUACAO.11.ID_REGISTRADO');
                    break;
                case 9: // Aguardando Pagamento
                    /*
                    $boletos = $this->download_boletos($arisp_pedido->id_pedido_arisp);
                    if (count($boletos)>0) {
                        // Argumentos novo pagamento!
                        $args_novo_pagamento = new stdClass();
                        $args_novo_pagamento->id_registro_fiduciario = $registro_fiduciario->id_registro_fiduciario;
                        $args_novo_pagamento->id_registro_fiduciario_pagamento_situacao = config('constants.REGISTRO_FIDUCIARIO.PAGAMENTOS.SITUACOES.AGUARDANDO_COMPROVANTE');
                        $args_novo_pagamento->id_registro_fiduciario_pagamento_tipo = config('constants.REGISTRO_FIDUCIARIO.PAGAMENTOS.TIPOS.EMOLUMENTOS');
                        $args_novo_pagamento->de_observacao = 'Pagamento recebido via integração.';
                        $args_novo_pagamento->in_isento = 'N';
                        $args_novo_pagamento->id_usuario_cad = 1;

                        $novo_registro_fiduciario_pagamento = $this->RegistroFiduciarioPagamentoServiceInterface->inserir($args_novo_pagamento);

                        foreach ($boletos as $boleto) {
                            $arisp_boleto = $this->ArispBoletoServiceInterface->buscar_url($boleto['url_boleto']);
                            if (!$arisp_boleto) {
                                // Argumentos novo arisp boleto
                                $args_novo_arisp_boleto = new stdClass();
                                $args_novo_arisp_boleto->id_arisp_pedido = $arisp_pedido->id_arisp_pedido;
                                $args_novo_arisp_boleto->url_boleto = $boleto['url_boleto'];
                                $args_novo_arisp_boleto->dt_boleto = $boleto['dt_boleto'];
                                $args_novo_arisp_boleto->id_usuario_cad = 1;

                                $novo_arisp_boleto = $this->ArispBoletoServiceInterface->inserir($args_novo_arisp_boleto);

                                $id_arisp_boleto = $novo_arisp_boleto->id_arisp_boleto;
                            } else {
                                $id_arisp_boleto = $arisp_boleto->id_arisp_boleto;
                            }

                            // Argumentos nova guia do pagamento
                            $args_novo_pagamento_guia = new stdClass();
                            $args_novo_pagamento_guia->id_registro_fiduciario_pagamento = $novo_registro_fiduciario_pagamento->id_registro_fiduciario_pagamento;
                            $args_novo_pagamento_guia->nu_guia = '';
                            $args_novo_pagamento_guia->nu_serie = '';
                            $args_novo_pagamento_guia->va_guia = 0;
                            $args_novo_pagamento_guia->no_emissor =  'Central de Registros';
                            $args_novo_pagamento_guia->id_arisp_boleto =  $id_arisp_boleto;
                            $args_novo_pagamento_guia->id_usuario_cad = 1;

                            $this->RegistroFiduciarioPagamentoGuiaServiceInterface->inserir($args_novo_pagamento_guia);
                        }
                    }
                    */

                    $id_situacao_pedido_grupo_produto = config('constants.SITUACAO.11.ID_EM_PROCESSAMENTO');
                    break;
            }

            $arisp_pedido->protocolo_prenotacao = $numero_prenotacao ?? NULL;
            $arisp_pedido->dt_atualizacao = Carbon::now();
            if(!$arisp_pedido->save()) {
                throw new RegdocException('Erro ao atualizar a prenotação do pedido.');
            }

            if($pedido->id_situacao_pedido_grupo_produto != config('constants.SITUACAO.11.ID_REGISTRADO')) {
                if ($id_situacao_pedido_grupo_produto != $pedido->id_situacao_pedido_grupo_produto) {
                    $this->PedidoServiceInterface->alterar_situacao($pedido, $id_situacao_pedido_grupo_produto);

                    // Insere o histórico do pedido
                    $this->HistoricoPedidoServiceInterface->inserir_historico($pedido, 'O status foi alterado via integração.', 1);

                    $mensagem = "A situação do registro nº ".$registro_fiduciario->registro_fiduciario_pedido->pedido->protocolo_pedido." foi alterada por meio de integração com a Central de Registros.";

                    $mensagemBradesco = "A situação do registro nº ".$registro_fiduciario->registro_fiduciario_pedido->pedido->protocolo_pedido." foi alterada por meio de integração com a Central de Registros.";

                    $this->enviar_email_observador_registro($registro_fiduciario, $mensagem, $mensagemBradesco);
                    $this->enviar_email_operadores_registro($registro_fiduciario, $mensagem, $mensagemBradesco);
                }
            }

            // Atualizar data de alteração
            $args_registro_fiduciario = new stdClass();
            $args_registro_fiduciario->dt_alteracao = Carbon::now();
            if ($dt_prenotacao) {
                $args_registro_fiduciario->dt_prenotacao = $dt_prenotacao;
            }
            if ($dt_vencto_prenotacao) {
                $args_registro_fiduciario->dt_vencto_prenotacao = $dt_vencto_prenotacao;
            }

            $this->RegistroFiduciarioServiceInterface->alterar($registro_fiduciario, $args_registro_fiduciario);

            if (in_array($id_situacao_pedido_grupo_produto, [config('constants.SITUACAO.11.ID_REGISTRADO')])) {
                // Enviar Notificação
                if(!empty($pedido->url_notificacao)) {
                    RegistroSituacaoNotificacao::dispatch($registro_fiduciario);
                }
            }

            //Enviar e-mail de averbação quando está finalizado
            if (in_array($id_situacao_pedido_grupo_produto, [config('constants.SITUACAO.11.ID_REGISTRADO')])) {
                if ($registro_fiduciario->registro_fiduciario_parte->count()>0) {
                    $registro_fiduciario_partes = $registro_fiduciario->registro_fiduciario_parte()
                        ->whereIn('id_tipo_parte_registro_fiduciario', [
                            config('constants.REGISTRO_FIDUCIARIO.PARTES.ID_TIPO_PARTE_ADQUIRENTE'),
                            config('constants.REGISTRO_FIDUCIARIO.PARTES.ID_TIPO_PARTE_TRANSMITENTE'),
                            config('constants.REGISTRO_FIDUCIARIO.PARTES.ID_TIPO_PARTE_DEVEDOR'),
                            config('constants.REGISTRO_FIDUCIARIO.PARTES.ID_TIPO_PARTE_PARTE')
                        ])
                        ->get();

                    foreach ($registro_fiduciario_partes as $registro_fiduciario_parte) {
                        if (count($registro_fiduciario_parte->registro_fiduciario_procurador)>0) {
                            foreach ($registro_fiduciario_parte->registro_fiduciario_procurador as $procurador) {
                                $args_email = [
                                    'no_email_contato' => $procurador->no_email_contato,
                                    'no_contato' => $procurador->no_parte,
                                    'senha' => Crypt::decryptString($procurador->pedido_usuario->pedido_usuario_senha->senha_crypt),
                                    'token' => $procurador->pedido_usuario->token,
                                ];
                                $this->enviar_email_registro_averbado($registro_fiduciario, $args_email);
                            }
                        } else {
                            $args_email = [
                                'no_email_contato' => $registro_fiduciario_parte->no_email_contato,
                                'no_contato' => $registro_fiduciario_parte->no_parte,
                                'senha' => Crypt::decryptString($registro_fiduciario_parte->pedido_usuario->pedido_usuario_senha->senha_crypt),
                                'token' => $registro_fiduciario_parte->pedido_usuario->token,
                            ];
                            $this->enviar_email_registro_averbado($registro_fiduciario, $args_email);
                        }
                    }
                }
            }
// AVERBADO ENVIO DE EMAIL
//            $mensagem = 'O registro foi averbado / finalizado, para visualizar o resultado acesse a aba "Arquivos" nos detalhes do Registro.';
//            $mensagemBradesco = 'O registro eletrônico foi concluído!<br>A matricula registrada/averbada foi disponibilizada no sistema do Banco Bradesco para liberação dos recursos ao vendedor.';
//
//            $this->enviar_email_observador_registro($registro_fiduciario, $mensagem, $mensagemBradesco);
//            $this->enviar_email_operadores_registro($registro_fiduciario, $mensagem, $mensagemBradesco);

            return 'Pedido atualizado com sucesso.';
        } else {
            throw new RegdocException('O pedido não foi encontrado.');
        }
    }

    private function download_anexos($campo, $id_arisp_pedido)
    {
        $consulta = ARISP::consulta_pedido($id_arisp_pedido);

        if (isset($consulta['pedido'][$campo])) {
            $anexos = $consulta['pedido'][$campo];
            if (gettype($anexos) == 'array') {
                $array_anexos = [];

                if (count($anexos)>0) {
                    foreach($anexos as $key => $anexo) {
                        $local_anexo = Helper::download_arquivo_url($anexo->URLArquivo);
                        $codigo_anexo = Helper::var_from_url('c', $anexo->URLArquivo);

                        $nome_anexo = Helper::get_filename_url($anexo->URLArquivo);
                        $nome_anexo = mb_strtolower($nome_anexo, 'UTF-8');
                        $nome_anexo = str_replace(".pdf.p7s", ".pdf" , $nome_anexo);
                        $extensao_anexo = Helper::extensao_arquivo($nome_anexo);

                        $array_anexos[] = [
                            'nome_anexo' => Str::random(10).'.'.$extensao_anexo,
                            'extensao_anexo' => $extensao_anexo,
                            'descricao' => $nome_anexo,
                            'dt_anexo' => $anexo->DataAnexo,
                            'url_anexo' => $anexo->URLArquivo,
                            'codigo_anexo' => $codigo_anexo,
                            'local_arquivo' => $local_anexo,
                            'campo' => $campo
                        ];
                    }
                }

                return $array_anexos;
            } else if (gettype($anexos) == 'object') {
                if (isset($anexos->URLArquivo)) {
                    $local_anexo = Helper::download_arquivo_url($anexos->URLArquivo);
                    $codigo_anexo = Helper::var_from_url('c', $anexos->URLArquivo);

                    $nome_anexo = Helper::get_filename_url($anexos->URLArquivo);
                    $nome_anexo = mb_strtolower($nome_anexo, 'UTF-8');
                    $nome_anexo = str_replace(".pdf.p7s", ".pdf" , $nome_anexo);
                    $extensao_anexo = Helper::extensao_arquivo($nome_anexo);

                    return [
                        [
                            'nome_anexo' => Str::random(10).'.'.$extensao_anexo,
                            'extensao_anexo' => $extensao_anexo,
                            'descricao' => $nome_anexo,
                            'dt_anexo' => $anexos->DataAnexo,
                            'url_anexo' => $anexos->URLArquivo,
                            'codigo_anexo' => $codigo_anexo,
                            'local_arquivo' => $local_anexo,
                            'campo' => $campo
                        ]
                    ];
                }
            }
        } else {
            throw new RegdocException('Arquivos não encontrados');
        }

        return [];
    }

    private function download_boletos($id_arisp_pedido)
    {
        $consulta = ARISP::consulta_pedido($id_arisp_pedido);

        if (isset($consulta['pedido']['boletos'])) {
            $boletos = $consulta['pedido']['boletos'];
            if (gettype($boletos) == 'array') {
                $array_boletos = [];

                if (count($boletos)) {
                    foreach($boletos as $key => $boleto) {
                        if (strlen($boleto->URLBoleto)>0) {
                            $array_boletos[] = [
                                'dt_boleto' => $boleto->DataBoleto,
                                'url_boleto' => $boleto->URLBoleto,
                            ];
                        }
                    }
                }

                return $array_boletos;
            } else if (gettype($boletos) == 'object') {
                if (isset($boletos->URLBoleto)) {
                    if (strlen($boletos->URLBoleto)>0) {
                        return [
                            [
                                'dt_boleto' => $boletos->DataBoleto,
                                'url_boleto' => $boletos->URLBoleto,
                            ]
                        ];
                    }
                }
            }
        } else {
            throw new RegdocException('Boletos não encontrados');
        }

        return [];
    }

    /**
     * @param $titulo
     * @param $request
     * @param false $erro
     * @param null $mensagem
     * @return mixed
     * @throws Exception
     */
    private function salvar_log($titulo, $request, $erro = false, $mensagem = null)
    {
        try {
            $log = '[ARISP: '.$titulo.']'.PHP_EOL.PHP_EOL;
            $log .= 'Dados do request: '.json_encode($request->all()).PHP_EOL;

            if ($erro) {
                $log .= 'Situação: Exception'.PHP_EOL;
                $log .= 'Mensagem: '.$mensagem.PHP_EOL;
            } else {
                $log .= 'Situação: O request foi realizado com sucesso.'.PHP_EOL;
            }

            $log .= PHP_EOL;
            $log .= '------------------------------------------------------------------------------------';
            $log .= PHP_EOL;

            return Storage::append('logs/arisp_'. Carbon::now()->format('d-m-y').'.log', $log);
        } catch (Exception $e) {
            throw new Exception('Houve um erro ao salvar o log da arisp!');
        }
    }
}
