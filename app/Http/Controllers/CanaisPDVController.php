<?php
 
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Domain\CanaisPdv\Contracts\CanalPdvParceiroServiceInterface;

use App\Http\Requests\Configuracoes\CanaisPdv\StoreCanaisPdvParceiro;
use App\Http\Requests\Configuracoes\CanaisPdv\UpdateCanaisPdvParceiro;

use DB;
use Gate;
use stdClass;
use LogDB;
use Auth;
 
class CanaisPDVController extends Controller
{
    public function __construct(CanalPdvParceiroServiceInterface $CanalPdvParceiroServiceInterface)
    {
       $this->CanalPdvParceiroServiceInterface = $CanalPdvParceiroServiceInterface;
    }

    public function index(Request $request)
    {
        //Montagem dos filtros
        $filtros = new stdClass();
        $filtros->nome_canal_pdv_parceiro = $request->nome_canal_pdv_parceiro;
        $filtros->email_canal_pdv_parceiro = $request->email_canal_pdv_parceiro;
        $filtros->codigo_canal_pdv_parceiro = $request->codigo_canal_pdv_parceiro;
        $filtros->parceiro_canal_pdv_parceiro = $request->parceiro_canal_pdv_parceiro;
        $filtros->cnpj_canal_pdv_parceiro = preg_replace('#[^0-9]#', '', $request->cnpj_canal_pdv_parceiro);
 
        $todas_canais = $this->CanalPdvParceiroServiceInterface->listar($filtros);
        $todas_canais = $todas_canais->paginate(10, ['*'], 'pag');
        $todas_canais->appends(Request::capture()->except('_token'))->render();

        $listar_nome_pessoas_juridicas = $this->CanalPdvParceiroServiceInterface->listar_nome_pessoas_juridicas();

        $args = [
            'class' => $this,
            'todas_canais' => $todas_canais,
            'listar_nome_pessoas_juridicas' => $listar_nome_pessoas_juridicas
        ];


        return view('app.configuracoes.canais-pdv.geral-canais-pdv', $args);
    }

    public function novo_canal_pdv(Request $request)
    {
        return view('app.configuracoes.canais-pdv.geral-canais-pdv-novo');
    }

    public function salvar_canal_pdv_parceiro(StoreCanaisPdvParceiro $request)
    {
        Gate::authorize('novo-canal-pdv-parceiro');
        
        DB::beginTransaction();

        try {
            $args_canal = new stdClass(); 
            $args_canal->nome_canal_pdv_parceiro = $request->nome_canal_pdv_parceiro;
            $args_canal->email_canal_pdv_parceiro = $request->email_canal_pdv_parceiro;
            $args_canal->codigo_canal_pdv_parceiro = $request->codigo_canal_pdv_parceiro ?? 0000;
            $args_canal->parceiro_canal_pdv_parceiro = $request->parceiro_canal_pdv_parceiro;
            $args_canal->cnpj_canal_pdv_parceiro = preg_replace('#[^0-9]#', '', $request->cnpj_canal_pdv_parceiro);
            $args_canal->id_usuario_cad = Auth::User()->id_usuario;
           
            $novo_canal_pdv_parceiro = $this->CanalPdvParceiroServiceInterface->inserir($args_canal);

            DB::commit();

            LogDB::insere(
                Auth::User()->id_usuario,
                6,
                'Inseriu o novo canal pdv de parceiros'.$novo_canal_pdv_parceiro->nome_canal_pdv_parceiro.' com sucesso.',
                'Serventia',
                'N',
                request()->ip()
            );

            $response_json = [
                'status' => 'sucesso',
                'message' => 'O canal pdv parceiro foi salvo com sucesso.',
                'recarrega' => 'true'
            ];

            return response()->json($response_json, 200);


        }catch (Exception $e) {
            DB::rollback();

            LogDB::insere(
                Auth::User()->id_usuario,
                4,
                'Erro ao inserir canal pdv parceiro',
                'Registro',
                'N',
                request()->ip(),
                $e->getMessage() . ' na linha ' . $e->getLine() . ' do arquivo ' . $e->getFile()
            );

            $response_json = [
                'status' => 'alerta',
                'message' => 'Erro interno, tente novamente mais tarde. '.(config('app.env')!='production'?$e->getMessage() . ' na linha ' . $e->getLine() . ' do arquivo ' . $e->getFile():''),
                'regarrega' => 'false'
            ];
            return response()->json($response_json, 500);
        }
    }

    public function detalhes_canal_pdv(Request $request)
    {
        Gate::authorize('detalhes-canal-pdv-parceiro');

        $canal_pdv_parceiro = $this->CanalPdvParceiroServiceInterface->buscar($request->id_canal_pdv_parceiro);
                                                               
        $compact_args = [
            'canal_pdv_parceiro' => $canal_pdv_parceiro,
        ];

        return view('app.configuracoes.canais-pdv.geral-canais-pdv-detalhes', $compact_args);
    }

    public function editar_canal_pdv(Request $request) 
    {
        Gate::authorize('novo-canal-pdv-parceiro');

        $canal_pdv_parceiro = $this->CanalPdvParceiroServiceInterface->buscar($request->id_canal_pdv_parceiro);
                                                               
        $compact_args = [
            'canal_pdv_parceiro' => $canal_pdv_parceiro,
        ];

        return view('app.configuracoes.canais-pdv.geral-canais-pdv-editar', $compact_args);
    }

    public function alterar_canal_pdv(UpdateCanaisPdvParceiro $request) 
    {
        Gate::authorize('novo-canal-pdv-parceiro');

        DB::beginTransaction();

        try {

            $canal_pdv_parceiro = $this->CanalPdvParceiroServiceInterface->buscar($request->id_canal_pdv_parceiro);
            
            $args_canal = new stdClass(); 
            $args_canal->nome_canal_pdv_parceiro = $request->nome_canal_pdv_parceiro;
            $args_canal->email_canal_pdv_parceiro = $request->email_canal_pdv_parceiro;
            $args_canal->codigo_canal_pdv_parceiro = $request->codigo_canal_pdv_parceiro ?? 0000;
            $args_canal->parceiro_canal_pdv_parceiro = $request->parceiro_canal_pdv_parceiro;
            $args_canal->cnpj_canal_pdv_parceiro = preg_replace('#[^0-9]#', '', $request->cnpj_canal_pdv_parceiro);
            $args_canal->id_usuario_cad = Auth::User()->id_usuario;
            $canal_pdv_parceiro = $this->CanalPdvParceiroServiceInterface->alterar($canal_pdv_parceiro , $args_canal);

            DB::commit();

            LogDB::insere(
                Auth::User()->id_usuario,
                6,
                'Alterou o canal pdv de parceiro com sucesso.',
                'Canal Pdv Parceiro',
                'N',
                request()->ip()
            );

            $response_json = [
                'status' => 'sucesso',
                'message' => 'O canal pdv parceiro foi alterado com sucesso.',
                'recarrega' => 'true'
            ];

            return response()->json($response_json, 200);

        }catch (Exception $e) {
            DB::rollback();

            LogDB::insere(
                Auth::User()->id_usuario,
                4,
                'Erro ao alterar canal pdv parceiro',
                'Canal Pdv Parceiro',
                'N',
                request()->ip(),
                $e->getMessage() . ' na linha ' . $e->getLine() . ' do arquivo ' . $e->getFile()
            );

            $response_json = [
                'status' => 'alerta',
                'message' => 'Erro interno, tente novamente mais tarde. '.(config('app.env')!='production'?$e->getMessage() . ' na linha ' . $e->getLine() . ' do arquivo ' . $e->getFile():''),
                'regarrega' => 'false'
            ];
            return response()->json($response_json, 500);
        }
        
    }

    public function desativar_canal_pdv(Request $request) 
    {
        Gate::authorize('novo-canal-pdv-parceiro');

        DB::beginTransaction();

        try {

            $canal_pdv_parceiro = $this->CanalPdvParceiroServiceInterface->buscar($request->id_canal_pdv_parceiro);
            $this->CanalPdvParceiroServiceInterface->desativar($canal_pdv_parceiro);

            DB::commit();

            LogDB::insere(
                Auth::User()->id_usuario,
                6,
                'Desativou o canal pdv de parceiro com sucesso.',
                'Canal Pdv Parceiro',
                'N',
                request()->ip()
            );

            $response_json = [
                'status' => 'sucesso',
                'message' => 'O canal pdv parceiro foi desativado com sucesso.',
                'recarrega' => 'true'
            ];

            return response()->json($response_json, 200);

        }catch (Exception $e) {
            DB::rollback();

            LogDB::insere(
                Auth::User()->id_usuario,
                4,
                'Erro ao desativar canal pdv parceiro',
                'Canal Pdv Parceiro',
                'N',
                request()->ip(),
                $e->getMessage() . ' na linha ' . $e->getLine() . ' do arquivo ' . $e->getFile()
            );

            $response_json = [
                'status' => 'alerta',
                'message' => 'Erro interno, tente novamente mais tarde. '.(config('app.env')!='production'?$e->getMessage() . ' na linha ' . $e->getLine() . ' do arquivo ' . $e->getFile():''),
                'regarrega' => 'false'
            ];
            return response()->json($response_json, 500);
        }
    }

    public function registro_canal_pdv(Request $request) 
    {
        if ($request->id_canal_pdv_parceiro>0) {
            $canal_pdv_parceiro = $this->CanalPdvParceiroServiceInterface->buscar($request->id_canal_pdv_parceiro);
            return response()->json($canal_pdv_parceiro);
        }
    }

}