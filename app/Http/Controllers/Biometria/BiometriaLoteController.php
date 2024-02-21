<?php

namespace App\Http\Controllers\Biometria;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use stdClass;
use Auth;
use Gate;
use DB;
use LogDB;

use App\Domain\VScore\Contracts\VScoreTransacaoServiceInterface;
use App\Domain\VScore\Contracts\VScoreTransacaoLoteServiceInterface;

use App\Jobs\BiometriaConsultarVScore;
use App\Jobs\BiometriaConsultarNotificacao;

class BiometriaLoteController extends Controller
{
    protected $VScoreTransacaoServiceInterface;
    protected $VScoreTransacaoLoteServiceInterface;

    public function __construct(VScoreTransacaoServiceInterface $VScoreTransacaoServiceInterface,
        VScoreTransacaoLoteServiceInterface $VScoreTransacaoLoteServiceInterface)
    {
        parent::__construct();
        $this->VScoreTransacaoServiceInterface = $VScoreTransacaoServiceInterface;
        $this->VScoreTransacaoLoteServiceInterface = $VScoreTransacaoLoteServiceInterface;
    }

    /**
     * Retorna a tela de consulta de lotes de biometria
     *
     * @param Request $request
     * @return view
     */
    public function index(Request $request)
    {
        $filtros = new stdClass();
        $filtros->uuid = $request->uuid;
        $filtros->data_cadastro_ini = $request->data_cadastro_ini;
        $filtros->data_cadastro_fim = $request->data_cadastro_fim;
        $filtros->data_finalizacao_ini = $request->data_finalizacao_ini;
        $filtros->data_finalizacao_fim = $request->data_finalizacao_fim;
        $filtros->in_completado = $request->in_completado;

        switch (Auth::User()->pessoa_ativa->id_tipo_pessoa) {
            case 1:
            case 13:
                break;
            default:
                $filtros->id_pessoa = Auth::User()->pessoa_ativa->id_pessoa;
            break;
        }

        $vscore_transacao_lotes = $this->VScoreTransacaoLoteServiceInterface->listar($filtros);                
        $vscore_transacao_lotes = $vscore_transacao_lotes->paginate(10, ['*'], 'pag');

        $compact_args = [
            'vscore_transacao_lotes' => $vscore_transacao_lotes
        ];
        return view('app.produtos.biometria.lote.geral-biometria-lote', $compact_args);
    }

    /**
     * Exibe os detalhes de um lote de biometria
     * @param Request $request
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function show(Request $request)
    {
        $vscore_transacao_lote = $this->VScoreTransacaoLoteServiceInterface->buscar_uuid($request->biometria_lote);

        // Argumentos para o retorno da view
        $compact_args = [
            'vscore_transacao_lote' => $vscore_transacao_lote
        ];
        return view('app.produtos.biometria.lote.geral-biometria-lote-detalhes', $compact_args);
    }

    /**
     * Reprocessar biometrias com erro de um lote
     *
     * @param Request $request
     * @return view
     */
    public function reprocessar(Request $request)
    {
        $vscore_transacao_lote = $this->VScoreTransacaoLoteServiceInterface->buscar_uuid($request->biometria_lote);

        Gate::authorize('consultar-biometria-lote-reprocessar', $vscore_transacao_lote);

        DB::beginTransaction();

        try {
            foreach ($vscore_transacao_lote->vscore_transacoes_erro as $vscore_transacao) {
                $args_alterar_transacao = new stdClass();
                $args_alterar_transacao->id_vscore_transacao_situacao = config('constants.VSCORE.SITUACOES.AGUARDANDO_PROCESSAMENTO');

                $this->VScoreTransacaoServiceInterface->alterar($vscore_transacao, $args_alterar_transacao);

                BiometriaConsultarVScore::dispatch($vscore_transacao);
            }

            DB::commit();

            LogDB::insere(
                Auth::User()->id_usuario,
                6,
                'Iniciou o reprocessamento de consultas de biometria com erro do lote '.$vscore_transacao_lote->uuid.'.',
                'Consultar Biometria - Lote',
                'N',
                request()->ip()
            );

            $response_json = [
                'status' => 'sucesso',
                'message' => 'As biometrias com erro deste lote serão reprocessadas, aguarde alguns minutos e atualize a página para consultar as novas situações.',
                'recarrega' => 'true'
            ];
            return response()->json($response_json, 200);
        } catch (Exception $e) {
            DB::rollBack();

            LogDB::insere(
                Auth::User()->id_usuario,
                4,
                'Erro ao iniciar o reprocessamento de consultas de biometria com erro do lote '.$vscore_transacao_lote->uuid.'.',
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

    /**
     * Reenvia a notificação de finalização do lote de biometrias
     *
     * @param Request $request
     * @return view
     */
    public function reenviar_notificacao(Request $request)
    {
        $vscore_transacao_lote = $this->VScoreTransacaoLoteServiceInterface->buscar_uuid($request->biometria_lote);

        Gate::authorize('consultar-biometria-lote-reenviar-notificacao', $vscore_transacao_lote);

        DB::beginTransaction();

        try {
            BiometriaConsultarNotificacao::dispatch($vscore_transacao_lote);

            DB::commit();

            LogDB::insere(
                Auth::User()->id_usuario,
                6,
                'Reenviou a notificação de finalização do lote '.$vscore_transacao_lote->uuid.'.',
                'Consultar Biometria - Lote',
                'N',
                request()->ip()
            );

            $response_json = [
                'status' => 'sucesso',
                'message' => 'Notificação de finalização reenviada com sucesso.',
                'recarrega' => 'true'
            ];
            return response()->json($response_json, 200);
        } catch (Exception $e) {
            DB::rollBack();

            LogDB::insere(
                Auth::User()->id_usuario,
                4,
                'Erro ao reenviar a notificação de finalização do lote '.$vscore_transacao_lote->uuid.'.',
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
}
