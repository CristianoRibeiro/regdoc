<?php

namespace App\Http\Controllers\Registros;

use App\Helpers\LogDB;
use App\Helpers\PDAVH;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Domain\RegistroFiduciario\Contracts\RegistroFiduciarioServiceInterface;
use App\Domain\RegistroFiduciarioAssinatura\Contracts\RegistroFiduciarioAssinaturaServiceInterface;
use App\Domain\RegistroFiduciarioAssinatura\Contracts\RegistroFiduciarioParteAssinaturaServiceInterface;
use App\Domain\Configuracao\Contracts\ConfiguracaoPessoaServiceInterface;
use App\Domain\Arquivo\Contracts\ArquivoServiceInterface;
use App\Domain\Pedido\Contracts\HistoricoPedidoServiceInterface;
use App\Http\Controllers\Controller;
use App\Http\Requests\RegistroFiduciario\StoreIniciarAssinaturas;
use App\Http\Requests\RegistroFiduciario\StoreIniciarAssinaturasPartes;
use App\Traits\EmailRegistro;
use Exception;
use URL;

class RegistroFiduciarioAssinaturaController extends Controller
{
    use EmailRegistro;

    protected $RegistroFiduciarioServiceInterface;
    protected $RegistroFiduciarioAssinaturaServiceInterface;
    protected $RegistroFiduciarioParteAssinaturaServiceInterface;
    protected $ConfiguracaoPessoaServiceInterface;
    protected $ArquivoServiceInterface;
    protected $HistoricoPedidoServiceInterface;

    /**
     * RegistroFiduciarioAssinatura constructor.
     * @param RegistroFiduciarioServiceInterface $RegistroFiduciarioServiceInterface
     * @param RegistroFiduciarioAssinaturaServiceInterface $RegistroFiduciarioAssinaturaServiceInterface
     * @param RegistroFiduciarioParteAssinaturaServiceInterface $RegistroFiduciarioParteAssinaturaServiceInterface
     * @param ConfiguracaoPessoaServiceInterface $ConfiguracaoPessoaServiceInterface
     * @param ArquivoServiceInterface $ArquivoServiceInterface
     * @param HistoricoPedidoServiceInterface $HistoricoPedidoServiceInterface
     */
    public function __construct(RegistroFiduciarioServiceInterface $RegistroFiduciarioServiceInterface,
        RegistroFiduciarioAssinaturaServiceInterface $RegistroFiduciarioAssinaturaServiceInterface,
        RegistroFiduciarioParteAssinaturaServiceInterface $RegistroFiduciarioParteAssinaturaServiceInterface,
        ConfiguracaoPessoaServiceInterface $ConfiguracaoPessoaServiceInterface,
        ArquivoServiceInterface $ArquivoServiceInterface,
        HistoricoPedidoServiceInterface $HistoricoPedidoServiceInterface)
    {
        parent::__construct();
        $this->RegistroFiduciarioServiceInterface = $RegistroFiduciarioServiceInterface;
        $this->RegistroFiduciarioAssinaturaServiceInterface = $RegistroFiduciarioAssinaturaServiceInterface;
        $this->RegistroFiduciarioParteAssinaturaServiceInterface = $RegistroFiduciarioParteAssinaturaServiceInterface;
        $this->ConfiguracaoPessoaServiceInterface = $ConfiguracaoPessoaServiceInterface;
        $this->ArquivoServiceInterface = $ArquivoServiceInterface;
        $this->HistoricoPedidoServiceInterface = $HistoricoPedidoServiceInterface;

    }
    public function visualizar_assinatura(Request $request)
    {
        $registro_fiduciario_parte_assinatura = $this->RegistroFiduciarioParteAssinaturaServiceInterface->buscar($request->parte_assinatura);

        if ($registro_fiduciario_parte_assinatura) {
            $compact_args = [
                'registro_fiduciario_parte_assinatura' => $registro_fiduciario_parte_assinatura
            ];

            return view('app.produtos.registro-fiduciario.detalhes.assinaturas.geral-registro-visualizar-assinatura', $compact_args);
        }
    }

    public function show(Request $request)
    {
        $registro_fiduciario_assinatura = $this->RegistroFiduciarioAssinaturaServiceInterface->buscar($request->assinatura);

        $arquivos_partes = [];
        foreach ($registro_fiduciario_assinatura->registro_fiduciario_parte_assinatura_arquivos as $registro_fiduciario_parte_assinatura_arquivo) {
            $arquivos_partes[$registro_fiduciario_parte_assinatura_arquivo->id_arquivo_grupo_produto]['arquivo_grupo_produto'] = $registro_fiduciario_parte_assinatura_arquivo->arquivo_grupo_produto;
            $arquivos_partes[$registro_fiduciario_parte_assinatura_arquivo->id_arquivo_grupo_produto]['partes_assinaturas'][] = $registro_fiduciario_parte_assinatura_arquivo->registro_fiduciario_parte_assinatura;
        }

        $compact_args = [
            'registro_fiduciario_assinatura' => $registro_fiduciario_assinatura,
            'arquivos_partes' => $arquivos_partes
        ];
        return view('app.produtos.registro-fiduciario.detalhes.assinaturas.geral-registro-assinatura-detalhes', $compact_args);
    }

    public function iniciar_assinaturas(Request $request)
    {
        $registro_fiduciario = $this->RegistroFiduciarioServiceInterface->buscar($request->registro);

        Gate::authorize('registros-iniciar-assinaturas', $registro_fiduciario);

        $arquivos_registro = $registro_fiduciario->arquivos_grupo()
                                                 ->whereNotIn('id_tipo_arquivo_grupo_produto', [
                                                    config('constants.TIPO_ARQUIVO.11.ID_XML_CONTRATO')
                                                 ])
                                                 ->get();
        $arquivos_partes = $registro_fiduciario->arquivos_partes->pluck('arquivo_grupo_produto');

        $arquivos = $arquivos_registro->merge($arquivos_partes);

        // Argumentos para o retorno da view
        $compact_args = [
            'registro_fiduciario' => $registro_fiduciario,
            'arquivos' => $arquivos,
            'registro_token' => Str::random(30)
        ];

        return view('app.produtos.registro-fiduciario.detalhes.assinaturas.geral-registro-iniciar-assinaturas', $compact_args);
    }

    public function iniciar_assinaturas_outros_arquivos(Request $request)
    {
        Gate::allows('registros-assinatura-multipla-A1-valid');

        $registro_fiduciario = $this->RegistroFiduciarioServiceInterface->buscar($request->registro);
        $pedido = $registro_fiduciario->registro_fiduciario_pedido->pedido;

        $arquivos = $registro_fiduciario->arquivos_grupo()
            ->whereIn('arquivo_grupo_produto.id_arquivo_grupo_produto', $request->idsArquivos)
            ->where('arquivo_grupo_produto.in_ass_digital', 'N')
            ->whereIn('arquivo_grupo_produto.id_tipo_arquivo_grupo_produto', [
                config('constants.TIPO_ARQUIVO.11.ID_OUTROS'),
                config('constants.TIPO_ARQUIVO.11.ID_IMOVEL'),
                config('constants.TIPO_ARQUIVO.11.ID_ADITIVO')
            ])
            ->get();
        
        if(count($arquivos) === 0)
        {
            return response()->json([
                'status' => 'erro',
                'message' => 'Nenhum dos arquivos selecionados pode ser assinado.',
                'regarrega' => 'false'
            ], 500);
        }

        $files = [];
        foreach ($arquivos as $arquivo)
        {
            $arquivo_path = "public{$arquivo->no_local_arquivo}/{$arquivo->no_arquivo}";
            $arquivo_content = Storage::get($arquivo_path);

            $files[] = [
                'code' => $arquivo->id_arquivo_grupo_produto,
                'content' => base64_encode($arquivo_content),
                'filename' => $arquivo->no_descricao_arquivo,
                'extension' => $arquivo->no_extensao,
                'mime' => $arquivo->no_mime_type,
                'hash' => $arquivo->no_hash,
                'size' => $arquivo->nu_tamanho_kb
            ];
        }
        
        $signers = [
            [
                "code" => "000-Validhub",
                "name" => "Sidney Coutinho de Faria",
                "email" => "sidney.faria@valid.com",
                "restrict" => false,
                "identifier" => "037.292.291-09"
            ]
        ];
        
        $response = PDAVH::init_signature_process("Registro nº {$pedido->protocolo_pedido} - Assinatura com A1 da Validhub", $pedido->id_pedido, 1, $files, $signers, URL::to('/pdavh/notificacao-outros-arquivos'));

        //Verifico se o pedido pertence o bradesco agro
        if($pedido->id_pessoa_origem == config('parceiros.BANCOS.BRADESCO_AGRO')){

            $registro_fiduciario_partes = $registro_fiduciario->registro_fiduciario_parte()->get();

            foreach ($registro_fiduciario_partes as $registro_fiduciario_parte) {

                $args_email = [
                    'no_email_contato' => $registro_fiduciario_parte->no_email_contato,
                    'no_contato' => $registro_fiduciario_parte->no_parte,
                    'token' => $registro_fiduciario_parte->pedido_usuario->token,
                ];

                $this->enviar_email_assinar_outros_documentos($registro_fiduciario, $args_email);
            }
        }

        $response_json = [
            'status' => 'sucesso',
            'message' => 'Assinaturas iniciadas com sucesso.',
            'url' => $response->signers[0]->url
        ];

        return response()->json($response_json, 200);
    }

    public function salvar_iniciar_assinaturas(StoreIniciarAssinaturas $request)
    {
       $registro_fiduciario = $this->RegistroFiduciarioServiceInterface->buscar($request->registro);

       DB::beginTransaction();

       try {
           $pedido = $registro_fiduciario->registro_fiduciario_pedido->pedido;

            if ($request->session()->has('signatarios_arquivos_' . $request->registro_token)) {
                $signatarios_arquivos = $request->session()->get('signatarios_arquivos_' . $request->registro_token);
            } else {
                $signatarios_arquivos = [];
            }

            $nova_assinatura = $this->RegistroFiduciarioAssinaturaServiceInterface->inserir_assinatura(
                $registro_fiduciario,
                0,
                config('constants.REGISTRO_FIDUCIARIO.ASSINATURAS.TIPOS.OUTRAS'),
                [],
                [],
                $request->id_arquivo_grupo_produto,
                $signatarios_arquivos,
            );

            // Insere o histórico do pedido
            $this->HistoricoPedidoServiceInterface->inserir_historico($pedido, 'A assinatura de outros arquivos foi iniciada com sucesso.');

            foreach ($nova_assinatura->registro_fiduciario_parte_assinatura as $registro_fiduciario_parte_assinatura) {
                if ($registro_fiduciario_parte_assinatura->registro_fiduciario_procurador) {
                    $registro_fiduciario_procurador = $registro_fiduciario_parte_assinatura->registro_fiduciario_procurador;
                    $args_email = [
                        'no_email_contato' => $registro_fiduciario_procurador->no_email_contato,
                        'no_contato' => $registro_fiduciario_procurador->no_procurador,
                        'senha' => Crypt::decryptString($registro_fiduciario_procurador->pedido_usuario->pedido_usuario_senha->senha_crypt),
                        'token' => $registro_fiduciario_procurador->pedido_usuario->token,
                    ];
                    $this->enviar_email_nova_assinatura($registro_fiduciario, $args_email);
                } else {
                    $registro_fiduciario_parte = $registro_fiduciario_parte_assinatura->registro_fiduciario_parte;
                    $args_email = [
                        'no_email_contato' => $registro_fiduciario_parte->no_email_contato,
                        'no_contato' => $registro_fiduciario_parte->no_parte,
                        'senha' => Crypt::decryptString($registro_fiduciario_parte->pedido_usuario->pedido_usuario_senha->senha_crypt),
                        'token' => $registro_fiduciario_parte->pedido_usuario->token,
                    ];
                    if($pedido->id_pessoa_origem == config('parceiros.BANCOS.BRADESCO_AGRO')){
                        $this->enviar_email_assinar_outros_documentos($registro_fiduciario, $args_email);
                    }else{
                        $this->enviar_email_nova_assinatura($registro_fiduciario, $args_email);
                    }   
                }
            }

            DB::commit();

            LogDB::insere(
                Auth::User()->id_usuario,
                6,
                'Iniciou assinaturas no pedido '.$registro_fiduciario->registro_fiduciario_pedido->pedido->protocolo_pedido.' com sucesso.',
                'Registro',
                'N',
                request()->ip()
            );

            $response_json = [
                'status' => 'sucesso',
                'message' => 'Assinaturas iniciadas com sucesso.',
                'recarrega' => 'true'
            ];

            return response()->json($response_json, 200);

       } catch (Exception $e) {
            DB::rollback();

            LogDB::insere(
                Auth::user()->id_usuario,
                4,
                'Erro ao iniciar assinaturas do pedido.',
                'Registro - Assinaturas',
                'N',
                request()->ip(),
                $e->getMessage() . ' na linha ' . $e->getLine() . ' do arquivo ' . $e->getFile()
            );

            $response_json = [
                'status' => 'erro',
                'message' => 'Erro interno, tente novamente mais tarde. '.(config('app.env')!='production'?$e->getMessage() . ' na linha ' . $e->getLine() . ' do arquivo ' . $e->getFile():''),
                'regarrega' => 'false'
            ];

            return response()->json($response_json, 500);
       }
    }

    public function configurar_partes(Request $request)
    {
        $registro_fiduciario = $this->RegistroFiduciarioServiceInterface->buscar($request->registro);

        $arquivo_grupo_produto = $this->ArquivoServiceInterface->buscar($request->arquivo);

        if ($request->session()->has('signatarios_arquivos_' . $request->registro_token)) {
            $signatarios_arquivos = $request->session()->get('signatarios_arquivos_' . $request->registro_token);
        } else {
            $signatarios_arquivos = [];
        }

        $compact_args = [
            'registro_fiduciario' => $registro_fiduciario,
            'arquivo_grupo_produto' => $arquivo_grupo_produto,
            'signatarios_arquivo' => $signatarios_arquivos[$arquivo_grupo_produto->id_arquivo_grupo_produto] ?? []
        ];

        return view('app.produtos.registro-fiduciario.detalhes.assinaturas.geral-registro-iniciar-assinaturas-partes', $compact_args);
    }

    public function salvar_configurar_partes(StoreIniciarAssinaturasPartes $request)
    {
        $arquivo_grupo_produto = $this->ArquivoServiceInterface->buscar($request->id_arquivo_grupo_produto);

        if ($request->session()->has('signatarios_arquivos_' . $request->registro_token)) {
            $signatarios_arquivos = $request->session()->get('signatarios_arquivos_' . $request->registro_token);
        } else {
            $signatarios_arquivos = [];
        }

        $signatarios_arquivos[$arquivo_grupo_produto->id_arquivo_grupo_produto] = $request->partes;

        $request->session()->put('signatarios_arquivos_' . $request->registro_token, $signatarios_arquivos);

        $response_json = [
            'status' => 'sucesso',
            'message' => 'Partes configuradas com sucesso.',
            'recarrega' => 'false'
        ];
        return response()->json($response_json, 200);
    }
}
