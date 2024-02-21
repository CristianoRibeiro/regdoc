<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\API\IndexCredores;

use App\Domain\Estado\Contracts\CidadeServiceInterface;
use App\Domain\RegistroFiduciario\Contracts\RegistroFiduciarioCredorServiceInterface;

use Auth;

class CredorController  extends Controller
{
    /**
     * @var CidadeServiceInterface
     * @var RegistroFiduciarioCredorServiceInterface
     */ 
     
    protected $CidadeServiceInterface;
    protected $RegistroFiduciarioCredorServiceInterface;
    
    /**
     * CidadeController constructor.
     * @param CidadeServiceInterface $CidadeServiceInterface
     * @param RegistroFiduciarioCredorServiceInterface $RegistroFiduciarioCredorServiceInterface
     */

    public function __construct(CidadeServiceInterface $CidadeServiceInterface,
        RegistroFiduciarioCredorServiceInterface $RegistroFiduciarioCredorServiceInterface)
    {
        parent::__construct();
        $this->CidadeServiceInterface = $CidadeServiceInterface;
        $this->RegistroFiduciarioCredorServiceInterface = $RegistroFiduciarioCredorServiceInterface;
    }

    public function index(IndexCredores $request) 
    { 
        $cidade = $this->CidadeServiceInterface->buscar_ibge($request->cidade);

        $credores = $this->RegistroFiduciarioCredorServiceInterface->credores_disponiveis_agencia($cidade->id_cidade, Auth::User()->pessoa_ativa->id_pessoa);
       
        foreach($credores as $credor) {
            $credores_disponiveis[] = [
                'cnpj' => $credor->nu_cpf_cnpj,
                'nome' => $credor->no_credor,
                'cidade' => [
                    'nome' => $credor->cidade->no_cidade,
                    'ibge'=> $credor->cidade->co_ibge,
                    'uf' => $credor->cidade->estado->uf,
                ]
            ];
        }
      
        $response_json = [
            'credores' => $credores_disponiveis ?? []
        ];
        return response()->json($response_json, 200);     
    }
} 
