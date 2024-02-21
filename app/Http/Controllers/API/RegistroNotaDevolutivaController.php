<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Storage;
use LogDB;
use Gate;
use Helper;

use App\Domain\RegistroFiduciario\Contracts\RegistroFiduciarioServiceInterface;

class RegistroNotaDevolutivaController extends Controller
{
    /**
     * @var RegistroFiduciarioServiceInterface
     */

    protected $RegistroFiduciarioServiceInterface;

    /**
     * @param RegistroFiduciarioServiceInterface $RegistroFiduciarioServiceInterface
    */

    public function __construct(RegistroFiduciarioServiceInterface $RegistroFiduciarioServiceInterface)
    {
        $this->RegistroFiduciarioServiceInterface = $RegistroFiduciarioServiceInterface;
    }

    public function index($uuid)
    {
        $registro_fiduciario = $this->RegistroFiduciarioServiceInterface->buscar_uuid($uuid);

        if(!$registro_fiduciario)
            throw new Exception('Registro não encontrado');

        Gate::authorize('api-registros-notas-devolutivas', $registro_fiduciario);

        $notas = [];
        foreach ($registro_fiduciario->registro_fiduciario_nota_devolutivas as $key => $registro_fiduciario_nota_devolutiva) {
            $notas[$key] = [
                'uuid' => $registro_fiduciario_nota_devolutiva->uuid,
                'situacao' => $registro_fiduciario_nota_devolutiva->id_registro_fiduciario_nota_devolutiva_situacao,
                'observacoes' => $registro_fiduciario_nota_devolutiva->de_nota_devolutiva
            ];

            if ($registro_fiduciario_nota_devolutiva->nota_devolutiva_cumprimento) {
                $notas[$key]['cumprimento'] = [
                    'codigo' => $registro_fiduciario_nota_devolutiva->nota_devolutiva_cumprimento->co_nota_devolutiva_cumprimento,
                    'descricao' => $registro_fiduciario_nota_devolutiva->nota_devolutiva_cumprimento->no_nota_devolutiva_cumprimento
                ];                
            }

            if ($registro_fiduciario_nota_devolutiva->nota_devolutiva_nota_devolutiva_causa_raiz) {
                foreach ($registro_fiduciario_nota_devolutiva->nota_devolutiva_nota_devolutiva_causa_raiz as $nota_devolutiva_nota_devolutiva_causa_raiz) {
                    $nota_devolutiva_causa_raiz = $nota_devolutiva_nota_devolutiva_causa_raiz->nota_devolutiva_causa_raiz;
                    $notas[$key]['causas_raizes'][] = [
                        'codigo' => $nota_devolutiva_causa_raiz->co_nota_devolutiva_causa_raiz,
                        'descricao' => $nota_devolutiva_causa_raiz->no_nota_devolutiva_causa_raiz,
                        'data_hora' => Helper::formata_data_hora($nota_devolutiva_nota_devolutiva_causa_raiz->dt_cadastro, 'Y-m-d H:i:s'),
                        'grupo' => [
                            'codigo' => $nota_devolutiva_causa_raiz->nota_devolutiva_causa_grupo->co_nota_devolutiva_causa_grupo,
                            'descricao' => $nota_devolutiva_causa_raiz->nota_devolutiva_causa_grupo->no_nota_devolutiva_causa_grupo,
                            'classificacao' => [
                                'codigo' => $nota_devolutiva_causa_raiz->nota_devolutiva_causa_grupo->nota_devolutiva_causa_classificacao->co_nota_devolutiva_causa_classificacao,
                                'descricao' => $nota_devolutiva_causa_raiz->nota_devolutiva_causa_grupo->nota_devolutiva_causa_classificacao->no_nota_devolutiva_causa_classificacao,
                            ]
                        ]
                    ];
                }              
            }

            foreach ($registro_fiduciario_nota_devolutiva->arquivos_grupo as $arquivo) {
                $storagepath = 'public'.$arquivo->no_local_arquivo.'/'.$arquivo->no_arquivo;

                $notas[$key]['arquivos'][] = [
                    'uuid' => $arquivo->uuid,
                    'nome' => $arquivo->no_descricao_arquivo,
                    'tamanho' => intval($arquivo->nu_tamanho_kb),
                    'extensao' => $arquivo->no_extensao
                ];
            }
        }

        $response_json = [
            'notas' => $notas
        ];
        return response()->json($response_json, 200);
    }

}
