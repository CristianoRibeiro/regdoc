<?php

namespace App\Http\Controllers\Registros;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use DB;
use Exception;
use stdClass;
use LogDB;
use Auth;
use Carbon\Carbon;
use Upload;

use App\Domain\RegistroFiduciario\Contracts\RegistroFiduciarioComentarioServiceInterface;

class RegistroFiduciarioComentarioArquivosController extends Controller
{
    protected $RegistroFiduciarioComentarioServiceInterface;

    public function __construct(RegistroFiduciarioComentarioServiceInterface $RegistroFiduciarioComentarioServiceInterface)
    {
        $this->RegistroFiduciarioComentarioServiceInterface = $RegistroFiduciarioComentarioServiceInterface;
    }

    public function index(Request $request)
    {
        $registro_fiduciario_comentario = $this->RegistroFiduciarioComentarioServiceInterface->buscar($request->comentario);

        if ($registro_fiduciario_comentario) {
            $compact_args = [
                'registro_fiduciario_comentario' => $registro_fiduciario_comentario
            ];
            return view('app.produtos.registro-fiduciario.detalhes.comentarios.geral-registro-comentarios-arquivos', $compact_args);
        }
    }

}
