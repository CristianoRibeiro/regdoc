<?php

namespace App\Http\Controllers\Registros;

use App\Domain\RegistroFiduciario\Contracts\RegistroFiduciarioServiceInterface;
use App\Domain\RegistroFiduciario\Contracts\RegistroFiduciarioComentarioInternoRepositoryInterface;

use App\Helpers\LogDB;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegistroFiduciario\Comentarios\StoreComentarioRegistroFiduciario;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;

use Exception;
use stdClass;

final class RegistroFiduciarioComentarioInternoController extends Controller
{
  private RegistroFiduciarioComentarioInternoRepositoryInterface $ComentarioInternoRepository;

  private RegistroFiduciarioServiceInterface $RegistroFiduciarioService;

  public function __construct(RegistroFiduciarioComentarioInternoRepositoryInterface $ComentarioInternoRepository, RegistroFiduciarioServiceInterface $RegistroFiduciarioService)
  {
    $this->ComentarioInternoRepository = $ComentarioInternoRepository;
    $this->RegistroFiduciarioService = $RegistroFiduciarioService;
  }

  public function index(Request $request)
  {
    $registroFiduciario = $this->RegistroFiduciarioService->buscar($request->registro);

    Gate::authorize('registros-comentarios-internos');

    $args = [
        'registro_fiduciario' => $registroFiduciario,
        'registro_token' => Str::random(30)
    ];
    return view('app.produtos.registro-fiduciario.detalhes.comentarios.geral-registro-comentarios-internos-detalhes', $args);
  }

  public function store(StoreComentarioRegistroFiduciario $request): JsonResponse
  {
    $registroFiduciario = $this->RegistroFiduciarioService->buscar($request->registro);

    Gate::authorize('registros-comentarios-internos');

    DB::beginTransaction();

    try {
      
      $args = new stdClass();
      $args->id_registro_fiduciario = $registroFiduciario->id_registro_fiduciario;
      $args->de_comentario = nl2br(strip_tags($request->de_comentario));
      $args->in_direcao = "C";
      
      $this->ComentarioInternoRepository->inserir($args);

      DB::commit();

      LogDB::insere(
        Auth::User()->id_usuario,
        6,
        'API - O comentário interno do registro foi salvo com sucesso.',
        'Registro - Comentários Internos',
        'N',
        request()->ip()
      );

      $response = [
        'status' => 'sucesso',
        'recarrega' => 'true',
        'message' => 'O comentário foi inserido com sucesso.'
      ];
      return response()->json($response, 200);

    } catch (Exception $e) {
      DB::rollBack();

      LogDB::insere(
          Auth::User()->id_usuario,
          4,
          'Error ao salvar o comentário interno do registro.',
          'Registro - Comentários Internos',
          'N',
          request()->ip(),
          $e->getMessage() . ' na linha ' . $e->getLine() . ' do arquivo ' . $e->getFile()
      );

      $response_json = [
          'status' => 'erro',
          'recarrega' => 'false',
          'message' => 'Erro interno, tente novamente mais tarde. '.(config('app.env')!='production'?$e->getMessage() . ' na linha ' . $e->getLine() . ' do arquivo ' . $e->getFile():''),
      ];
      return response()->json($response_json, 500);
    }
  }
}