<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Gate;

use App\Domain\Procuracao\Contracts\ProcuracaoServiceInterface;

class ProcuracaoController extends Controller
{
    /**
     * @var ProcuracaoServiceInterface
     *
     */

    protected $ProcuracaoServiceInterface;

    /**
     * @param ProcuracaoServiceInterface $ProcuracaoServiceInterface
     */
    public function __construct(ProcuracaoServiceInterface $ProcuracaoServiceInterface)
    {
        parent::__construct();
        $this->ProcuracaoServiceInterface = $ProcuracaoServiceInterface;
    }

    public function index()
    {
        $procuracoes_banco = $this->ProcuracaoServiceInterface->listar();

        Gate::authorize('api-procuracoes');

        $procuracoes = [];
        foreach ($procuracoes_banco as $procuracao) {
            $procuracoes[] = [
                "uuid" => $procuracao->uuid,
                "titulo" => $procuracao->no_identificacao
            ];
        }

        $response_json = [
            'procuracoes' => $procuracoes
        ];
        return response()->json($response_json, 200);
    }

}
