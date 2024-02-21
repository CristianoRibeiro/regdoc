<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Gate;
use Auth;
use Helper;

use App\Domain\RegistroFiduciario\Contracts\RegistroFiduciarioServiceInterface;
use App\Domain\RegistroFiduciario\Contracts\RegistroFiduciarioParteServiceInterface;

class RegistroParteController extends Controller
{
    protected $RegistroFiduciarioServiceInterface;
    protected $RegistroFiduciarioParteServiceInterface;

    public function __construct(RegistroFiduciarioServiceInterface $RegistroFiduciarioServiceInterface,
                                RegistroFiduciarioParteServiceInterface $RegistroFiduciarioParteServiceInterface)
    {
        parent::__construct();
        $this->RegistroFiduciarioServiceInterface = $RegistroFiduciarioServiceInterface;
        $this->RegistroFiduciarioParteServiceInterface = $RegistroFiduciarioParteServiceInterface;
    }

    public function index($uuid)
    {
        $registro_fiduciario = $this->RegistroFiduciarioServiceInterface->buscar_uuid($uuid);

        if(!$registro_fiduciario)
            throw new Exception('Registro não encontrado');

        Gate::authorize('api-registros-partes', $registro_fiduciario);

        $partes = [];
        foreach ($registro_fiduciario->registro_fiduciario_parte as $registro_fiduciario_parte) {
            $partes[] = [
                "uuid" => $registro_fiduciario_parte->uuid,
                "tipo" => $registro_fiduciario_parte->tipo_parte_registro_fiduciario->codigo_tipo_parte_registro_fiduciario,
                "nome" => $registro_fiduciario_parte->no_parte,
                "cpf_cnpj" => $registro_fiduciario_parte->nu_cpf_cnpj
            ];
        }

        $response_json = [
            'partes' => $partes
        ];
        return response()->json($response_json, 200);
    }

    public function show($uuid, $parte_uuid)
    {
        $registro_fiduciario_parte = $this->RegistroFiduciarioParteServiceInterface->buscar_uuid($parte_uuid);

        if(!$registro_fiduciario_parte)
            throw new Exception('Parte não encontrada');

        $registro_fiduciario = $registro_fiduciario_parte->registro_fiduciario;

        Gate::authorize('api-registros-partes', $registro_fiduciario);

        switch ($registro_fiduciario_parte->no_estado_civil) {
            case 'Solteiro':
                $estado_civil = 1;
                break;
            case 'Casado':
                $estado_civil = 2;
                break;
            case 'Separado':
                $estado_civil = 3;
                break;
            case 'Separado judicialmente':
                $estado_civil = 4;
                break;
            case 'Divorciado':
                $estado_civil = 5;
                break;
            case 'Viúvo':
                $estado_civil = 6;
                break;
            case 'União estável':
                $estado_civil = 7;
                break;
        }

        if ($estado_civil>1) {
            switch ($registro_fiduciario_parte->no_regime_bens) {
                case 'Comunhão parcial de bens':
                    $regime_bens = 1;
                    break;
                case 'Comunhão universal de bens':
                    $regime_bens = 2;
                    break;
                case 'Separação total de bens':
                    $regime_bens = 3;
                    break;
                case 'Participação final nos aquestos':
                    $regime_bens = 4;
                    break;
            }
        }

        $parte = [
            'uuid' => $registro_fiduciario_parte->uuid,
            'tipo' => $registro_fiduciario_parte->tipo_parte_registro_fiduciario->codigo_tipo_parte_registro_fiduciario,
            'tipo_pessoa' => $registro_fiduciario_parte->tp_pessoa,
            'nome' => $registro_fiduciario_parte->no_parte,
            'cpf_cnpj' => $registro_fiduciario_parte->nu_cpf_cnpj,
            'emitir_certificado' => $registro_fiduciario_parte->in_emitir_certificado,
            'telefone_contato' => $registro_fiduciario_parte->nu_telefone_contato,
            'email_contato' => $registro_fiduciario_parte->no_email_contato,
            'estado_civil' => $estado_civil ?? NULL,
            'regime_bens' => $regime_bens ?? NULL,
            'data_casamento' => Helper::formata_data_hora($registro_fiduciario_parte->dt_casamento, 'Y-m-d'),
            'conjuge_ausente' => $registro_fiduciario_parte->in_conjuge_ausente,
            'cpf_conjuge' => Helper::pontuacao_cpf_cnpj($registro_fiduciario_parte->cpf_conjuge),
            'procuracao' => $registro_fiduciario_parte->uuid,
            'procuradores' => []
        ];

        if($registro_fiduciario_parte->registro_fiduciario_procurador) {
            foreach($registro_fiduciario_parte->registro_fiduciario_procurador as $procurador) {
                $parte['procuradores'][] = [
                    'uuid' => $procurador->uuid,
                    'nome' => $procurador->no_procurador,
                    'cpf' => $procurador->nu_cpf_cnpj,
                    'telefone_contato' => $procurador->nu_telefone_contato,
                    'email_contato' => $procurador->no_email_contato,
                    'emitir_certificado' => $procurador->in_emitir_certificado
                ];
            }
        }

        $response_json = [
            'parte' => $parte
        ];
        return response()->json($response_json, 200);
    }

}
