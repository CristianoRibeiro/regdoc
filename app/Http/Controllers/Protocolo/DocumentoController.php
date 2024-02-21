<?php
namespace App\Http\Controllers\Protocolo;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Auth;
use Carbon\Carbon;
use Storage;
use DB;
use Helper;
use Session;
use URL;

use App\Domain\Documento\Assinatura\Contracts\DocumentoParteAssinaturaServiceInterface;

class DocumentoController extends Controller {

	/**
	 * @var DocumentoParteAssinaturaServiceInterface
	 */
	private $DocumentoParteAssinaturaServiceInterface;

	/**
	 * DocumentoController constructor.
	 * @param DocumentoParteAssinaturaServiceInterface $DocumentoParteAssinaturaServiceInterface
	 */
	public function __construct(DocumentoParteAssinaturaServiceInterface $DocumentoParteAssinaturaServiceInterface)
	{
		$this->DocumentoParteAssinaturaServiceInterface = $DocumentoParteAssinaturaServiceInterface;
	}

	public function visualizar_assinatura(Request $request)
    {
        $documento_parte_assinatura = $this->DocumentoParteAssinaturaServiceInterface->buscar($request->parte_assinatura);

        if ($documento_parte_assinatura) {
            $compact_args = [
                'documento_parte_assinatura' => $documento_parte_assinatura
            ];

            return view('protocolo.produtos.documentos.assinaturas.geral-documentos-visualizar-assinatura', $compact_args);
        }
    }
}
