<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use stdClass;
use Gate;
use Auth;
use DB;
use Helper;
use LogDB;

use App\Exceptions\RegdocException;

use App\Domain\VScore\Contracts\VScoreTransacaoLoteServiceInterface;
use App\Domain\VScore\Contracts\VScoreTransacaoServiceInterface;

use App\Http\Requests\API\StoreBiometriaLote;

use App\Jobs\BiometriaConsultarVScore;
use App\Jobs\BiometriaConsultarFinalizar;
use App\Jobs\BiometriaConsultarNotificacao;

class BiometriaLoteController extends Controller
{
    /**
     * @var VScoreTransacaoLoteServiceInterface
     * @var VScoreTransacaoServiceInterface
     *
     */
    protected $VScoreTransacaoLoteServiceInterface;
    protected $VScoreTransacaoServiceInterface;

    /**
     * RegistroController constructor.
     * @param VScoreTransacaoLoteServiceInterface $VScoreTransacaoLoteServiceInterface
     * @param VScoreTransacaoServiceInterface $VScoreTransacaoServiceInterface
     *
     */
    public function __construct(VScoreTransacaoLoteServiceInterface $VScoreTransacaoLoteServiceInterface,
        VScoreTransacaoServiceInterface $VScoreTransacaoServiceInterface)
    {
        parent::__construct();
        $this->VScoreTransacaoLoteServiceInterface = $VScoreTransacaoLoteServiceInterface;
        $this->VScoreTransacaoServiceInterface = $VScoreTransacaoServiceInterface;
    }

    public function index(Request $request)
    {
        $filtros = new stdClass();

        switch (Auth::User()->pessoa_ativa->id_tipo_pessoa) {
            case 1:
            case 13:
                break;
            default:
                $filtros->id_pessoa = Auth::User()->pessoa_ativa->id_pessoa;
            break;
        }

        $vscore_transacao_lotes = $this->VScoreTransacaoLoteServiceInterface->listar($filtros);
        $vscore_transacao_lotes = $vscore_transacao_lotes->get();

        $lotes = [];
        foreach ($vscore_transacao_lotes as $vscore_transacao_lote) {
            $lotes[] = [
                'uuid' => $vscore_transacao_lote->uuid,
                'situacao' => ($vscore_transacao_lote->in_completado == 'S' ? 'finalizado' : 'processando'),
                'data_cadastro' => Helper::formata_data_hora($vscore_transacao_lote->dt_cadastro),
                'data_finalizacao' => ($vscore_transacao_lote->dt_finalizacao ? Helper::formata_data_hora($vscore_transacao_lote->dt_finalizacao) : NULL),
            ];
        }

        $response_json = [
            'lotes' => $lotes
        ];
        return response()->json($response_json, 200);
    }

    public function store(StoreBiometriaLote $request)
    {
        Gate::authorize('api-biometria-lote-novo');


        DB::beginTransaction();

        try {
            $args_novo_lote = new stdClass();
            $args_novo_lote->url_notificacao = $request->url_notificacao;

            $vscore_transacao_lote = $this->VScoreTransacaoLoteServiceInterface->inserir($args_novo_lote);

            $cpfs = [];
            foreach ($request->cpfs as $cpf) {
                $cpf = Helper::somente_numeros($cpf);

                if(in_array($cpf, $cpfs))
                    throw new RegdocException('Existe um ou mais CPF(s) duplicados no lote.');

                $cpfs[] = $cpf;

                $args_nova_transacao = new stdClass();
                $args_nova_transacao->id_vscore_transacao_lote = $vscore_transacao_lote->id_vscore_transacao_lote;
                $args_nova_transacao->id_vscore_transacao_situacao = config('constants.VSCORE.SITUACOES.AGUARDANDO_PROCESSAMENTO');
                $args_nova_transacao->nu_cpf_cnpj = $cpf;

                $vscore_transacao = $this->VScoreTransacaoServiceInterface->inserir($args_nova_transacao);

                BiometriaConsultarVScore::dispatch($vscore_transacao);
            }
            
            DB::commit();

            LogDB::insere(
                Auth::User()->id_usuario,
                6,
                'API - O lote de consulta de biometria foi inserido com sucesso.',
                'Consultar Biometria - Lote',
                'N',
                 request()->ip()
            );

            $response_json = [
                'uuid' => $vscore_transacao_lote->uuid,
                'message' => 'O lote de consulta de biometria foi inserido com sucesso.'
            ];

            return response()->json($response_json, 200);

        } catch (RegdocException $e) {
            DB::rollBack();

            LogDB::insere(
                Auth::User()->id_usuario,
                4,
                'API - Error ao salvar o lote de consulta de biometria (CPFs Duplicados).',
                'Consultar Biometria - Lote',
                'N',
                request()->ip(),
                $e->getMessage() . ' na linha ' . $e->getLine() . ' do arquivo ' . $e->getFile()
            );

            $response_json = [
                'message' => $e->getMessage(),
            ];
            return response()->json($response_json, 400);
        } catch (Exception $e) {
            DB::rollBack();

            LogDB::insere(
                Auth::User()->id_usuario,
                4,
                'API - Error ao salvar o lote de consulta de biometria.',
                'Consultar Biometria - Lote',
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

    public function show($uuid)
    {
        $vscore_transacao_lote = $this->VScoreTransacaoLoteServiceInterface->buscar_uuid($uuid);

        $finalizadas = $vscore_transacao_lote->vscore_transacoes()
        ->where('id_vscore_transacao_situacao', config('constants.VSCORE.SITUACOES.FINALIZADO'))
        ->count();
    
        $processando = $vscore_transacao_lote->vscore_transacoes()
            ->where('id_vscore_transacao_situacao', config('constants.VSCORE.SITUACOES.PROCESSANDO'))
            ->count();
        
        $erros = $vscore_transacao_lote->vscore_transacoes()
            ->where('id_vscore_transacao_situacao', config('constants.VSCORE.SITUACOES.ERRO'))
            ->count();
            
        $retorno = [
            'situacao' => ($vscore_transacao_lote->in_completado == 'S' ? 'finalizado' : 'processando'),
            'data_cadastro' => Helper::formata_data_hora($vscore_transacao_lote->dt_cadastro),
            'data_finalizacao' => ($vscore_transacao_lote->dt_finalizacao ? Helper::formata_data_hora($vscore_transacao_lote->dt_finalizacao) : NULL),
            'totais' => [
                'consultas' => count($vscore_transacao_lote->vscore_transacoes),
                'erros' => $erros,
                'processando' => $processando,
                'finalizadas' => $finalizadas,
            ],
            'cpfs' => []
        ];

        foreach ($vscore_transacao_lote->vscore_transacoes as $vscore_transacao) {
            $retorno['cpfs'][] = [
                'cpf' => $vscore_transacao->nu_cpf_cnpj,
                'situacao' => [
                    'codigo' => $vscore_transacao->vscore_transacao_situacao->id_vscore_transacao_situacao,
                    'descricao' => $vscore_transacao->vscore_transacao_situacao->no_vscore_transacao_situacao
                ],
                'resultado_biometria' => $vscore_transacao->in_biometria_cpf ?? false
            ];
        }

        return response()->json($retorno, 200);
    }
}
