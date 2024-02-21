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

use App\Domain\RegistroFiduciarioAssinatura\Contracts\RegistroFiduciarioParteAssinaturaServiceInterface;

class RegistroController extends Controller {

	/**
	 * @var RegistroFiduciarioParteAssinaturaServiceInterface
	 */
	private $RegistroFiduciarioParteAssinaturaServiceInterface;

	/**
	 * RegistroController constructor.
	 * @param RegistroFiduciarioParteAssinaturaServiceInterface $RegistroFiduciarioParteAssinaturaServiceInterface
	 */
	public function __construct(RegistroFiduciarioParteAssinaturaServiceInterface $RegistroFiduciarioParteAssinaturaServiceInterface)
	{
		$this->RegistroFiduciarioParteAssinaturaServiceInterface = $RegistroFiduciarioParteAssinaturaServiceInterface;
	}

	public function visualizar_assinatura(Request $request)
    {
        $registro_fiduciario_parte_assinatura = $this->RegistroFiduciarioParteAssinaturaServiceInterface->buscar($request->parte_assinatura);

        if ($registro_fiduciario_parte_assinatura) {
            $compact_args = [
                'registro_fiduciario_parte_assinatura' => $registro_fiduciario_parte_assinatura
            ];

            return view('protocolo.produtos.registro-fiduciario.assinaturas.geral-registro-visualizar-assinatura', $compact_args);
        }
    }
}
