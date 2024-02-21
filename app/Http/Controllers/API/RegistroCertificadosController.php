<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\StoreHistoricoCertificado;
use Exception;
use Gate;
use Helper;
use Carbon\Carbon;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Domain\RegistroFiduciario\Contracts\RegistroFiduciarioServiceInterface;

class RegistroCertificadosController extends Controller
{
    /**
     * @var RegistroFiduciarioServiceInterface
     */

    protected $RegistroFiduciarioServiceInterface;

    /**
     * RegistroCertificadosController constructor.
     * @param RegistroFiduciarioServiceInterface $RegistroFiduciarioServiceInterface
     */

    public function __construct(RegistroFiduciarioServiceInterface $RegistroFiduciarioServiceInterface)
    {
        parent::__construct();
        $this->RegistroFiduciarioServiceInterface = $RegistroFiduciarioServiceInterface;
    }

    public function index(Request $request)
    {
        $registro_fiduciario = $this->RegistroFiduciarioServiceInterface->buscar_uuid($request->registro);

        if(!$registro_fiduciario)
            throw new Exception('Registro não encontrado');

        Gate::authorize('api-registros-certificados', $registro_fiduciario);

        $partes_emissao_certificados = [];
        foreach ($registro_fiduciario->registro_fiduciario_parte as $registro_fiduciario_parte) {
            if(count($registro_fiduciario_parte->registro_fiduciario_procurador)>0) {
                foreach($registro_fiduciario_parte->registro_fiduciario_procurador as $registro_fiduciario_procurador) {
                    $parte_emissao_certificado = $registro_fiduciario_procurador->parte_emissao_certificado;
                    $partes_emissao_certificados[] = [
                        "uuid_parte" => $registro_fiduciario_parte->uuid,
                        "nome_parte" => $registro_fiduciario_parte->no_parte,
                        "uuid_procurador" => $registro_fiduciario_procurador->uuid,
                        "nome_procurador" => $registro_fiduciario_procurador->no_procurador,
                        "situacao" => $parte_emissao_certificado->id_parte_emissao_certificado_situacao,
                        "descricao_situacao" => $parte_emissao_certificado->parte_emissao_certificado_situacao->no_situacao,
                        'data_atualizacao' => Helper::formata_data_hora($parte_emissao_certificado->dt_atualizacao, 'Y-m-d H:i:s')
                    ];
                }
            } else {
                $parte_emissao_certificado = $registro_fiduciario_parte->parte_emissao_certificado;
                $partes_emissao_certificados[] = [
                    "uuid_parte" => $registro_fiduciario_parte->uuid,
                    "nome_parte" => $registro_fiduciario_parte->no_parte,
                    "uuid_procurador" => null,
                    "nome_procurador" => null,
                    "situacao" => $parte_emissao_certificado->id_parte_emissao_certificado_situacao,
                    "descricao_situacao" => $parte_emissao_certificado->parte_emissao_certificado_situacao->no_situacao,
                    'data_atualizacao' => Helper::formata_data_hora($parte_emissao_certificado->dt_atualizacao, 'Y-m-d H:i:s')
                ];

            }
        }

        $response_json = [
            'certificados' => $partes_emissao_certificados
        ];
        return response()->json($response_json, 200);
    }
}
