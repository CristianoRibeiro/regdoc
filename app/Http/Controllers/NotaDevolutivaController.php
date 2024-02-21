<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use stdClass;

use App\Domain\NotaDevolutiva\Contracts\NotaDevolutivaCausaGrupoServiceInterface;
use App\Domain\NotaDevolutiva\Contracts\NotaDevolutivaCausaRaizServiceInterface;

class NotaDevolutivaController extends Controller
{
    /**
     * @var NotaDevolutivaCausaGrupoServiceInterface
     * @var NotaDevolutivaCausaRaizServiceInterface
    */

    protected $NotaDevolutivaCausaGrupoServiceInterface;
    protected $NotaDevolutivaCausaRaizServiceInterface;

    /**
     * NotaDevolutivaController constructor.
     * @param NotaDevolutivaCausaGrupoServiceInterface $NotaDevolutivaCausaGrupoServiceInterface
     * @param NotaDevolutivaCausaRaizServiceInterface $NotaDevolutivaCausaRaizServiceInterface
     */
    
    public function __construct(NotaDevolutivaCausaGrupoServiceInterface $NotaDevolutivaCausaGrupoServiceInterface,
        NotaDevolutivaCausaRaizServiceInterface $NotaDevolutivaCausaRaizServiceInterface)
    {
        $this->NotaDevolutivaCausaGrupoServiceInterface = $NotaDevolutivaCausaGrupoServiceInterface;
        $this->NotaDevolutivaCausaRaizServiceInterface = $NotaDevolutivaCausaRaizServiceInterface;  
    } 

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function lista_nota_devolutiva_causa_grupo(Request $request)
    {
        $args_nota_devolutiva_classificacao = new stdClass();
        $args_nota_devolutiva_classificacao->id_causa_raiz_classificacao = $request->id_causa_raiz_classificacao;

        $nota_devolutiva_causas_grupos = $this->NotaDevolutivaCausaGrupoServiceInterface->listar($args_nota_devolutiva_classificacao);

        return response()->json($nota_devolutiva_causas_grupos);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function lista_nota_devolutiva_causa_raiz(Request $request)
    {
        $args_nota_devolutiva_causa_raiz_grupo = new stdClass();
        $args_nota_devolutiva_causa_raiz_grupo->id_nota_devolutiva_causa_raiz_grupo = $request->id_causa_raiz_grupo;

        $nota_devolutiva_causas_raizes = $this->NotaDevolutivaCausaRaizServiceInterface->listar($args_nota_devolutiva_causa_raiz_grupo);

        return response()->json($nota_devolutiva_causas_raizes);
    }
}    