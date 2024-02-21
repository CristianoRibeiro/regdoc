<?php
 
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Domain\Estado\Contracts\EstadoServiceInterface;
use App\Domain\Estado\Contracts\CidadeServiceInterface;
use App\Domain\Pessoa\Contracts\PessoaServiceInterface;
use App\Domain\Serventia\Contracts\ServentiaServiceInterface;

use App\Http\Requests\Configuracoes\Serventias\StoreServentia;
use App\Http\Requests\Configuracoes\Serventias\UpdateServentia;

use App\Models\tipo_serventia;
use App\Models\endereco;
use App\Models\pessoa_endereco;
use App\Models\pessoa;

use DB;
use Gate;
use stdClass;
use LogDB;
use Auth;
use Exception;
 
class ServentiasController extends Controller
{
    private EstadoServiceInterface $EstadoServiceInterface;
    private CidadeServiceInterface $CidadeServiceInterface;
    private PessoaServiceInterface $PessoaServiceInterface;
    private ServentiaServiceInterface $ServentiaServiceInterface;
    
    public function __construct(EstadoServiceInterface $EstadoServiceInterface, 
                                CidadeServiceInterface $CidadeServiceInterface, 
                                PessoaServiceInterface $PessoaServiceInterface,
                                ServentiaServiceInterface $ServentiaServiceInterface)
    {
       $this->EstadoServiceInterface = $EstadoServiceInterface;
       $this->CidadeServiceInterface = $CidadeServiceInterface;
       $this->PessoaServiceInterface = $PessoaServiceInterface;
       $this->ServentiaServiceInterface = $ServentiaServiceInterface;
    }

    public function index(Request $request)
    {
        $estados = [];
        $cidades = [];
        $estados = $this->EstadoServiceInterface->estados_disponiveis();

        if ($request->id_estado) {
            $cidades = $this->CidadeServiceInterface->cidades_disponiveis($request->id_estado);
        }

        //Montagem dos filtros
        $filtros = new stdClass();
        $filtros->id_tipo_serventia = $request->id_tipo_serventia;
        $filtros->nu_cns = $request->nu_cns;
        $filtros->no_serventia = $request->no_serventia;
        $filtros->email_serventia = $request->email_serventia;
        $filtros->no_pessoa = $request->no_responsavel;
        $filtros->nu_cpf_cnpj = preg_replace('#[^0-9]#', '', $request->nu_cpf_cnpj);
        $filtros->id_estado = $request->id_estado;
        $filtros->id_cidade = $request->id_cidade;

        $todas_serventias = $this->ServentiaServiceInterface->listar($filtros);
        $todas_serventias = $todas_serventias->paginate(10, ['*'], 'pag');
        $todas_serventias->appends(Request::capture()->except('_token'))->render();

        $tipo_serventia = new tipo_serventia();
        $tipo_serventias = $tipo_serventia->get();

        $args = [
            'class' => $this,
            'cidades' => $cidades,
            'estados' => $estados,
            'tipo_serventias' => $tipo_serventias,
            'todas_serventias' => $todas_serventias
        ];

        return view('app.configuracoes.serventias.geral-serventias', $args);
    }

    public function nova_serventia(Request $request)
    {
        $estados_disponiveis = $this->EstadoServiceInterface->estados_disponiveis();
        $campos = $request->campos;

        $tipo_serventia = new tipo_serventia();
        $tipo_serventias = $tipo_serventia->get();

        if (isset($campos['id_cidade'])) {
            $campos['cidade'] = $this->CidadeServiceInterface->buscar_cidade($campos['id_cidade']);
            
            $cidades_disponiveis = $this->CidadeServiceInterface->cidades_disponiveis($campos['id_cidade']);
        }

        $compact_args = [
            'estados_disponiveis' => $estados_disponiveis,
            'cidades_disponiveis' => $cidades_disponiveis ?? [],
            'campos' => $campos,
            'readonly' => ($campos ? 'readonly' : NULL),
            'tipo_serventias' => $tipo_serventias

        ];

        return view('app.configuracoes.serventias.geral-serventias-novo', $compact_args);
    }

    public function salvar_serventia(StoreServentia $request)
    {
        Gate::authorize('serventia-nova');
        
        DB::beginTransaction();

        try {

            //Inserir a Pessoa que vai representar a entidade serventia
            // Argumentos pessoa!
            $args_pessoa = new stdClass();
            $args_pessoa->no_pessoa = $request->no_serventia;
            $args_pessoa->tp_pessoa = 'J';
            $args_pessoa->nu_cpf_cnpj = preg_replace('#[^0-9]#', '', $request->nu_cpf_cnpj);
            $args_pessoa->no_email_pessoa = $request->email_serventia;
            $args_pessoa->id_tipo_pessoa = 2;

            $novo_pessoa = $this->PessoaServiceInterface->inserir($args_pessoa);

            // Endereco
            $novo_endereco = new endereco();
            $novo_endereco->id_cidade = $request->id_cidade;
            $novo_endereco->no_endereco = $request->no_endereco;
            $novo_endereco->nu_endereco = $request->nu_endereco;
            $novo_endereco->no_bairro = $request->no_bairro;
            $novo_endereco->nu_cep = preg_replace('#[^0-9]#', '', $request->nu_cep);
            $novo_endereco->no_complemento = $request->no_complemento;
    
            if ($novo_endereco->save()) {
                $novo_pessoa_endereco = new pessoa_endereco();
                $novo_pessoa_endereco->id_pessoa = $novo_pessoa->id_pessoa; 
                $novo_pessoa_endereco->id_endereco = $novo_endereco->id_endereco; 
                if (!$novo_pessoa_endereco->save()) {
                    throw new Exception('Erro ao salvar relacionamento da pessoa com endereço.');
                }
            } else {
                throw new Exception('Erro ao salvar o endereço da pessoa.');
            }

            DB::commit();

            //Inserir a Serventia com a Pessoa criada acima
            $args_serventia = new stdClass(); 
            $args_serventia->id_tipo_serventia = $request->id_tipo_serventia;
            $args_serventia->id_pessoa = $novo_pessoa->id_pessoa;
            $args_serventia->no_serventia = $request->no_serventia;
            $args_serventia->abv_serventia = $request->no_serventia;
            $args_serventia->no_responsavel = $request->no_responsavel;
            $args_serventia->codigo_cns_completo = $request->nu_cns;
            $args_serventia->telefone_serventia = $request->telefone_serventia;
            $args_serventia->site_serventia = $request->site_serventia;
            $args_serventia->whatsapp_serventia = $request->whatsapp_serventia;
           
            $novo_serventia = $this->ServentiaServiceInterface->inserir($args_serventia);

            LogDB::insere(
                Auth::User()->id_usuario,
                6,
                'Inseriu a serventia '.$novo_serventia->no_serventia.' com sucesso.',
                'Serventia',
                'N',
                request()->ip()
            );

            $response_json = [
                'status' => 'sucesso',
                'message' => 'A serventia foi salva com sucesso.',
                'recarrega' => 'true'
            ];

            return response()->json($response_json, 200);


        }catch (Exception $e) {
            DB::rollback();

            LogDB::insere(
                Auth::User()->id_usuario,
                4,
                'Erro ao inserir serventia',
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


    public function detalhes_serventia(Request $request)
    {
        Gate::authorize('serventia-detalhes');

        $serventia = $this->ServentiaServiceInterface->buscar($request->id_serventia);
                      
        $endereco = new pessoa();
        $endereco = $endereco->join('pessoa_endereco', 'pessoa_endereco.id_pessoa', '=', 'pessoa.id_pessoa')
                             ->join('endereco', 'endereco.id_endereco', '=', 'pessoa_endereco.id_endereco')
                             ->join('cidade', 'cidade.id_cidade', '=', 'endereco.id_cidade')
                             ->join('estado', 'estado.id_estado', '=', 'cidade.id_estado')
                             ->where('pessoa.id_pessoa', $serventia->id_pessoa)->first();
                                                        
        $compact_args = [

            'serventia' => $serventia,
            'endereco'  => $endereco
            
        ];

        return view('app.configuracoes.serventias.geral-serventias-detalhes', $compact_args);


    }

    public function editar_serventia(Request $request)
    {
        Gate::authorize('serventia-detalhes');

        $estados_disponiveis = $this->EstadoServiceInterface->estados_disponiveis();
      
        $serventia = $this->ServentiaServiceInterface->buscar($request->id_serventia);

        $tipo_serventia = new tipo_serventia();
        $tipo_serventias = $tipo_serventia->get();
                      
        $endereco = new pessoa();
        $endereco = $endereco->join('pessoa_endereco', 'pessoa_endereco.id_pessoa', '=', 'pessoa.id_pessoa')
                             ->join('endereco', 'endereco.id_endereco', '=', 'pessoa_endereco.id_endereco')
                             ->join('cidade', 'cidade.id_cidade', '=', 'endereco.id_cidade')
                             ->join('estado', 'estado.id_estado', '=', 'cidade.id_estado')
                             ->where('pessoa.id_pessoa', $serventia->id_pessoa)->first();

        $cidades_disponiveis = $this->CidadeServiceInterface->cidades_disponiveis($endereco->id_estado);
                                                         
        $compact_args = [
             
            'request' => $request,
            'serventia' => $serventia,
            'endereco'  => $endereco,
            'estados_disponiveis' => $estados_disponiveis,
            'cidades_disponiveis' => $cidades_disponiveis,
            'tipo_serventias' => $tipo_serventias
            
        ];

        return view('app.configuracoes.serventias.geral-serventias-editar', $compact_args);


    }


    public function alterar_serventia(UpdateServentia $request)
    {
        Gate::authorize('serventia-detalhes');    

        DB::beginTransaction();

        try {

            $serventia = $this->ServentiaServiceInterface->buscar($request->id_serventia);
            $args_serventia = new stdClass();
            $args_serventia->id_tipo_serventia = $request->id_tipo_serventia;
            $args_serventia->codigo_cns_completo = $request->nu_cns;
            $args_serventia->no_serventia = $request->no_serventia;
            $args_serventia->abv_serventia = $request->no_serventia;
            $args_serventia->telefone_serventia = $request->telefone_serventia;
            $args_serventia->site_serventia = $request->site_serventia;
            $args_serventia->whatsapp_serventia = $request->whatsapp_serventia;
            $this->ServentiaServiceInterface->alterar($serventia, $args_serventia);

            DB::commit();   

            $pessoa = $this->PessoaServiceInterface->buscar($serventia->id_pessoa);
            $args_pessoa = new stdClass();
            $args_pessoa->no_email_pessoa = $request->email_serventia;
            $args_pessoa->nu_cpf_cnpj = preg_replace('#[^0-9]#', '', $request->nu_cpf_cnpj);
            $args_pessoa->no_pessoa = $request->no_serventia;
            $this->PessoaServiceInterface->alterar($pessoa , $args_pessoa);

            $pessoa_endereco = new pessoa_endereco();
            $pessoa_endereco = $pessoa_endereco->where('pessoa_endereco.id_pessoa', $serventia->id_pessoa)->first();
           
            $endereco = new endereco();
            $endereco = $endereco->where('endereco.id_endereco', $pessoa_endereco->id_endereco)->first();

            $endereco->nu_cep  = preg_replace('#[^0-9]#', '', $request->nu_cep);
            $endereco->no_endereco  =  $request->no_endereco;
            $endereco->nu_endereco  =  $request->nu_endereco;
            $endereco->no_bairro  =  $request->no_bairro;
            $endereco->no_complemento  =  $request->no_complemento;
            $endereco->id_cidade  =  $request->id_cidade;

            if (!$endereco->save()) {
                throw new Exception('Erro ao atualizar o endereço da serventia.');
            }

            LogDB::insere(
                Auth::User()->id_usuario,
                6,
                'Alterou a serventia com sucesso.',
                'Serventia',
                'N',
                request()->ip()
            );

            $response_json = [
                'status' => 'sucesso',
                'message' => 'A serventia foi alterada com sucesso.',
                'recarrega' => 'true'
            ];

            return response()->json($response_json, 200);


        }catch (Exception $e) {
            DB::rollback();

            LogDB::insere(
                Auth::User()->id_usuario,
                4,
                'Erro ao alterar serventia',
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

}