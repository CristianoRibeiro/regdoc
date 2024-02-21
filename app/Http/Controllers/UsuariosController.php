<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

use Auth;
use Session;
use DB;
use Exception;
use Validator;
use Helper;
use Carbon\Carbon;
use Hash;
use URL;
use Mail;
use LogDB;

use App\Models\tipo_pessoa;
use App\Domain\Pessoa\Models\pessoa;
use App\Domain\Usuario\Models\usuario;
use App\Domain\Usuario\Models\usuario_pessoa;
use App\Domain\Usuario\Models\usuario_senha;

use App\Exceptions\RegdocException;

use App\Mail\UsuariosNovaSenhaMail;
use App\Mail\UsuariosNovoUsuarioMail;
use App\Mail\UsuariosNovoVinculoMail;

use App\Http\Requests\Configuracoes\Usuarios\StoreUsuario;
use App\Http\Requests\Configuracoes\Usuarios\EnviarSenhaUsuario;
use App\Http\Requests\Configuracoes\Usuarios\DesativarUsuario;
use App\Http\Requests\Configuracoes\Usuarios\ReativarUsuario;

use App\Domain\Usuario\Contracts\UsuarioServiceInterface;
use App\Domain\Pessoa\Contracts\PessoaServiceInterface;

class UsuariosController extends Controller
{
	/**
	 * @var UsuarioServiceInterface
	 * @var PessoaServiceInterface
	 *
	 */
	protected $UsuarioServiceInterface;
	protected $PessoaServiceInterface;

	/**
	 * UsuariosController constructor.
	 * @param UsuarioServiceInterface $UsuarioServiceInterface
	 * @param PessoaServiceInterface $PessoaServiceInterface
	 */
	public function __construct(UsuarioServiceInterface $UsuarioServiceInterface,
        PessoaServiceInterface $PessoaServiceInterface)
	{
		parent::__construct();
		$this->UsuarioServiceInterface = $UsuarioServiceInterface;
		$this->PessoaServiceInterface = $PessoaServiceInterface;
	}

	public function index(Request $request)
    {
        $todos_usuarios = new usuario();
        $todos_usuarios = $todos_usuarios->select('usuario.*')
            ->join('pessoa', 'pessoa.id_pessoa', '=', 'usuario.id_pessoa')
            ->join('usuario_pessoa', 'usuario_pessoa.id_usuario', '=', 'usuario.id_usuario')
        	->join('pessoa as pessoa_entidade', 'pessoa_entidade.id_pessoa', '=', 'usuario_pessoa.id_pessoa');

        if ($request->no_usuario) {
            $todos_usuarios = $todos_usuarios->where('usuario.no_usuario', 'ilike', '%' . $request->no_usuario . '%');
        }
        if ($request->dt_cadastro_ini and $request->dt_cadastro_fim) {
            $dt_cadastro_ini = Carbon::createFromFormat('d/m/Y', $request->dt_cadastro_ini)->startOfDay();
            $dt_cadastro_fim = Carbon::createFromFormat('d/m/Y', $request->dt_cadastro_fim)->endOfDay();
            $todos_usuarios = $todos_usuarios->whereBetween('usuario.dt_cadastro', [$dt_cadastro_ini, $dt_cadastro_fim]);
        }
        if ($request->email_usuario) {
            $todos_usuarios = $todos_usuarios->where('usuario.email_usuario', 'ilike', '%' . $request->email_usuario . '%');
        }
        if ($request->nu_cpf_cnpj) {
            $todos_usuarios = $todos_usuarios->where('pessoa.nu_cpf_cnpj', '=', Helper::somente_numeros($request->nu_cpf_cnpj));
        }
        if ($request->id_pessoa_entidade) {
            $todos_usuarios = $todos_usuarios->where('pessoa_entidade.id_pessoa', '=', $request->id_pessoa_entidade);
        }
        if ($request->in_registro_ativo) {
            $todos_usuarios = $todos_usuarios->where('usuario.in_registro_ativo', '=', $request->in_registro_ativo);
        }
        if ($request->in_usuario_logado) {
            $todos_usuarios = $todos_usuarios->join('sessions', function($join) {
                    $now = Carbon::now()->subMinutes(config('session.lifetime'))->getTimestamp();

                    $join->on('sessions.user_id', '=', 'usuario.id_usuario')
                        ->where('sessions.last_activity', '>', $now);
                });
        }
        

        switch (Auth::User()->pessoa_ativa->id_tipo_pessoa) {
            case 1:
            case 13:
                $pessoas_entidades = $this->PessoaServiceInterface->listar_por_tipo([8]);
                
                $todos_usuarios = $todos_usuarios->whereNotIn('pessoa_entidade.id_tipo_pessoa', config('constants.USUARIO.ID_TIPO_PESSOA_INVISIVEIS'));
                break;
            default:
				$todos_usuarios = $todos_usuarios->where('usuario_pessoa.id_pessoa', '=', Auth::User()->pessoa_ativa->id_pessoa)
                    ->whereNotIn('pessoa_entidade.id_tipo_pessoa', config('constants.USUARIO.ID_TIPO_PESSOA_INVISIVEIS'));
				break;
        }

        $todos_usuarios = $todos_usuarios->groupBy('usuario.id_usuario')
                                         ->orderBy('usuario.dt_cadastro', 'desc');

        $todos_usuarios = $todos_usuarios->paginate(10, ['*'], 'pag');
        $todos_usuarios->appends(Request::capture()->except('_token'))->render();


        // Argumentos para o retorno da view
        $compact_args = [
            'pessoas_entidades' => $pessoas_entidades ?? [],
            'todos_usuarios' => $todos_usuarios,
        ];
        return view('app.configuracoes.gerenciar-usuarios.geral-gerenciar-usuarios', $compact_args);
    }

    public function novo_usuario(Request $request)
    {
        $tipo_pessoa = new tipo_pessoa();
        $tipos_pessoa = $tipo_pessoa->where('in_registro_ativo', 'S')
                                    ->orderBy('nu_ordem', 'asc')
                                    ->get();

        // Argumentos para o retorno da view
        $compact_args = [
            'tipos_pessoa' => $tipos_pessoa,
            'request' => $request,
            'class' => $this
        ];

        return view('app.configuracoes.gerenciar-usuarios.geral-gerenciar-usuarios-novo', $compact_args);
    }

    public function inserir_usuario(StoreUsuario $request)
    {
        DB::beginTransaction();

        try {
            $nova_pessoa = new pessoa();
            $nova_pessoa->no_pessoa = $request->no_usuario;
            $nova_pessoa->tp_pessoa = 'F';
            $nova_pessoa->nu_cpf_cnpj = $request->nu_cpf_cnpj;
            $nova_pessoa->no_email_pessoa = $request->email_usuario;
            $nova_pessoa->id_tipo_pessoa = config('constants.USUARIO.ID_TIPO_PESSOA_USUARIO');

            if (!$nova_pessoa->save())
				throw new Exception('Erro ao salvar a pessoa no banco de dados.');

            $novo_usuario = new usuario();
            $novo_usuario->id_pessoa = $nova_pessoa->id_pessoa;
            $novo_usuario->no_usuario = $request->no_usuario;
            $novo_usuario->email_usuario = $request->email_usuario;
            $novo_usuario->login = $request->email_usuario;
            $novo_usuario->dt_ini_periodo = Carbon::now();
            $novo_usuario->in_confirmado = 'S';
            $novo_usuario->in_aprovado = 'S';
            $novo_usuario->dt_usuario_confirmado = Carbon::now();
            $novo_usuario->dt_usuario_aprovado = Carbon::now();
            $novo_usuario->in_completar_cadastro = 'S';

            if (!$novo_usuario->save())
				throw new Exception('Erro ao salvar o usuário no banco de dados.');

            $senha_gerada = Str::random(10);

            $novo_usuario_senha = new usuario_senha();
            $novo_usuario_senha->id_usuario = $novo_usuario->id_usuario;
            $novo_usuario_senha->senha = Hash::make($senha_gerada);
            $novo_usuario_senha->dt_ini_periodo = Carbon::now();
            $novo_usuario_senha->in_alterar_senha = 'S';

            if (!$novo_usuario_senha->save())
                throw new Exception("Erro ao salvar a senha do usuário no banco de dados.");

            $pessoas = new pessoa();
            $pessoas = $pessoas->where('in_registro_ativo', 'S');
            if (in_array(Auth::User()->pessoa_ativa->id_tipo_pessoa, config('constants.USUARIO.ID_TIPO_PESSOA_ADDVINCULO'))) {
                $pessoas = $pessoas->whereIn('id_pessoa', $request->id_pessoa);
            } else {
                $pessoas = $pessoas->where('id_pessoa', Auth::User()->pessoa_ativa->id_pessoa);
            }
            $pessoas = $pessoas->orderBy('pessoa.no_pessoa', 'asc')
                ->get();

            if ($pessoas->count()<=0)
				throw new Exception('Erro ao encontrar os vínculos no banco de dados.');

            foreach ($pessoas as $pessoa) {
                $novo_usuario_pessoa = new usuario_pessoa();
                $novo_usuario_pessoa->id_usuario = $novo_usuario->id_usuario;
                $novo_usuario_pessoa->id_pessoa = $pessoa->id_pessoa;
                $novo_usuario_pessoa->in_usuario_master = ($request->in_usuario_master=='S'?'S':'N');
                $novo_usuario_pessoa->id_usuario_cad = Auth::User()->id_usuario;

                if (!$novo_usuario_pessoa->save())
                    throw new Exception("Erro ao salvar o vínculo no banco de dados.");
            }

			Mail::to($novo_usuario->email_usuario, $novo_usuario->no_usuario)->queue(new UsuariosNovoUsuarioMail($novo_usuario, $pessoas, $senha_gerada));

            DB::commit();

            LogDB::insere(
                Auth::User()->id_usuario,
                6,
                'O usuário foi criado com sucesso.',
                'Usuarios',
                'N',
                request()->ip()
            );

            $response_json = [
                'message' => 'O usuário foi criado com sucesso.',
            ];
            return response()->json($response_json,200);
        } catch (Exception $e) {
            DB::rollback();

            LogDB::insere(
                Auth::User()->id_usuario,
                6,
                'Error ao criar usuário.',
                'Usuarios',
                'N',
                request()->ip(),
                $e->getMessage().' Linha '.$e->getLine().' do arquivo '.$e->getFile().'.'
            );

            $response_json = [
                'message' => 'Erro interno, tente novamente mais tarde. '.(config('app.env')!='production'?$e->getMessage() . ' na linha ' . $e->getLine() . ' do arquivo ' . $e->getFile():''),
            ];
            return response()->json($response_json,500);
        }
    }

    public function detalhes_usuario(Request $request)
    {
        $usuario = new usuario();
        $usuario = $usuario->find($request->id_usuario);
        if ($usuario) {
            // Argumentos para o retorno da view
            $compact_args = [
                'class' => $this,
                'usuario' => $usuario
            ];

            return view('app.configuracoes.gerenciar-usuarios.geral-gerenciar-usuarios-detalhes', $compact_args);
        } else {
            $response_json = [
                'message' => 'O usuário não foi encontrado.'
            ];
            return response()->json($response_json,400);
        }
    }

    public function novo_vinculo(Request $request)
    {
        $usuarios = [];
        $tipos_pessoa = [];
        if (isset($request->busca_usuario)) {
            $usuario = new usuario();
            $usuarios = $usuario->join('pessoa','pessoa.id_pessoa','=','usuario.id_pessoa')
                                ->where(function($where) use ($request) {
                                    $where->where('usuario.login', 'like', '%'.$request->busca_usuario.'%')
                                          ->orWhere('usuario.email_usuario', 'like', '%'.$request->busca_usuario.'%');
                                })
                                ->where('usuario.in_registro_ativo', 'S')
                                ->whereNotIn('pessoa.id_tipo_pessoa', [config('constants.USUARIO.ID_TIPO_PESSOA_CLIENTE')])
                                ->orderBy('usuario.dt_cadastro', 'desc')
                                ->paginate(10);

            $tipo_pessoa = new tipo_pessoa();
            $tipos_pessoa = $tipo_pessoa->where('in_registro_ativo', 'S')
                                        ->orderBy('nu_ordem', 'asc')
                                        ->get();
        }

        // Argumentos para o retorno da view
        $compact_args = [
            'tipos_pessoa' => $tipos_pessoa,
            'usuarios' => $usuarios,
            'request' => $request,
            'class' => $this
        ];

        return view('app.configuracoes.gerenciar-usuarios.geral-gerenciar-usuarios-novo-vinculo', $compact_args);
    }

    public function inserir_vinculo(Request $request)
    {
        DB::beginTransaction();

        try {
            $usuario = new usuario();
            $usuario = $usuario->find($request->id_usuario);

            if(in_array(Auth::User()->pessoa_ativa->id_tipo_pessoa, config('constants.USUARIO.ID_TIPO_PESSOA_ADDVINCULO')) and $request->id_pessoa>0) {
                $id_pessoa_selecionada = $request->id_pessoa;
            } else {
                $id_pessoa_selecionada = Auth::User()->pessoa_ativa->id_pessoa;
            }

            $pessoa_vinculo = new pessoa();
            $pessoa_vinculo = $pessoa_vinculo->find($id_pessoa_selecionada);

            if ($usuario) {
                if (!$pessoa_vinculo->usuario_pessoa->contains('id_usuario', $request->id_usuario)) {
                    $novo_usuario_pessoa = new usuario_pessoa();
                    $novo_usuario_pessoa->id_usuario = $usuario->id_usuario;
                    $novo_usuario_pessoa->id_pessoa = $pessoa_vinculo->id_pessoa;
                    $novo_usuario_pessoa->in_usuario_master = ($request->in_usuario_master=='S'?'S':'N');
                    $novo_usuario_pessoa->id_usuario_cad = Auth::User()->id_usuario;

                    if ($novo_usuario_pessoa->save()) {
						Mail::to($usuario->email_usuario, $usuario->no_usuario)->queue(new UsuariosNovoVinculoMail($usuario, $pessoa_vinculo));
                    } else {
                        throw new Exception("Erro ao salvar o vínculo no banco de dados.");
                    }

                    DB::commit();

                    $response_json = [
                        'message' => 'O usuário foi vinculado com sucesso.',
                    ];
                    return response()->json($response_json,200);
                } else {
                    throw new RegdocException('O usuário selecionado já foi vinculado.');
                }
            } else {
                throw new Exception('O usuário selecionado não foi encontrado.');
            }
		} catch (RegdocException $e) {
            DB::rollback();

            $response_json = [
                'status' => 'alerta',
                'message' => $e->getMessage(),
                'regarrega' => 'false'
            ];

            return response()->json($response_json, 400);
        } catch (Exception $e) {
            DB::rollback();
            $response_json = [
                'message' => $e->getMessage().' Linha '.$e->getLine().' do arquivo '.$e->getFile().'.',
            ];
            return response()->json($response_json,500);
        }
    }

    public function remover_vinculo(Request $request)
    {
        $usuario_pessoa = new usuario_pessoa();
        $usuario_pessoa = $usuario_pessoa->find($request->id_usuario_pessoa);
        if ($usuario_pessoa) {
            if ($usuario_pessoa->delete()) {
                $response_json = [
                    'message' => 'O vínculo foi removido com sucesso.',
                ];
                return response()->json($response_json,200);
            } else {
                $response_json = [
                    'message' => 'Erro ao deletar o vínculo do usuário.',
                ];
                return response()->json($response_json,500);
            }
        } else {
            $response_json = [
                'message' => 'O usuário não foi encontrado.'
            ];
            return response()->json($response_json,400);
        }
    }

    /* Lista as pessoas de um tipo e agrupa por cidade
     */
    public function listar_pessoas(Request $request)
    {
        $pessoas_cidade = [];
        if ($request->id_tipo_pessoa>0) {
            $pessoa = new pessoa();
            $pessoas = $pessoa->where('in_registro_ativo', 'S')
                              ->where('id_tipo_pessoa', $request->id_tipo_pessoa)
                              ->orderBy('pessoa.no_pessoa', 'asc')
                              ->get();

            if (count($pessoas)>0) {
                foreach ($pessoas as $pessoa) {
                    if (count($pessoa->enderecos)>0) {
                        $id_cidade = $pessoa->enderecos[0]->id_cidade;
                        $no_cidade = $pessoa->enderecos[0]->cidade->no_cidade;
                    } else {
                        $id_cidade = 0;
                        $no_cidade = 'Não definido';
                    }
                    $pessoas_cidade[$id_cidade]['no_cidade'] = $no_cidade;
                    $pessoas_cidade[$id_cidade]['pessoas'][] = $pessoa;
                }
            }
        }
        return response()->json($pessoas_cidade);
    }

    /**
     * @param EnviarSenhaUsuario $request
     * @param usuario $usuario
     * @return \Illuminate\Http\JsonResponse
     */
	public function gerar_nova_senha(EnviarSenhaUsuario $request, usuario $usuario)
	{
        DB::beginTransaction();

        try {
            $usuario = $usuario->find($request->id_usuario);
            if ($usuario) {
                $desativar_senha_atual = $usuario->usuario_senha()->orderBy('dt_cadastro', 'desc')->first();
                if ($desativar_senha_atual) {
                    $desativar_senha_atual->dt_fim_periodo = Carbon::now();
                    if (!$desativar_senha_atual->save()) {
                        throw new Exception('Erro ao desabilitar a senha antiga no banco de dados.');
                    }
                }

                $senha_gerada = Str::random(10);

                $nova_senha = new usuario_senha();
                $nova_senha->id_usuario = $usuario->id_usuario;
                $nova_senha->dt_ini_periodo = Carbon::now();
                $nova_senha->in_alterar_senha = 'S';
                $nova_senha->senha = Hash::make($senha_gerada);
                if(!$nova_senha->save()) {
                    throw new Exception('Erro ao salvar a nova senha.');
                }

                Mail::to($usuario->email_usuario, $usuario->pessoa->no_pessoa)->queue(new UsuariosNovaSenhaMail($usuario, $senha_gerada));

            } else {
                throw new Exception('O usuário não foi encontrado.');
            }

            DB::commit();

            LogDB::insere(
                Auth::user()->id_usuario,
                2,
                'Nova senha gerada para usuario',
                'Manter Usuarios',
                'N',
                request()->ip()
            );

            $response_json = [
                'status' => 'sucesso',
                'recarrega' => 'true',
                'message' => 'Uma nova senha foi gerada com sucesso e enviada para o e-mail '.$usuario->email_usuario.'.'
            ];

            return response()->json($response_json);
        } catch(Exception $e) {
            DB::rollback();

            LogDB::insere(
                Auth::user()->id_usuario,
                2,
                'Erro ao gerar nova senha para usuario',
                'Manter Usuarios',
                'N',
                request()->ip(),
                ' Linha ' . $e->getLine() . ' do arquivo ' . $e->getFile() . '.'
            );

            $response_json = [
                'status' => 'erro',
                'recarrega' => 'false',
                'message' => $e->getMessage() . (config('app.env')!='production'?' Linha ' . $e->getLine() . ' do arquivo ' . $e->getFile() . '.':'')
            ];

            return response()->json($response_json);
        }
    }

	public function desativar_usuario(DesativarUsuario $request, usuario $usuario)
	{
        DB::beginTransaction();

        try {
            $usuario = $usuario->find($request->id_usuario);

            if ($usuario) {
                $usuario->pessoa->in_registro_ativo = 'N';
                if (!$usuario->pessoa->save()) {
                    throw new Exception('Erro ao desativar a pessoa.');
                }

                $usuario->in_registro_ativo = 'N';
                if (!$usuario->save()) {
                    throw new Exception('Erro ao desativar o usuário.');
                }
            } else {
                throw new Exception('O usuário não foi encontrado.');
            }

            DB::commit();

            $response_json = [
                'status' => 'sucesso',
                'recarrega' => 'true',
                'message' => 'O usuário foi desativado com sucesso.'
            ];

            return response()->json($response_json);
        } catch(Exception $e) {
            DB::rollback();

            $response_json = [
                'status' => 'erro',
                'recarrega' => 'false',
                'message' => $e->getMessage() . (config('app.env')!='production'?' Linha ' . $e->getLine() . ' do arquivo ' . $e->getFile() . '.':'')
            ];

            return response()->json($response_json);
        }
    }

	public function reativar_usuario(ReativarUsuario $request, usuario $usuario)
	{
        DB::beginTransaction();

        try {
            $usuario = $usuario->find($request->id_usuario);
            if ($usuario) {
                $usuario->pessoa->in_registro_ativo = 'S';
                if (!$usuario->pessoa->save()) {
                    throw new Exception('Erro ao desativar a pessoa.');
                }

                $usuario->in_registro_ativo = 'S';
                if (!$usuario->save()) {
                    throw new Exception('Erro ao desativar o usuário.');
                }
            } else {
                throw new Exception('O usuário não foi encontrado.');
            }

            DB::commit();

            $response_json = [
                'status' => 'sucesso',
                'recarrega' => 'true',
                'message' => 'O usuário foi reativado com sucesso.'
            ];

            return response()->json($response_json);
        } catch(Exception $e) {
            DB::rollback();

            $response_json = [
                'status' => 'erro',
                'recarrega' => 'false',
                'message' => $e->getMessage() . (config('app.env')!='production'?' Linha ' . $e->getLine() . ' do arquivo ' . $e->getFile() . '.':'')
            ];

            return response()->json($response_json);
        }
    }

    public function listar(Request $request) {
        $usuarios = [];
        if ($request->id_pessoa_origem) {
            $usuarios = $this->UsuarioServiceInterface->listar_por_entidade($request->id_pessoa_origem);
        }
        return response()->json($usuarios);
    }
}
