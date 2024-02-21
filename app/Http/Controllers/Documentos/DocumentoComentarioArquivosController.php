<?php

namespace App\Http\Controllers\Documentos;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use DB;
use Exception;
use stdClass;
use LogDB;
use Auth;
use Carbon\Carbon;
use Upload;

use App\Domain\Documento\Documento\Contracts\DocumentoComentarioServiceInterface;

class DocumentoComentarioArquivosController extends Controller
{
    protected $DocumentoComentarioServiceInterface;

    public function __construct(DocumentoComentarioServiceInterface $DocumentoComentarioServiceInterface)
    {
        $this->DocumentoComentarioServiceInterface = $DocumentoComentarioServiceInterface;
    }

    public function index(Request $request)
    {
        $documento_comentario = $this->DocumentoComentarioServiceInterface->buscar($request->comentario);

        if ($documento_comentario) {
            $compact_args = [
                'documento_comentario' => $documento_comentario
            ];
            return view('app.produtos.documentos.detalhes.comentarios.geral-documentos-comentarios-arquivos', $compact_args);
        }
    }

}
