<?php

namespace App\Http\Controllers\Registros;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Auth;

use App\Domain\RegistroFiduciario\Contracts\RegistroFiduciarioCustodianteServiceInterface;

class RegistroFiduciarioCustodianteController extends Controller
{
    /**
     * @var RegistroFiduciarioCustodianteServiceInterface
     *
     */
    protected $RegistroFiduciarioCustodianteServiceInterface;

    /**
     * RegistroFiduciarioCustodianteController constructor.
     * @param RegistroFiduciarioCustodianteServiceInterface $RegistroFiduciarioCustodianteServiceInterface
     */
    public function __construct(RegistroFiduciarioCustodianteServiceInterface $RegistroFiduciarioCustodianteServiceInterface)
    {
        parent::__construct();
        $this->RegistroFiduciarioCustodianteServiceInterface = $RegistroFiduciarioCustodianteServiceInterface;
    }

    public function index(Request $request)
    {
        $custodiantes = [];

        if ($request->id_cidade) {
            $custodiantes = $this->RegistroFiduciarioCustodianteServiceInterface->custodiantes_disponiveis($request->id_cidade);
        }
        return response()->json($custodiantes);
    }

    public function show(Request $request)
    {
        
    }
}
