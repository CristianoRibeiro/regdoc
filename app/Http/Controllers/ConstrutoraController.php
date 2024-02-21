<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Domain\Construtora\Contracts\ConstrutoraServiceInterface;

class ConstrutoraController extends Controller
{
    /**
     * @var ConstrutoraServiceInterface
     *
     */
    protected $ConstrutoraServiceInterface;

    /**
     * ConstrutoraController constructor.
     * @param ConstrutoraServiceInterface $ConstrutoraServiceInterface
     */
    public function __construct(ConstrutoraServiceInterface $ConstrutoraServiceInterface)
    {
        parent::__construct();
        $this->ConstrutoraServiceInterface = $ConstrutoraServiceInterface;
    }

    public function empreendimentos(Request $request)
    {
        $empreendimentos = [];

        if ($request->id_construtora>0) {
            $construtora = $this->ConstrutoraServiceInterface->busca_construtora($request->id_construtora);

            $empreendimentos = $construtora->empreendimentos()
                ->select('id_empreendimento', 'no_empreendimento')
                ->where('in_registro_ativo', 'S')
                ->orderBy('no_empreendimento', 'ASC')
                ->get();
        }
        return response()->json($empreendimentos);
    }
}
