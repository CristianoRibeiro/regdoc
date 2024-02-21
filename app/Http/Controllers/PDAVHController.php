<?php

namespace App\Http\Controllers;

use App\Events\SkipStatus;

use App\Helpers\LogDB;
use App\Helpers\PDAVH;

use App\Http\Controllers\Controller;

use App\Exceptions\RegdocException;

use App\Domain\RegistroFiduciario\Contracts\RegistroFiduciarioServiceInterface;
use App\Domain\RegistroFiduciarioAssinatura\Contracts\RegistroFiduciarioAssinaturaServiceInterface;
use App\Domain\RegistroFiduciarioAssinatura\Contracts\RegistroFiduciarioParteAssinaturaServiceInterface;
use App\Domain\RegistroFiduciarioAssinatura\Contracts\RegistroFiduciarioParteAssinaturaArquivoServiceInterface;

use App\Domain\Arquivo\Contracts\ArquivoServiceInterface;
use App\Domain\Arquivo\Contracts\ArquivoAssinaturaServiceInterface;
use App\Domain\Usuario\Contracts\UsuarioCertificadoServiceInterface;
use App\Domain\Pedido\Contracts\PedidoServiceInterface;
use App\Domain\Pedido\Contracts\HistoricoPedidoServiceInterface;
use App\Domain\Configuracao\Contracts\ConfiguracaoPessoaServiceInterface;

use App\Domain\Documento\Documento\Contracts\DocumentoServiceInterface;
use App\Domain\Documento\Assinatura\Contracts\DocumentoAssinaturaServiceInterface;
use App\Domain\Documento\Assinatura\Contracts\DocumentoParteAssinaturaServiceInterface;
use App\Domain\Documento\Assinatura\Contracts\DocumentoParteAssinaturaArquivoServiceInterface;

use App\Traits\EmailDocumentos;
use App\Traits\EmailRegistro;

use Carbon\Carbon;

use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

use Exception;
use stdClass;

class PDAVHController extends Controller
{
    use EmailDocumentos;
    use EmailRegistro;

    /**
     * @var RegistroFiduciarioServiceInterface
     * @var RegistroFiduciarioAssinaturaServiceInterface
     * @var RegistroFiduciarioParteAssinaturaServiceInterface
     * @var RegistroFiduciarioParteAssinaturaArquivoServiceInterface
     *
     * @var ArquivoServiceInterface
     * @var ArquivoAssinaturaServiceInterface
     * @var UsuarioCertificadoServiceInterface
     * @var PedidoServiceInterface
     * @var HistoricoPedidoServiceInterface
     * @var ConfiguracaoPessoaServiceInterface
     *
     * @var DocumentoServiceInterface
     * @var DocumentoAssinaturaServiceInterface
     * @var DocumentoParteAssinaturaServiceInterface
     * @var DocumentoParteAssinaturaArquivoServiceInterface
     */
    protected $RegistroFiduciarioServiceInterface;
    protected $RegistroFiduciarioAssinaturaServiceInterface;
    protected $RegistroFiduciarioParteAssinaturaServiceInterface;
    protected $RegistroFiduciarioParteAssinaturaArquivoServiceInterface;

    protected $ArquivoServiceInterface;
    protected $ArquivoAssinaturaServiceInterface;
    protected $UsuarioCertificadoServiceInterface;
    protected $PedidoServiceInterface;
    protected $HistoricoPedidoServiceInterface;
    protected $ConfiguracaoPessoaServiceInterface;

    protected $DocumentoServiceInterface;
    protected $DocumentoAssinaturaServiceInterface;
    protected $DocumentoParteAssinaturaServiceInterface;
    protected $DocumentoParteAssinaturaArquivoServiceInterface;

    /**
     * PDAVHController constructor.
     * @param RegistroFiduciarioServiceInterface $RegistroFiduciarioServiceInterface
     * @param RegistroFiduciarioAssinaturaServiceInterface $RegistroFiduciarioAssinaturaServiceInterface
     * @param RegistroFiduciarioParteAssinaturaServiceInterface $RegistroFiduciarioParteAssinaturaServiceInterface
     * @param RegistroFiduciarioParteAssinaturaArquivoServiceInterface $RegistroFiduciarioParteAssinaturaArquivoServiceInterface
     *
     * @param ArquivoServiceInterface $ArquivoServiceInterface
     * @param ArquivoAssinaturaServiceInterface $ArquivoAssinaturaServiceInterface
     * @param UsuarioCertificadoServiceInterface $UsuarioCertificadoServiceInterface
     * @param PedidoServiceInterface $PedidoServiceInterface
     * @param HistoricoPedidoServiceInterface $HistoricoPedidoServiceInterface
     * @param ConfiguracaoPessoaServiceInterface $ConfiguracaoPessoaServiceInterface
     *
     * @param DocumentoServiceInterface $DocumentoServiceInterface
     * @param DocumentoAssinaturaServiceInterface $DocumentoAssinaturaServiceInterface
     * @param DocumentoParteAssinaturaServiceInterface $DocumentoParteAssinaturaServiceInterface
     * @param DocumentoParteAssinaturaArquivoServiceInterface $DocumentoParteAssinaturaArquivoServiceInterface
     */
    public function __construct(RegistroFiduciarioServiceInterface $RegistroFiduciarioServiceInterface,
                                RegistroFiduciarioAssinaturaServiceInterface $RegistroFiduciarioAssinaturaServiceInterface,
                                RegistroFiduciarioParteAssinaturaServiceInterface $RegistroFiduciarioParteAssinaturaServiceInterface,
                                RegistroFiduciarioParteAssinaturaArquivoServiceInterface $RegistroFiduciarioParteAssinaturaArquivoServiceInterface,

                                ArquivoServiceInterface $ArquivoServiceInterface,
                                ArquivoAssinaturaServiceInterface $ArquivoAssinaturaServiceInterface,
                                UsuarioCertificadoServiceInterface $UsuarioCertificadoServiceInterface,
                                PedidoServiceInterface $PedidoServiceInterface,
                                HistoricoPedidoServiceInterface $HistoricoPedidoServiceInterface,
                                ConfiguracaoPessoaServiceInterface $ConfiguracaoPessoaServiceInterface,

                                DocumentoServiceInterface $DocumentoServiceInterface,
                                DocumentoAssinaturaServiceInterface $DocumentoAssinaturaServiceInterface,
                                DocumentoParteAssinaturaServiceInterface $DocumentoParteAssinaturaServiceInterface,
                                DocumentoParteAssinaturaArquivoServiceInterface $DocumentoParteAssinaturaArquivoServiceInterface)
    {
        $this->RegistroFiduciarioServiceInterface = $RegistroFiduciarioServiceInterface;
        $this->RegistroFiduciarioAssinaturaServiceInterface = $RegistroFiduciarioAssinaturaServiceInterface;
        $this->RegistroFiduciarioParteAssinaturaServiceInterface = $RegistroFiduciarioParteAssinaturaServiceInterface;
        $this->RegistroFiduciarioParteAssinaturaArquivoServiceInterface = $RegistroFiduciarioParteAssinaturaArquivoServiceInterface;

        $this->ArquivoServiceInterface = $ArquivoServiceInterface;
        $this->ArquivoAssinaturaServiceInterface = $ArquivoAssinaturaServiceInterface;
        $this->UsuarioCertificadoServiceInterface = $UsuarioCertificadoServiceInterface;
        $this->PedidoServiceInterface = $PedidoServiceInterface;
        $this->HistoricoPedidoServiceInterface = $HistoricoPedidoServiceInterface;
        $this->ConfiguracaoPessoaServiceInterface = $ConfiguracaoPessoaServiceInterface;

        $this->DocumentoServiceInterface = $DocumentoServiceInterface;
        $this->DocumentoAssinaturaServiceInterface = $DocumentoAssinaturaServiceInterface;
        $this->DocumentoParteAssinaturaServiceInterface = $DocumentoParteAssinaturaServiceInterface;
        $this->DocumentoParteAssinaturaArquivoServiceInterface = $DocumentoParteAssinaturaArquivoServiceInterface;
    }

    public function notificacaoOutrosArquivos(Request $request)
    {
        $data = json_decode($request->getContent());
        
        if ($data->type === 2)
        {
            $signature_process = PDAVH::show_signature_process($data->uuid);
            
            foreach($signature_process->signers as $signer)
            {
                foreach ($signer->files as $file)
                {
                    $file_signature = PDAVH::get_file_signature($data->uuid, $file->uuid, $signer->uuid);
                    $arquivo_grupo_produto = $this->ArquivoServiceInterface->buscar($file->code);
                    $nome_arquivo_assinado = Str::random(16).'.'.$arquivo_grupo_produto->no_extensao;

                    $local_arquivo_assinado = "public{$arquivo_grupo_produto->no_local_arquivo}/{$nome_arquivo_assinado}";
                    Storage::put($local_arquivo_assinado, base64_decode($file_signature->signature->signed_content));

                    if (!Storage::exists($local_arquivo_assinado))
                        throw new Exception('O arquivo não foi salvo corretamente.');

                    $args_alterar_arquivo = new stdClass();
                    $args_alterar_arquivo->no_arquivo = $nome_arquivo_assinado;
                    $args_alterar_arquivo->in_ass_digital = 'S';
                    $args_alterar_arquivo->dt_ass_digital = Carbon::parse($file_signature->signature->signed_at);
                    $args_alterar_arquivo->no_hash = hash('md5', $file_signature->signature->signed_content);
                    $args_alterar_arquivo->nu_tamanho_kb = $file_signature->signature->signed_size;

                    $this->ArquivoServiceInterface->alterar($arquivo_grupo_produto, $args_alterar_arquivo);
                }
            }
        }

        return "A assinatura foi atualizada com sucesso.";
    }

    public function notificacaoLote(Request $request)
    {
        $data = json_decode($request->getContent());
        
        if ($data->type === 1)
        {
            try{

                $signature_process = PDAVH::show_signature_process($data->uuid);

                if($signature_process){
                    
                    $array_ids_parte_assinatura = explode(",", $data->signer->code);

                    foreach ($array_ids_parte_assinatura as $ids_parte_assinatura) {
                        $registro_fiduciario_parte_assinatura = $this->RegistroFiduciarioParteAssinaturaServiceInterface->buscar($ids_parte_assinatura);
                        $registro_fiduciario = $registro_fiduciario_parte_assinatura->registro_fiduciario_parte->registro_fiduciario;
                        $pedido = $registro_fiduciario->registro_fiduciario_pedido->pedido;
                        $registro_fiduciario_assinatura = $registro_fiduciario_parte_assinatura->registro_fiduciario_assinatura;

                        foreach ($signature_process->signers as $signer) {

                            foreach ($signer->files as $file) {

                                $inserir_historico = false;
                                
                                if($registro_fiduciario_parte_assinatura){
                                    if ($file->signed_at) {
                                        $registro_fiduciario_parte_assinatura_arquivo = $registro_fiduciario_parte_assinatura->registro_fiduciario_parte_assinatura_arquivo()
                                            ->where('id_arquivo_grupo_produto', $file->code)
                                            ->first();

                                        if (!$registro_fiduciario_parte_assinatura_arquivo)
                                            continue;
                                        
                                        if (!$registro_fiduciario_parte_assinatura_arquivo->id_arquivo_grupo_produto_assinatura) {
                                            $inserir_historico = true;

                                            $arquivo_grupo_produto = $registro_fiduciario_parte_assinatura_arquivo->arquivo_grupo_produto;
        
                                            $file_signature = PDAVH::get_file_signature($signature_process->signature_process->uuid, $file->uuid, $signer->uuid);

                                            $signed_at = Carbon::parse($file_signature->signature->signed_at);
                                            $signed_hash = hash('md5', $file_signature->signature->signed_content);

                                            $id_usuario_certificado = $this->usuario_certificado($file_signature->signature->signer->certificate ?? NULL);

                                            $nome_arquivo_assinado = Str::random(16).'.'.$arquivo_grupo_produto->no_extensao;

                                            $args_novo_arquivo_assinatura = new stdClass();
                                            $args_novo_arquivo_assinatura->id_arquivo_grupo_produto = $arquivo_grupo_produto->id_arquivo_grupo_produto;
                                            $args_novo_arquivo_assinatura->no_arquivo = $nome_arquivo_assinado;
                                            $args_novo_arquivo_assinatura->no_local_arquivo = $arquivo_grupo_produto->no_local_arquivo;
                                            $args_novo_arquivo_assinatura->no_extensao = $arquivo_grupo_produto->no_extensao;
                                            $args_novo_arquivo_assinatura->in_ass_digital = 'S';
                                            $args_novo_arquivo_assinatura->dt_ass_digital = $signed_at;
                                            $args_novo_arquivo_assinatura->nu_tamanho_kb = $file_signature->signature->signed_size;
                                            $args_novo_arquivo_assinatura->no_hash = $signed_hash;
                                            $args_novo_arquivo_assinatura->id_usuario_certificado = $id_usuario_certificado ?? NULL;
                                            $args_novo_arquivo_assinatura->no_mime_type = $arquivo_grupo_produto->no_mime_type;
                                            $args_novo_arquivo_assinatura->id_usuario_cad = 1;

                                            $novo_arquivo_assinatura = $this->ArquivoAssinaturaServiceInterface->inserir($args_novo_arquivo_assinatura);

                                            $args_alterar_arquivo_assinatura = new stdClass();
                                            $args_alterar_arquivo_assinatura->id_arquivo_grupo_produto_assinatura = $novo_arquivo_assinatura->id_arquivo_grupo_produto_assinatura;

                                            $this->RegistroFiduciarioParteAssinaturaArquivoServiceInterface->alterar($registro_fiduciario_parte_assinatura_arquivo, $args_alterar_arquivo_assinatura);

                                            $local_arquivo_assinado = 'public' . $arquivo_grupo_produto->no_local_arquivo . '/' . $nome_arquivo_assinado;
                                            Storage::put($local_arquivo_assinado, base64_decode($file_signature->signature->signed_content));

                                            if (!Storage::exists($local_arquivo_assinado))
                                                throw new Exception('O arquivo não foi salvo corretamente.');

                                            $ultima_assinatura = $arquivo_grupo_produto->arquivo_grupo_produto_assinatura()
                                                ->orderBy('dt_ass_digital')
                                                ->first();

                                            $atualiza_arquivo = true;
                                            if ($ultima_assinatura) {
                                                if ($signed_at<$ultima_assinatura->dt_ass_digital) {
                                                    $atualiza_arquivo = false;
                                                }
                                            }

                                            if ($atualiza_arquivo) {
                                                $args_alterar_arquivo = new stdClass();
                                                $args_alterar_arquivo->no_arquivo = $nome_arquivo_assinado;
                                                $args_alterar_arquivo->nu_tamanho_kb = $file_signature->signature->signed_size;
                                                $args_alterar_arquivo->no_hash = $signed_hash;

                                                $this->ArquivoServiceInterface->alterar($arquivo_grupo_produto, $args_alterar_arquivo);
                                            }
                                        }    
                                    }

                                    if($inserir_historico) {
                                        // Insere o histórico do pedido
                                        $this->HistoricoPedidoServiceInterface->inserir_historico($pedido, 'A assinatura em lote do '.$registro_fiduciario_assinatura->registro_fiduciario_assinatura_tipo->no_tipo.' da parte '.$registro_fiduciario_parte_assinatura->registro_fiduciario_parte->no_parte.' foi salva com sucesso.', 1);
                    
                                        LogDB::insere(
                                            1,
                                            6,
                                            'A assinatura da parte foi salva com sucesso.',
                                            'Registro - Assinaturas em lote',
                                            'N',
                                            request()->ip()
                                        );
                    
                                        // Atualizar data de alteração
                                        $args_registro_fiduciario = new stdClass();
                                        $args_registro_fiduciario->dt_alteracao = Carbon::now();
                    
                                        $this->RegistroFiduciarioServiceInterface->alterar($registro_fiduciario, $args_registro_fiduciario);
                    
                                        if ($registro_fiduciario_assinatura->in_ordem_assinatura=='S') {
                                            $outras_assinaturas_mesma_ordem = $registro_fiduciario_assinatura->registro_fiduciario_parte_assinatura()
                                                ->where('id_registro_fiduciario_parte_assinatura', '<>', $registro_fiduciario_parte_assinatura->id_registro_fiduciario_parte_assinatura)
                                                ->where('nu_ordem_assinatura', $registro_fiduciario_parte_assinatura->nu_ordem_assinatura)
                                                ->get();
                    
                                            $altera_ordem = true;
                                            foreach ($outras_assinaturas_mesma_ordem as $registro_fiduciario_parte_assinatura) {
                                                if ($registro_fiduciario_parte_assinatura->arquivos_nao_assinados()->count()>0) {
                                                    $altera_ordem = false;
                                                }
                                            }
                    
                                            if ($altera_ordem) {
                                                $args_registro_fiduciario_assinatura = new stdClass();
                                                $args_registro_fiduciario_assinatura->nu_ordem_assinatura_atual = $registro_fiduciario_assinatura->nu_ordem_assinatura_atual+1;
                                                $this->RegistroFiduciarioAssinaturaServiceInterface->alterar($registro_fiduciario_assinatura, $args_registro_fiduciario_assinatura);
                    
                                                // Envia o e-mail das próximas partes
                                                foreach ($registro_fiduciario->registro_fiduciario_parte as $registro_fiduciario_parte) {
                                                    if (count($registro_fiduciario_parte->registro_fiduciario_procurador)>0) {
                                                        foreach ($registro_fiduciario_parte->registro_fiduciario_procurador as $registro_fiduciario_procurador) {
                                                            if(count($registro_fiduciario_procurador->registro_fiduciario_parte_assinatura_na_ordem)>0) {
                                                                $verificacao_ordem = $registro_fiduciario_procurador->registro_fiduciario_parte_assinatura_na_ordem()
                                                                    ->where('registro_fiduciario_assinatura.id_registro_fiduciario_assinatura', $registro_fiduciario_assinatura->id_registro_fiduciario_assinatura)
                                                                    ->count();
                                                                if($verificacao_ordem>0) {
                                                                    $args_email = [
                                                                        'no_email_contato' => $registro_fiduciario_procurador->no_email_contato,
                                                                        'no_contato' => $registro_fiduciario_procurador->no_procurador,
                                                                        'senha' => Crypt::decryptString($registro_fiduciario_procurador->pedido_usuario->pedido_usuario_senha->senha_crypt),
                                                                        'token' => $registro_fiduciario_procurador->pedido_usuario->token,
                                                                    ];
                                                                    $this->enviar_email_iniciar_documentacao($registro_fiduciario, $args_email);
                                                                }
                                                            }
                                                        }
                                                    } else {
                                                        if(count($registro_fiduciario_parte->registro_fiduciario_parte_assinatura_na_ordem)>0) {
                                                            $verificacao_ordem = $registro_fiduciario_parte->registro_fiduciario_parte_assinatura_na_ordem()
                                                                ->where('registro_fiduciario_assinatura.id_registro_fiduciario_assinatura', $registro_fiduciario_assinatura->id_registro_fiduciario_assinatura)
                                                                ->count();
                                                            if($verificacao_ordem>0) {
                                                                $args_email = [
                                                                    'no_email_contato' => $registro_fiduciario_parte->no_email_contato,
                                                                    'no_contato' => $registro_fiduciario_parte->no_parte,
                                                                    'senha' => Crypt::decryptString($registro_fiduciario_parte->pedido_usuario->pedido_usuario_senha->senha_crypt),
                                                                    'token' => $registro_fiduciario_parte->pedido_usuario->token,
                                                                ];
                                                                $this->enviar_email_iniciar_documentacao($registro_fiduciario, $args_email);
                                                            }
                                                        }
                                                    }
                                                }
                                            }
                                        }
                                    }
                    
                                }
                                SkipStatus::dispatch($registro_fiduciario);
                            }
                        }
                    }
                }
                
            } catch (Exception $e) {
                LogDB::insere(
                    1,
                    4,
                    $e->getMessage(),
                    'Notificação assinatura em lote',
                    'N',
                    null,
                    $e->getMessage() . ' na linha ' . $e->getLine() . ' do arquivo ' . $e->getFile()
                );
            }
        }
    }

    /**
     * @param Request $request
     * @return string|void
     */
    public function notificacao(Request $request)
    {
        DB::beginTransaction();

        try {
            if ($request->type) {
                $pedido = $this->PedidoServiceInterface->buscar($request->code);

                if (!$pedido)
                    throw new RegdocException('Pedido não encontrado.');

                switch ($request->type) {
                    case '1':
                        switch ($pedido->id_produto) {
                            case config('constants.REGISTRO_FIDUCIARIO.ID_PRODUTO'):
                            case config('constants.REGISTRO_CONTRATO.ID_PRODUTO'):
                                $retorno = $this->notificacao_signatario_registro($request);
                                break;
                            case config('constants.DOCUMENTO.PRODUTO.ID_PRODUTO'):
                                $retorno = $this->notificacao_signatario_documento($request);
                                break;
                        }
                        break;
                    case '2':
                        switch ($pedido->id_produto) {
                            case config('constants.REGISTRO_FIDUCIARIO.ID_PRODUTO'):
                            case config('constants.REGISTRO_CONTRATO.ID_PRODUTO'):
                                $retorno = $this->notificacao_processo_registro($request);
                                break;
                            case config('constants.DOCUMENTO.PRODUTO.ID_PRODUTO'):
                                $retorno = $this->notificacao_processo_documento($request);
                                break;
                        }
                        break;
                    default:
                        throw new RegdocException('O tipo de notificação desconhecido.');
                        break;
                }
            } else {
                throw new RegdocException('O tipo de notificação não foi informado.');
            }

            DB::commit();

            return $retorno ?? NULL;
        } catch(Exception $e) {
            DB::rollback();

            LogDB::insere(
                1,
                4,
                $e->getMessage(),
                'Notificação assinatura',
                'N',
                null,
                $e->getMessage() . ' na linha ' . $e->getLine() . ' do arquivo ' . $e->getFile() . 'Request: ' . $request->getContent()
            );

            return 'Erro interno, por favor, tente novamente mais tarde.' . (config('app.env')!='production'? ' Descrição: '.$e->getMessage().' - Linha: '.$e->getLine().' - Arquivo: '.$e->getFile() : '');
        }
    }

    /**
     * @param $request
     * @throws Exception
     */
    private function notificacao_signatario_registro($request)
    {
        if ($request->signer['code']) {
            $signature_process = PDAVH::show_signature_process($request->uuid);

            $registro_fiduciario_assinatura = $this->RegistroFiduciarioAssinaturaServiceInterface->buscar_pdavh_uuid($request->uuid);

            if (!$registro_fiduciario_assinatura)
                return false;

            $registro_fiduciario = $registro_fiduciario_assinatura->registro_fiduciario;
            $pedido = $registro_fiduciario->registro_fiduciario_pedido->pedido;

            foreach ($signature_process->signers as $signer) {
                $registro_fiduciario_parte_assinatura = $this->RegistroFiduciarioParteAssinaturaServiceInterface->buscar($signer->code);

                $inserir_historico = false;

                foreach ($signer->files as $file) {
                    if ($file->signed_at) {
                        $registro_fiduciario_parte_assinatura_arquivo = $registro_fiduciario_parte_assinatura->registro_fiduciario_parte_assinatura_arquivo()
                            ->where('id_arquivo_grupo_produto', $file->code)
                            ->first();

                        if (!$registro_fiduciario_parte_assinatura_arquivo)
                            throw new Exception('Erro ao encontrar o vínculo do arquivo com a assinatura.');

                        if (!$registro_fiduciario_parte_assinatura_arquivo->id_arquivo_grupo_produto_assinatura) {
                            $inserir_historico = true;

                            $arquivo_grupo_produto = $registro_fiduciario_parte_assinatura_arquivo->arquivo_grupo_produto;

                            $file_signature = PDAVH::get_file_signature($signature_process->signature_process->uuid, $file->uuid, $signer->uuid);

                            $signed_at = Carbon::parse($file_signature->signature->signed_at);
                            $signed_hash = hash('md5', $file_signature->signature->signed_content);

                            $id_usuario_certificado = $this->usuario_certificado($file_signature->signature->signer->certificate ?? NULL);

                            $nome_arquivo_assinado = Str::random(16).'.'.$arquivo_grupo_produto->no_extensao;

                            $args_novo_arquivo_assinatura = new stdClass();
                            $args_novo_arquivo_assinatura->id_arquivo_grupo_produto = $arquivo_grupo_produto->id_arquivo_grupo_produto;
                            $args_novo_arquivo_assinatura->no_arquivo = $nome_arquivo_assinado;
                            $args_novo_arquivo_assinatura->no_local_arquivo = $arquivo_grupo_produto->no_local_arquivo;
                            $args_novo_arquivo_assinatura->no_extensao = $arquivo_grupo_produto->no_extensao;
                            $args_novo_arquivo_assinatura->in_ass_digital = 'S';
                            $args_novo_arquivo_assinatura->dt_ass_digital = $signed_at;
                            $args_novo_arquivo_assinatura->nu_tamanho_kb = $file_signature->signature->signed_size;
                            $args_novo_arquivo_assinatura->no_hash = $signed_hash;
                            $args_novo_arquivo_assinatura->id_usuario_certificado = $id_usuario_certificado ?? NULL;
                            $args_novo_arquivo_assinatura->no_mime_type = $arquivo_grupo_produto->no_mime_type;
                            $args_novo_arquivo_assinatura->id_usuario_cad = 1;

                            $novo_arquivo_assinatura = $this->ArquivoAssinaturaServiceInterface->inserir($args_novo_arquivo_assinatura);

                            $args_alterar_arquivo_assinatura = new stdClass();
                            $args_alterar_arquivo_assinatura->id_arquivo_grupo_produto_assinatura = $novo_arquivo_assinatura->id_arquivo_grupo_produto_assinatura;

                            $this->RegistroFiduciarioParteAssinaturaArquivoServiceInterface->alterar($registro_fiduciario_parte_assinatura_arquivo, $args_alterar_arquivo_assinatura);

                            $local_arquivo_assinado = 'public' . $arquivo_grupo_produto->no_local_arquivo . '/' . $nome_arquivo_assinado;
                            Storage::put($local_arquivo_assinado, base64_decode($file_signature->signature->signed_content));

                            if (!Storage::exists($local_arquivo_assinado))
                                throw new Exception('O arquivo não foi salvo corretamente.');

                            $ultima_assinatura = $arquivo_grupo_produto->arquivo_grupo_produto_assinatura()
                                ->orderBy('dt_ass_digital')
                                ->first();

                            $atualiza_arquivo = true;
                            if ($ultima_assinatura) {
                                if ($signed_at<$ultima_assinatura->dt_ass_digital) {
                                    $atualiza_arquivo = false;
                                }
                            }

                            if ($atualiza_arquivo) {
                                $args_alterar_arquivo = new stdClass();
                                $args_alterar_arquivo->no_arquivo = $nome_arquivo_assinado;
                                $args_alterar_arquivo->nu_tamanho_kb = $file_signature->signature->signed_size;
                                $args_alterar_arquivo->no_hash = $signed_hash;

                                $this->ArquivoServiceInterface->alterar($arquivo_grupo_produto, $args_alterar_arquivo);
                            }
                        }
                    }
                }

                if($inserir_historico) {
                    // Insere o histórico do pedido
                    $this->HistoricoPedidoServiceInterface->inserir_historico($pedido, 'A assinatura do '.$registro_fiduciario_assinatura->registro_fiduciario_assinatura_tipo->no_tipo.' da parte '.$registro_fiduciario_parte_assinatura->registro_fiduciario_parte->no_parte.' foi salva com sucesso.', 1);

                    LogDB::insere(
                        1,
                        6,
                        'A assinatura da parte foi salva com sucesso.',
                        'Registro - Assinaturas',
                        'N',
                        request()->ip()
                    );

                    // Atualizar data de alteração
                    $args_registro_fiduciario = new stdClass();
                    $args_registro_fiduciario->dt_alteracao = Carbon::now();

                    $this->RegistroFiduciarioServiceInterface->alterar($registro_fiduciario, $args_registro_fiduciario);

                    if ($registro_fiduciario_assinatura->in_ordem_assinatura=='S') {
                        $outras_assinaturas_mesma_ordem = $registro_fiduciario_assinatura->registro_fiduciario_parte_assinatura()
                            ->where('id_registro_fiduciario_parte_assinatura', '<>', $registro_fiduciario_parte_assinatura->id_registro_fiduciario_parte_assinatura)
                            ->where('nu_ordem_assinatura', $registro_fiduciario_parte_assinatura->nu_ordem_assinatura)
                            ->get();

                        $altera_ordem = true;
                        foreach ($outras_assinaturas_mesma_ordem as $registro_fiduciario_parte_assinatura) {
                            if ($registro_fiduciario_parte_assinatura->arquivos_nao_assinados()->count()>0) {
                                $altera_ordem = false;
                            }
                        }

                        if ($altera_ordem) {
                            $args_registro_fiduciario_assinatura = new stdClass();
                            $args_registro_fiduciario_assinatura->nu_ordem_assinatura_atual = $registro_fiduciario_assinatura->nu_ordem_assinatura_atual+1;
                            $this->RegistroFiduciarioAssinaturaServiceInterface->alterar($registro_fiduciario_assinatura, $args_registro_fiduciario_assinatura);

                            // Envia o e-mail das próximas partes
                            foreach ($registro_fiduciario->registro_fiduciario_parte as $registro_fiduciario_parte) {
                                if (count($registro_fiduciario_parte->registro_fiduciario_procurador)>0) {
                                    foreach ($registro_fiduciario_parte->registro_fiduciario_procurador as $registro_fiduciario_procurador) {
                                        if(count($registro_fiduciario_procurador->registro_fiduciario_parte_assinatura_na_ordem)>0) {
                                            $verificacao_ordem = $registro_fiduciario_procurador->registro_fiduciario_parte_assinatura_na_ordem()
                                                ->where('registro_fiduciario_assinatura.id_registro_fiduciario_assinatura', $registro_fiduciario_assinatura->id_registro_fiduciario_assinatura)
                                                ->count();
                                            if($verificacao_ordem>0) {
                                                $args_email = [
                                                    'no_email_contato' => $registro_fiduciario_procurador->no_email_contato,
                                                    'no_contato' => $registro_fiduciario_procurador->no_procurador,
                                                    'senha' => Crypt::decryptString($registro_fiduciario_procurador->pedido_usuario->pedido_usuario_senha->senha_crypt),
                                                    'token' => $registro_fiduciario_procurador->pedido_usuario->token,
                                                ];
                                                $this->enviar_email_iniciar_documentacao($registro_fiduciario, $args_email);
                                            }
                                        }
                                    }
                                } else {
                                    if(count($registro_fiduciario_parte->registro_fiduciario_parte_assinatura_na_ordem)>0) {
                                        $verificacao_ordem = $registro_fiduciario_parte->registro_fiduciario_parte_assinatura_na_ordem()
                                            ->where('registro_fiduciario_assinatura.id_registro_fiduciario_assinatura', $registro_fiduciario_assinatura->id_registro_fiduciario_assinatura)
                                            ->count();
                                        if($verificacao_ordem>0) {
                                            $args_email = [
                                                'no_email_contato' => $registro_fiduciario_parte->no_email_contato,
                                                'no_contato' => $registro_fiduciario_parte->no_parte,
                                                'senha' => Crypt::decryptString($registro_fiduciario_parte->pedido_usuario->pedido_usuario_senha->senha_crypt),
                                                'token' => $registro_fiduciario_parte->pedido_usuario->token,
                                            ];
                                            $this->enviar_email_iniciar_documentacao($registro_fiduciario, $args_email);
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
            SkipStatus::dispatch($registro_fiduciario);
            return 'A assinatura foi atualizada com sucesso.';
        }
    }

    private function notificacao_signatario_documento($request)
    {
        if ($request->signer['code']) {
            $signature_process = PDAVH::show_signature_process($request->uuid);

            $documento_assinatura = $this->DocumentoAssinaturaServiceInterface->buscar_pdavh_uuid($request->uuid);

            if (!$documento_assinatura)
                throw new Exception('O processo de assinatura não foi encontrado');

            $documento = $documento_assinatura->documento;
            $pedido = $documento->pedido;

            $nu_ordem_assinatura = 0;

            foreach ($signature_process->signers as $signer) {
                $documento_parte_assinatura = $this->DocumentoParteAssinaturaServiceInterface->buscar($signer->code);

                $inserir_historico = false;

                foreach ($signer->files as $file) {
                    if ($file->signed_at) {
                        $documento_parte_assinatura_arquivo = $documento_parte_assinatura->documento_parte_assinatura_arquivo()
                            ->where('id_arquivo_grupo_produto', $file->code)
                            ->first();

                        if (!$documento_parte_assinatura_arquivo)
                            throw new Exception('Erro ao encontrar o vínculo do arquivo com a assinatura.');

                        if (!$documento_parte_assinatura_arquivo->id_arquivo_grupo_produto_assinatura) {
                            $inserir_historico = true;

                            $arquivo_grupo_produto = $documento_parte_assinatura_arquivo->arquivo_grupo_produto;

                            $file_signature = PDAVH::get_file_signature($signature_process->signature_process->uuid, $file->uuid, $signer->uuid);

                            $signed_at = Carbon::parse($file_signature->signature->signed_at);
                            $signed_hash = hash('md5', $file_signature->signature->signed_content);

                            $id_usuario_certificado = $this->usuario_certificado($file_signature->signature->signer->certificate ?? NULL);

                            $nome_arquivo_assinado = Str::random(16).'.'.$arquivo_grupo_produto->no_extensao;

                            $args_novo_arquivo_assinatura = new stdClass();
                            $args_novo_arquivo_assinatura->id_arquivo_grupo_produto = $arquivo_grupo_produto->id_arquivo_grupo_produto;
                            $args_novo_arquivo_assinatura->no_arquivo = $nome_arquivo_assinado;
                            $args_novo_arquivo_assinatura->no_local_arquivo = $arquivo_grupo_produto->no_local_arquivo;
                            $args_novo_arquivo_assinatura->no_extensao = $arquivo_grupo_produto->no_extensao;
                            $args_novo_arquivo_assinatura->in_ass_digital = 'S';
                            $args_novo_arquivo_assinatura->dt_ass_digital = $signed_at;
                            $args_novo_arquivo_assinatura->nu_tamanho_kb = $file_signature->signature->signed_size;
                            $args_novo_arquivo_assinatura->no_hash = $signed_hash;
                            $args_novo_arquivo_assinatura->id_usuario_certificado = $id_usuario_certificado ?? NULL;
                            $args_novo_arquivo_assinatura->no_mime_type = $arquivo_grupo_produto->no_mime_type;
                            $args_novo_arquivo_assinatura->id_usuario_cad = 1;

                            $novo_arquivo_assinatura = $this->ArquivoAssinaturaServiceInterface->inserir($args_novo_arquivo_assinatura);

                            $args_alterar_arquivo_assinatura = new stdClass();
                            $args_alterar_arquivo_assinatura->id_arquivo_grupo_produto_assinatura = $novo_arquivo_assinatura->id_arquivo_grupo_produto_assinatura;

                            $this->DocumentoParteAssinaturaArquivoServiceInterface->alterar($documento_parte_assinatura_arquivo, $args_alterar_arquivo_assinatura);

                            $local_arquivo_assinado = 'public' . $arquivo_grupo_produto->no_local_arquivo . '/' . $nome_arquivo_assinado;
                            Storage::put($local_arquivo_assinado, base64_decode($file_signature->signature->signed_content));

                            if (!Storage::exists($local_arquivo_assinado))
                                throw new Exception('O arquivo não foi salvo corretamente.');

                            $ultima_assinatura = $arquivo_grupo_produto->arquivo_grupo_produto_assinatura()
                                ->orderBy('dt_ass_digital')
                                ->first();

                            $atualiza_arquivo = true;
                            if ($ultima_assinatura) {
                                if ($signed_at<$ultima_assinatura->dt_ass_digital) {
                                    $atualiza_arquivo = false;
                                }
                            }

                            if ($atualiza_arquivo) {
                                $args_alterar_arquivo = new stdClass();
                                $args_alterar_arquivo->no_arquivo = $nome_arquivo_assinado;
                                $args_alterar_arquivo->nu_tamanho_kb = $file_signature->signature->signed_size;
                                $args_alterar_arquivo->no_hash = $signed_hash;

                                $this->ArquivoServiceInterface->alterar($arquivo_grupo_produto, $args_alterar_arquivo);
                            }
                        }
                    }
                }

                if($inserir_historico) {
                    // Insere o histórico do pedido
                    $this->HistoricoPedidoServiceInterface->inserir_historico($pedido, 'A assinatura do '.$documento_assinatura->documento_assinatura_tipo->no_documento_assinatura_tipo.' da parte '.$documento_parte_assinatura->documento_parte->no_parte.' foi salva com sucesso.', 1);

                    LogDB::insere(
                        1,
                        6,
                        'A assinatura da parte foi salva com sucesso.',
                        'Documentos - Assinaturas',
                        'N',
                        request()->ip()
                    );

                    // Atualizar data de alteração
                    $args_documento = new stdClass();
                    $args_documento->dt_alteracao = Carbon::now();

                    $this->DocumentoServiceInterface->alterar($documento, $args_documento);

                    if ($documento_assinatura->in_ordem_assinatura=='S') {
                        $outras_assinaturas_mesma_ordem = $documento_assinatura->documento_parte_assinatura()
                            ->where('id_documento_parte_assinatura', '<>', $documento_parte_assinatura->id_documento_parte_assinatura)
                            ->where('nu_ordem_assinatura', $documento_parte_assinatura->nu_ordem_assinatura)
                            ->get();

                        $altera_ordem = true;
                        foreach ($outras_assinaturas_mesma_ordem as $documento_parte_assinatura) {
                            if ($documento_parte_assinatura->arquivos_nao_assinados()->count()>0) {
                                $altera_ordem = false;
                            }
                        }

                        if ($altera_ordem) {
                            $args_documento_assinatura = new stdClass();
                            $args_documento_assinatura->nu_ordem_assinatura_atual = $documento_assinatura->nu_ordem_assinatura_atual+1;
                            $this->DocumentoAssinaturaServiceInterface->alterar($documento_assinatura, $args_documento_assinatura);

                            $documento_partes = $documento->documento_parte()
                                ->whereIn('id_documento_parte_tipo', [
                                    config('constants.DOCUMENTO.PARTES.ID_CESSIONARIA'),
                                    config('constants.DOCUMENTO.PARTES.ID_CEDENTE'),
                                    config('constants.DOCUMENTO.PARTES.ID_ESCRITORIO_COBRANCA'),
                                    config('constants.DOCUMENTO.PARTES.ID_ESCRITORIO_ADVOCACIA'),
                                    config('constants.DOCUMENTO.PARTES.ID_TESTEMUNHA'),
                                    config('constants.DOCUMENTO.PARTES.ID_JURIDICO_INTERNO')
                                ])
                                ->get();

                            /* Enviar e-mails das partes:
                            *      - O e-mail só será enviado caso a parte tenha alguma
                            *        parte_assinatura vinculada;
                            *      - Será necessário popular os arrays de envio, para que o
                            *        envio seja feito só uma vez, evitando e-mails duplicados.
                            */
                            $partes_envia_email = [];
                            $procuradores_envia_email = [];
                            if (count($documento_partes)>0) {
                                foreach ($documento_partes as $documento_parte) {
                                    if (count($documento_parte->documento_procurador)>0 && $documento_parte->in_assinatura_parte=='N') {
                                        foreach ($documento_parte->documento_procurador as $procurador) {
                                            $verificacao_ordem = $procurador->documento_parte_assinatura_na_ordem()
                                                ->where('documento_assinatura.id_documento_assinatura', $documento_assinatura->id_documento_assinatura)
                                                ->count();
                                            if($verificacao_ordem>0) {
                                                $procuradores_envia_email[$procurador->id_documento_procurador] = $procurador;
                                            }
                                        }
                                    } else {
                                        $verificacao_ordem = $documento_parte->documento_parte_assinatura_na_ordem()
                                            ->where('documento_assinatura.id_documento_assinatura', $documento_assinatura->id_documento_assinatura)
                                            ->count();
                                        if($verificacao_ordem>0) {
                                            $partes_envia_email[$documento_parte->id_documento_parte] = $documento_parte;
                                        }
                                    }
                                }
                            }

                            // Enviar e-mails para as partes
                            foreach ($partes_envia_email as $parte) {
                                $args_email = [
                                    'no_email_contato' => $parte->no_email_contato,
                                    'no_contato' => $parte->no_parte,
                                    'senha' => Crypt::decryptString($parte->pedido_usuario->pedido_usuario_senha->senha_crypt),
                                    'token' => $parte->pedido_usuario->token,
                                ];
                                $this->enviar_email_iniciar_assinatura($documento, $args_email);
                            }

                            // Enviar e-mails para os procuradores
                            foreach ($procuradores_envia_email as $procurador) {
                                $args_email = [
                                    'no_email_contato' => $procurador->no_email_contato,
                                    'no_contato' => $procurador->no_procurador,
                                    'senha' => Crypt::decryptString($procurador->pedido_usuario->pedido_usuario_senha->senha_crypt),
                                    'token' => $procurador->pedido_usuario->token,
                                ];
                                $this->enviar_email_iniciar_assinatura($documento, $args_email);
                            }
                        }
                    }
                }
            }

            return 'A assinatura foi atualizada com sucesso.';
        }
    }

    /**
     * @param $request
     * @throws Exception
     */
    private function notificacao_processo_registro($request)
    {
        if ($request->uuid) {
            $registro_fiduciario_assinatura = $this->RegistroFiduciarioAssinaturaServiceInterface->buscar_pdavh_uuid($request->uuid);

            if (!$registro_fiduciario_assinatura)
                return false;

            $registro_fiduciario = $registro_fiduciario_assinatura->registro_fiduciario;
            $pedido = $registro_fiduciario->registro_fiduciario_pedido->pedido;

            if ($registro_fiduciario_assinatura->id_registro_fiduciario_assinatura_tipo == config('constants.REGISTRO_FIDUCIARIO.ASSINATURAS.TIPOS.CONTRATO')
                && $request->state === 6) {
                $args_registro_fiduciario = new stdClass();
                $args_registro_fiduciario->dt_assinatura_contrato = Carbon::now();
                $this->RegistroFiduciarioServiceInterface->alterar($registro_fiduciario, $args_registro_fiduciario);
            }

            // Insere o histórico do pedido
            $this->HistoricoPedidoServiceInterface
                ->inserir_historico(
                    $pedido,
                    'Todas as assinaturas de '.$registro_fiduciario_assinatura->registro_fiduciario_assinatura_tipo->no_tipo.' foram concluídas.',
                    1
                );

            //Enviar Email observador 
            $mensagem = "Todas as assinaturas de ".$registro_fiduciario_assinatura->registro_fiduciario_assinatura_tipo->no_tipo." do processo " .$registro_fiduciario->registro_fiduciario_pedido->pedido->protocolo_pedido." foram concluídas com sucesso.";

            $mensagemBradesco = "Todas as assinaturas de ".$registro_fiduciario_assinatura->registro_fiduciario_assinatura_tipo->no_tipo." do processo " .$registro_fiduciario->registro_fiduciario_pedido->pedido->protocolo_pedido." foram concluídas com sucesso.";

            $this->enviar_email_observador_registro($registro_fiduciario, $mensagem, $mensagemBradesco);   
            $this->enviar_email_operadores_registro($registro_fiduciario, $mensagem, $mensagemBradesco);   

            // Atualizar data de alteração
            $args_registro_fiduciario = new stdClass();
            $args_registro_fiduciario->dt_alteracao = Carbon::now();

            $this->RegistroFiduciarioServiceInterface->alterar($registro_fiduciario, $args_registro_fiduciario);
            
            LogDB::insere(
                1,
                6,
                'A assinatura foi finalizada com sucesso.',
                'Registro - Assinaturas',
                'N',
                request()->ip()
            );

            return 'O processo de assinatura foi atualizado com sucesso.';
        }
    }

    private function notificacao_processo_documento($request)
    {
        if ($request->uuid) {
            $documento_assinatura = $this->DocumentoAssinaturaServiceInterface->buscar_pdavh_uuid($request->uuid);

            if (!$documento_assinatura)
                throw new Exception('O processo de assinatura não foi encontrado');

            $documento = $documento_assinatura->documento;
            $pedido = $documento->pedido;

            if ($request->state === 6) {
                $assinaturas_nao_finalizadas = $documento->documento_assinatura()
                    ->where('id_documento_assinatura', '<>', $documento_assinatura->id_documento_assinatura)
                    ->where('in_finalizado', 'N')
                    ->count();

                $args_documento_assinatura = new stdClass();
                $args_documento_assinatura->in_finalizado = 'S';
                $this->DocumentoAssinaturaServiceInterface->alterar($documento_assinatura, $args_documento_assinatura);

                if ($assinaturas_nao_finalizadas<=0) {
                    $args_documento = new stdClass();
                    $args_documento->dt_assinatura = Carbon::now();
                    $args_documento->dt_finalizacao = Carbon::now();
                    $this->DocumentoServiceInterface->alterar($documento, $args_documento);

                    $args_pedido = new stdClass();
                    $args_pedido->id_situacao_pedido_grupo_produto = config('constants.DOCUMENTO.SITUACOES.ID_FINALIZADO');
                    $this->PedidoServiceInterface->alterar($pedido, $args_pedido);

                    $documento_partes = $documento->documento_parte()
                        ->whereIn('id_documento_parte_tipo', [
                            config('constants.DOCUMENTO.PARTES.ID_CEDENTE'),
                            config('constants.DOCUMENTO.PARTES.ID_JURIDICO_INTERNO')
                        ])
                        ->get();

                    foreach ($documento_partes as $documento_parte) {
                        if (count($documento_parte->documento_procurador)>0 && $documento_parte->in_assinatura_parte=='N') {
                            foreach ($documento_parte->documento_procurador as $documento_procurador) {
                                $args_email = [
                                    'no_email_contato' => $documento_procurador->no_email_contato,
                                    'no_contato' => $documento_procurador->no_procurador,
                                    'senha' => Crypt::decryptString($documento_procurador->pedido_usuario->pedido_usuario_senha->senha_crypt),
                                    'token' => $documento_procurador->pedido_usuario->token,
                                ];
                                $this->enviar_email_documento_finalizado($documento, $args_email);
                            }
                        } else {
                            $args_email = [
                                'no_email_contato' => $documento_parte->no_email_contato,
                                'no_contato' => $documento_parte->no_parte,
                                'senha' => Crypt::decryptString($documento_parte->pedido_usuario->pedido_usuario_senha->senha_crypt),
                                'token' => $documento_parte->pedido_usuario->token,
                            ];
                            $this->enviar_email_documento_finalizado($documento, $args_email);
                        }
                    }

                    $mensagem = "O documento <b>".$pedido->protocolo_pedido."</b> foi finalizado, acesse a plataforma para conferir os documentos assinados.";
                    $this->enviar_email_observador_documento($documento, $mensagem);
                }

                // Insere o histórico do pedido
                $this->HistoricoPedidoServiceInterface
                    ->inserir_historico(
                        $pedido,
                        'Todas as assinaturas de '.$documento_assinatura->documento_assinatura_tipo->no_documento_assinatura_tipo.' foram concluídas.',
                        1
                    );
            }

            // Atualizar data de alteração
            $args_documento = new stdClass();
            $args_documento->dt_alteracao = Carbon::now();

            $this->DocumentoServiceInterface->alterar($documento, $args_documento);

            LogDB::insere(
                1,
                6,
                'A assinatura foi finalizada com sucesso.',
                'Documentos - Assinaturas',
                'N',
                request()->ip()
            );

            return 'O processo de assinatura foi atualizado com sucesso.';
        }
    }

    private function usuario_certificado($certificate)
    {
        if ($certificate) {
            $usuario_certificado = $this->UsuarioCertificadoServiceInterface->buscar_serial($certificate->serial);
            if ($usuario_certificado)
                return $usuario_certificado->id_usuario_certificado;

            $args_novo_usuario_certificado = new stdClass();
            $args_novo_usuario_certificado->no_comum = $certificate->common_name;
            $args_novo_usuario_certificado->no_autoridade_raiz = $certificate->organization_name;
            $args_novo_usuario_certificado->no_autoridade_unidade = $certificate->organizational_unit;
            $args_novo_usuario_certificado->no_autoridade_certificadora = $certificate->certificate_authority;
            $args_novo_usuario_certificado->nu_serial = $certificate->serial;
            $args_novo_usuario_certificado->dt_validade_ini = Carbon::parse($certificate->start_at);
            $args_novo_usuario_certificado->dt_validade_fim = Carbon::parse($certificate->end_at);
            $args_novo_usuario_certificado->tp_certificado = $certificate->version;
            $args_novo_usuario_certificado->nu_cpf_cnpj = $certificate->identifier;
            $args_novo_usuario_certificado->no_responsavel = $certificate->name;
            $args_novo_usuario_certificado->de_campos = $certificate->fields;

            $novo_usuario_certificado = $this->UsuarioCertificadoServiceInterface->inserir($args_novo_usuario_certificado);

            return $novo_usuario_certificado->id_usuario_certificado;
        }

        return null;
    }
}
