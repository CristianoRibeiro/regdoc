<?php

namespace App\Http\Controllers\API;

use App\Domain\Parte\Contracts\ParteEmissaoCertificadoServiceInterface;

use App\Helpers\LogDB;
use App\Helpers\Helper;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\CertificadoraRequest;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

use Exception;

use stdClass;
use Symfony\Component\HttpFoundation\Response;

final class CertificadoraController extends Controller
{
    private ParteEmissaoCertificadoServiceInterface $ParteEmissaoCertificadoService;

    public function __construct(ParteEmissaoCertificadoServiceInterface $ParteEmissaoCertificadoService)
    {
        $this->ParteEmissaoCertificadoService = $ParteEmissaoCertificadoService;
    }


    public function sistemasul(Request $request) // TODO: Remover quando o metodo update passar a ser usado pela Sistemasul
    {
        $request = json_decode($request->getContent(), true);
        LogDB::insere(1,4, 'API Sistema Sul', 'Webhook Sistema Sul', 'S', null, json_encode($request));

        $id_situacao = match($request['situacao_id']) {
            'S3', 'S4'  => config('constants.PARTE_EMISSAO_CERTIFICADO.SITUACAO.PROBLEMA'),
            'S5', 'S6'  => config('constants.PARTE_EMISSAO_CERTIFICADO.SITUACAO.AGENDADO'),
            'S20'  => config('constants.PARTE_EMISSAO_CERTIFICADO.SITUACAO.EMITIDO'),
            'S21'  => config('constants.PARTE_EMISSAO_CERTIFICADO.SITUACAO.ATENDIMENTO_PRIORITARIO'),
            default => null
        };

        if(!$id_situacao) return response()->json(['error' => 'situacao_id não permitido.'], Response::HTTP_UNPROCESSABLE_ENTITY);

        $parte = $this->ParteEmissaoCertificadoService->buscar_cpf_cnpj(Helper::somente_numeros($request['cpf_cnpj']));
        if(!$parte) return response()->json(['error' => 'Parte não encontrada'], 404);

        $args_emissao_certificado = new stdClass();
        $args_emissao_certificado->id_parte_emissao_certificado_situacao = $id_situacao;
        $args_emissao_certificado->dt_situacao = Carbon::now();
        $args_emissao_certificado->in_atualizacao_automatica = 'N';
        if($request['situacao_id'] == 'S20'){
            $args_emissao_certificado->dt_emissao = array_key_exists('dt_emissao', $request) ? Helper::formata_data_hora($request['dt_emissao'], 'Y-m-d H:i:s') : NULL;
            $args_emissao_certificado->hr_emissao = array_key_exists('dt_emissao', $request) ? Helper::formata_hora($request['dt_emissao']) : NULL;
        }
        if($request['situacao_id'] == 'S5' || $request['situacao_id'] == 'S6'){
            $args_emissao_certificado->dt_agendamento = array_key_exists('dt_agendamento', $request) ? Helper::formata_data_hora($request['dt_agendamento'], 'Y-m-d H:i:s') : '';
            $args_emissao_certificado->hr_agendado = array_key_exists('dt_agendamento', $request) ? Helper::formata_hora($request['dt_agendamento']) : '';
        }

        $this->ParteEmissaoCertificadoService->alterar($parte, $args_emissao_certificado);

        return response()->json(['success' => true], 200);
    }

    public function update(CertificadoraRequest $request)
    {
        Gate::authorize('certificadora-update');

        $id_situacao = match($request->situacao_id) {
            'S3', 'S4'  => config('constants.PARTE_EMISSAO_CERTIFICADO.SITUACAO.PROBLEMA'),
            'S5', 'S6'  => config('constants.PARTE_EMISSAO_CERTIFICADO.SITUACAO.AGENDADO'),
            'S20'  => config('constants.PARTE_EMISSAO_CERTIFICADO.SITUACAO.EMITIDO'),
            'S21'  => config('constants.PARTE_EMISSAO_CERTIFICADO.SITUACAO.ATENDIMENTO_PRIORITARIO')
        };

        $parte = $this->ParteEmissaoCertificadoService->buscar_cpf_cnpj($request->cpf_cnpj);

        $args_emissao_certificado = new stdClass();
        $args_emissao_certificado->id_parte_emissao_certificado_situacao = $id_situacao;
        $args_emissao_certificado->dt_situacao = Carbon::now();
        $args_emissao_certificado->in_atualizacao_automatica = 'N';
        if($request->situacao_id === 'S20') {
            $args_emissao_certificado->dt_emissao = $request->dt_emissao ? Helper::formata_data_hora($request->dt_emissao, 'Y-m-d H:i:s') : NULL;
        } else if($request->situacao_id === 'S5' || $request->situacao_id === 'S6') {
            $args_emissao_certificado->dt_agendamento = $request->dt_agendamento ? Helper::formata_data_hora($request->dt_agendamento, 'Y-m-d H:i:s') : '';
        }

        $this->ParteEmissaoCertificadoService->alterar($parte, $args_emissao_certificado);

        LogDB::insere(1,4, 'API Sistema Sul', 'Webhook Sistema Sul', 'S', null, json_encode($request));

        return response()->json(['success' => true], 200);
    }
}