<?php
namespace App\Http\Controllers\Protocolo;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Auth;
use Storage;
use Helper;
use URL;
use PDAVH;

use App\Domain\Arquivo\Contracts\ArquivoServiceInterface;
use App\Domain\RegistroFiduciarioAssinatura\Contracts\RegistroFiduciarioParteAssinaturaServiceInterface;

class AssinaturaController extends Controller
{
    /** 
    * @var ArquivoServiceInterface
    */
    protected $ArquivoServiceInterface;
    /**
     * AssinaturaController constructor.
     * @param ArquivoServiceInterface $ArquivoServiceInterface
     */

    public function __construct(ArquivoServiceInterface $ArquivoServiceInterface)
    {
        parent::__construct();
        $this->ArquivoServiceInterface = $ArquivoServiceInterface;
    } 

    public function iniciar_assinatura_lote(Request $request, RegistroFiduciarioParteAssinaturaServiceInterface $RegistroFiduciarioParteAssinaturaServiceInterface)
	{
        $ids_parte_assinatura = explode(",", $request->ids_parte_assinatura);
        $qualificacoes = explode(",", $request->qualificacoes);

        $files = [];
        foreach ($ids_parte_assinatura as $id)
        {
            $parte_assinatura = $RegistroFiduciarioParteAssinaturaServiceInterface->buscar($id);
            if(!$parte_assinatura) continue;

            foreach($parte_assinatura->registro_fiduciario_parte_assinatura_arquivo as $parte_arquivo)
            {
                $arquivo = $parte_arquivo->arquivo_grupo_produto;
                
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
        }

        $signers = [
            [
                "code" => $request->ids_parte_assinatura,
                "name" => Auth::User()->pessoa->no_pessoa,
                "email" => Auth::User()->pessoa->no_email_pessoa,
                "restrict" => false,
                "identifier" => Auth::User()->pessoa->nu_cpf_cnpj,
                "qualification" => $qualificacoes[0]
            ]
        ];

        $id_produto = config('constants.REGISTRO_FIDUCIARIO.ID_PRODUTO');
        // Determina o protocolo do pedido
        $protocolo_pedido = Helper::gerar_protocolo(Auth::User()->pessoa_ativa->id_pessoa, $id_produto, config('constants.REGISTRO_FIDUCIARIO.ID_GRUPO_PRODUTO'));

        $response = PDAVH::init_signature_process("Registro nº {$protocolo_pedido} - Assinatura em lote", $protocolo_pedido, 1, $files, $signers, URL::to('/pdavh/notificacao-lote'));

        return $response->signers[0]->url;
    }

}