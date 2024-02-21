<?php

namespace App\Http\Controllers\Biometria;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use stdClass;
use Helper;
use Gate;
use Auth;
use VALIDScore;
use LogDB;
use DB;

use App\Domain\VScore\Contracts\VScoreTransacaoServiceInterface;
use App\Domain\VScore\Contracts\VScoreTransacaoSituacaoServiceInterface;

use App\Jobs\BiometriaConsultarVScore;

class BiometriaController extends Controller
{

    protected $VScoreTransacaoServiceInterface;

    public function __construct(VScoreTransacaoServiceInterface $VScoreTransacaoServiceInterface,
        VScoreTransacaoSituacaoServiceInterface $VScoreTransacaoSituacaoServiceInterface)
    {
        parent::__construct();
        $this->VScoreTransacaoServiceInterface = $VScoreTransacaoServiceInterface;
        $this->VScoreTransacaoSituacaoServiceInterface = $VScoreTransacaoSituacaoServiceInterface;
    }

    /**
     * Retorna a tela de lista de consultas de biometria
     *
     * @param Request $request
     * @return view
     */
    public function index(Request $request)
    {
        $filtros = new stdClass();
        $filtros->nu_cpf_cnpj = $request->nu_cpf_cnpj;
        $filtros->data_cadastro_ini = $request->data_cadastro_ini;
        $filtros->data_cadastro_fim = $request->data_cadastro_fim;
        $filtros->id_vscore_transacao_situacao = $request->id_vscore_transacao_situacao;
        $filtros->in_biometria_cpf = $request->in_biometria_cpf;

        switch (Auth::User()->pessoa_ativa->id_tipo_pessoa) {
            case 1:
            case 13:
                break;
            default:
                $filtros->id_pessoa = Auth::User()->pessoa_ativa->id_pessoa;
            break;
        }        

        $vscore_transacoes = $this->VScoreTransacaoServiceInterface->listar($filtros);                
        $vscore_transacoes = $vscore_transacoes->paginate(10, ['*'], 'pag');

        $vscore_transacao_situacoes = $this->VScoreTransacaoSituacaoServiceInterface->listar();

        $compact_args = [
            'vscore_transacoes' => $vscore_transacoes,
            'vscore_transacao_situacoes' => $vscore_transacao_situacoes
        ];
        return view('app.produtos.biometria.geral-biometria', $compact_args);
    }

    /**
     * Retorna a tela de consulta de biometria
     *
     * @param Request $request
     * @return view
     */
    public function create(Request $request)
    {
        return view('app.produtos.biometria.geral-biometria-consulta');
    }

    /**
     * Retorna a tela de resultado de biometria
     *
     * @param Request $request
     * @return view
     */
    public function show(Request $request)
    {
        $vscore_transacao = $this->VScoreTransacaoServiceInterface->buscar_uuid($request->biometria);

        Gate::authorize('consultar-biometria-resultado', $vscore_transacao);

        $compact_args = [
            'vscore_transacao' => $vscore_transacao,
        ];
        return view('app.produtos.biometria.geral-biometria-detalhes', $compact_args);
    }

    /**
     * Reprocessar uma biometria
     *
     * @param Request $request
     * @return view
     */
    public function reprocessar(Request $request)
    {
        $vscore_transacao = $this->VScoreTransacaoServiceInterface->buscar_uuid($request->biometria);

        Gate::authorize('consultar-biometria-reprocessar', $vscore_transacao);

        DB::beginTransaction();

        try {
            $args_alterar_transacao = new stdClass();
            $args_alterar_transacao->id_vscore_transacao_situacao = config('constants.VSCORE.SITUACOES.AGUARDANDO_PROCESSAMENTO');

            $this->VScoreTransacaoServiceInterface->alterar($vscore_transacao, $args_alterar_transacao);

            BiometriaConsultarVScore::dispatch($vscore_transacao);

            DB::commit();

            LogDB::insere(
                Auth::User()->id_usuario,
                6,
                'Inicou o reprocessamento da consulta de biometria '.$vscore_transacao->uuid.'.',
                'Consultar Biometria',
                'N',
                request()->ip()
            );

            $response_json = [
                'status' => 'sucesso',
                'message' => 'A biometria será reprocessada, aguarde alguns minutos e atualize a página para consultar a nova situação.',
                'recarrega' => 'true'
            ];
            return response()->json($response_json, 200);
        } catch (Exception $e) {
            DB::rollBack();

            LogDB::insere(
                Auth::User()->id_usuario,
                4,
                'Erro ao iniciar o reprocessamento da consulta de biometria '.$vscore_transacao->uuid.'.',
                'Consultar Biometria',
                'N',
                request()->ip(),
                $e->getMessage() . ' na linha ' . $e->getLine() . ' do arquivo ' . $e->getFile()
            );

            $response_json = [
                'message' => 'Erro interno, tente novamente mais tarde. '.(config('app.env')!='production'?$e->getMessage() . ' na linha ' . $e->getLine() . ' do arquivo ' . $e->getFile():''),
            ];
            return response()->json($response_json, 500);
        }        
    }
    
    /**
     * Realiza a consulta de biometria no VScore
     * 
     * @param Request $request
     * @return json
     */
    public function consultar_biometria(Request $request)
    {
        $cpf = Helper::somente_numeros($request->cpf);

        $consulta = VALIDScore::consultar_vscore($cpf);

        $enviado_consulta = $consulta['enviado'];
        $response_consulta = $consulta['response'];

        $args_nova_transacao = new stdClass();
        $args_nova_transacao->id_vscore_transacao_situacao = config('constants.VSCORE.SITUACOES.PROCESSANDO');
        $args_nova_transacao->nu_transacao_vscore = $response_consulta['data']['subjectUuid'];
        $args_nova_transacao->co_momento = 'vscore';
        $args_nova_transacao->nu_cpf_cnpj = $cpf;
        $args_nova_transacao->de_enviado_vscore = $enviado_consulta;

        $vscore_transacao = $this->VScoreTransacaoServiceInterface->inserir($args_nova_transacao);

        $response_json = [
            'uuid' => $vscore_transacao->uuid,
            'status' => 'processando'
        ];
        return response()->json($response_json, 200);
    }
    
    /**
     * consultar_biometria_segundabase
     * Realiza  a consulta de biometria no DataValid
     * 
     * @param Request $request
     * @return json
     */
    public function consultar_biometria_segundabase(Request $request)
    {
        $vscore_transacao = $this->VScoreTransacaoServiceInterface->buscar_uuid($request->uuid);

        $consulta = VALIDScore::consultar_dvalid($vscore_transacao->nu_cpf_cnpj);

        $enviado_consulta = $consulta['enviado'];
        $response_consulta = $consulta['response'];

        $args_alterar_transacao = new stdClass();
        $args_alterar_transacao->co_momento = 'datavalid';
        $args_alterar_transacao->nu_transacao_dvalid = $response_consulta['data']['subjectUuid'];
        $args_alterar_transacao->de_enviado_dvalid = $enviado_consulta;
        
        $this->VScoreTransacaoServiceInterface->alterar($vscore_transacao, $args_alterar_transacao);

        $response_json = [
            'uuid' => $vscore_transacao->uuid,
            'status' => 'processando'
        ];
        return response()->json($response_json, 200);
    }
    
    /**
     * Consulta o status da transação e chama a função de validação
     * 
     * @param Request $request
     * @return json
     */
    function consultar_status(Request $request)
    {
        $vscore_transacao = $this->VScoreTransacaoServiceInterface->buscar_uuid($request->uuid);

        $biometria = false;
        switch ($vscore_transacao->co_momento) {
            case 'vscore':
                $status = VALIDScore::consultar_status($vscore_transacao->nu_transacao_vscore);
                break;
            case 'datavalid':
                $status = VALIDScore::consultar_status($vscore_transacao->nu_transacao_dvalid);
                break;
        }

        if ($status['data']['status'] == 'Em processamento') {
            $response_json = [
                'uuid' => $vscore_transacao->uuid,
                'status' => 'processando'
            ];
            return response()->json($response_json, 200);
        } else {
            $args_alterar_transacao = new stdClass();

            switch ($vscore_transacao->co_momento) {
                case 'vscore':
                    $args_alterar_transacao->de_resultado_vscore = json_encode($status);

                    if (isset($status['data']['vscore'])) {
                        $value = $status['data']['vscore']['biometric'];
                        if ($value != NULL && $value > 0) {
                            $biometria = true;
                        }
                    }
                    break;
                case 'datavalid':
                    $args_alterar_transacao->de_resultado_dvalid = json_encode($status);
                    $args_alterar_transacao->id_vscore_transacao_situacao = config('constants.VSCORE.SITUACOES.FINALIZADO');

                    $biometria = $status['data']['vscore']['biometric'] > 0 ?? false;
                    break;
            }

            if ($biometria===true) {
                $args_alterar_transacao->id_vscore_transacao_situacao = config('constants.VSCORE.SITUACOES.FINALIZADO');
                $args_alterar_transacao->in_biometria_cpf = $biometria;
            }
          
            $this->VScoreTransacaoServiceInterface->alterar($vscore_transacao, $args_alterar_transacao);
        }

        $response_json = [
            'uuid' => $vscore_transacao->uuid,
            'biometria' => $biometria,
            'url_resultado' => route('app.produtos.biometrias.show', $vscore_transacao->uuid),
            'status' => 'sucesso'
        ];
        return response()->json($response_json, 200);
    }
}
