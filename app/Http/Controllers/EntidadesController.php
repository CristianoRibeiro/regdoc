<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Str;
use DB;
use Exception;
use Auth;
use Helper;
use LogDB;
use Mail;
use Hash;
use URL;
use Carbon\Carbon;

use App\Domain\Pessoa\Models\pessoa;
use App\Domain\Configuracao\Models\configuracao;

use App\Domain\Pessoa\Contracts\PessoaServiceInterface;
use App\Domain\Estado\Contracts\EstadoServiceInterface;
use App\Domain\Estado\Contracts\CidadeServiceInterface;

// use App\Models\estado;
// use App\Models\cidade;
// use App\Models\tipo_pessoa;
// use App\Models\usuario_senha;
// use App\Models\pessoa_endereco;
// use App\Models\pessoa_telefone;
// use App\Models\pessoa_modulo;
// use App\Models\endereco;
// use App\Models\telefone;
// use App\Models\usuario_pessoa;
// use App\Models\usuario;
// use App\Models\agencia;
// use App\Models\banco;
// use App\Models\registro_fiduciario_credor;

//
// use App\Http\Requests\BancosSalvar;
// use App\Http\Requests\BancosAlterar;

// use App\Mail\UsuariosNovoUsuarioMail;

class EntidadesController extends Controller
{
    /**
    * @var PessoaServiceInterface
    * @var EstadoServiceInterface
    * @var CidadeServiceInterface
    */

   protected $PessoaServiceInterface;
   protected $EstadoServiceInterface;
   protected $CidadeServiceInterface;

   public function __construct(PessoaServiceInterface $PessoaServiceInterface,
        EstadoServiceInterface $EstadoServiceInterface,
        CidadeServiceInterface $CidadeServiceInterface)
   {
      $this->PessoaServiceInterface = $PessoaServiceInterface;
      $this->EstadoServiceInterface = $EstadoServiceInterface;
      $this->CidadeServiceInterface = $CidadeServiceInterface;
   }

    public function index(Request $request)
    {
        $estados = [];
        $cidades = [];

        $estados = $this->EstadoServiceInterface->estados_disponiveis();

        if ($request->id_estado) {
            $cidades = $this->CidadeServiceInterface->cidades_disponiveis($request->id_estado);
        }

        $pessoas = new pessoa();
        $pessoas = $pessoas->where('pessoa.id_tipo_pessoa', 8);

        $pessoas = $this->aplicar_filtro($pessoas, $request);

        $pessoas = $pessoas->orderBy('pessoa.dt_cadastro','desc')->paginate(10);
        $pessoas->appends(Request::capture()->except('_token'))->render();

        $args = [
            'class' => $this,
            'pessoas' => $pessoas,
            'cidades' => $cidades,
            'estados' => $estados,
        ];

        return view('app.configuracoes.entidades.geral-entidades' , $args);
    }

    public function show(Request $request)
    {
        $pessoa = $this->PessoaServiceInterface->buscar($request->entidade);

        $configuracoes_pessoa = new configuracao();
        $configuracoes_pessoa = $configuracoes_pessoa->select('configuracao.id_configuracao', 'configuracao.no_configuracao', 'configuracao_pessoa.no_valor')
            ->leftJoin('configuracao_pessoa',function ($join) use ($pessoa) {
                $join->on('configuracao_pessoa.id_configuracao', '=', 'configuracao.id_configuracao')
                    ->where('configuracao_pessoa.id_pessoa', $pessoa->id_pessoa);
            })
            ->get();

        // Argumentos para o retorno da view
        $args = [
            'pessoa' => $pessoa,
            'configuracoes_pessoa' => $configuracoes_pessoa
        ];

        return view('app.configuracoes.entidades.geral-entidades-detalhes', $args);
    }

    private function aplicar_filtro($pessoas, $request){
        if($request->no_pessoa){
            $pessoas = $pessoas->where('pessoa.no_pessoa','ilike', '%'.$request->no_pessoa.'%');
        }
        if($request->nu_cnpj){
            $pessoas = $pessoas->where('nu_cpf_cnpj',Helper::limpar_mascara($request->nu_cnpj));
        }
        if($request->data_cadastro_ini || $request->data_cadastro_fim ){
            $data_cadastro_ini  = Carbon::createFromFormat('d/m/Y', $request->data_cadastro_ini)->startOfDay();
            $data_cadastro_fim  = Carbon::createFromFormat('d/m/Y', $request->data_cadastro_fim)->endOfDay();
            $pessoas = $pessoas->whereBetween('pessoa.dt_cadastro', [$data_cadastro_ini, $data_cadastro_fim]);
        }

        $pessoas = $pessoas->where('id_tipo_pessoa', 8);

        if($request->id_estado){
            $pessoas = $pessoas->join('pessoa_endereco','pessoa_endereco.id_pessoa','=', 'pessoa.id_pessoa')
                ->join('endereco','endereco.id_endereco','=','pessoa_endereco.id_endereco')
                ->join('cidade', 'cidade.id_cidade','=','endereco.id_cidade')
                ->where('cidade.id_estado', $request->id_estado);
        }
        if($request->id_cidade){
            $pessoas = $pessoas->where('endereco.id_cidade', $request->id_cidade);

        }

        return $pessoas;
    }

    //
    // public function novo_banco(Request $request, estado $estado)
    // {
	//     $estados = $estado->orderBy('no_estado')
    //                       ->get();
    //
	//     $banco = new banco();
	//     $bancos = $banco->where('in_registro_ativo', '=', 'S')
    //                     ->orderBy('codigo_banco', 'asc')
    //                     ->get();
    //
    //
    //     // Argumentos para o retorno da view
    //     $compact_args = [
    //         'request' => $request,
    //         'class' => $this,
    //         'estados' => $estados,
    //         'bancos' => $bancos,
    //     ];
    //     return view('app.configuracoes.bancos.geral-novo-banco', $compact_args);
    // }
    //
    // public function inserir_banco(BancosSalvar $request)
    // {
    //     DB::beginTransaction();
    //
    //     try {
    //         $nova_pessoa = new pessoa();
    //         $nova_pessoa->no_pessoa = $request->no_pessoa;
    //         $nova_pessoa->tp_pessoa = 'J';
    //         $nova_pessoa->no_email_pessoa = $request->no_email_pessoa;
    //         $nova_pessoa->nu_cpf_cnpj = preg_replace('#[^0-9]#', '', $request->nu_cnpj);
    //         $nova_pessoa->nu_inscricao_municipal = $request->nu_inscricao_municipal;
    //         $nova_pessoa->no_fantasia = $request->no_fantasia;
    //         $nova_pessoa->tp_sexo = 'N';
    //         $nova_pessoa->id_tipo_pessoa = 8;
    //
    //         if ($nova_pessoa->save()) {
    //             // Módulo
    //             $nova_pessoa_modulo = new pessoa_modulo();
    //             $nova_pessoa_modulo->id_pessoa = $nova_pessoa->id_pessoa;
    //             $nova_pessoa_modulo->id_modulo = 8;
    //             $nova_pessoa_modulo->in_registro_ativo = "S";
    //             $nova_pessoa_modulo->id_usuario_cad = Auth::User()->id_usuario;
    //             $nova_pessoa_modulo->dt_cadastro = Carbon::now();
    //             if (!$nova_pessoa_modulo->save()) {
    //                 throw new Exception('Erro ao salvar o módulo do banco.');
    //             }
    //
    //             // Endereco
    //             $novo_endereco = new endereco();
    //             $novo_endereco->id_cidade = $request->id_cidade;
    //             $novo_endereco->no_endereco = $request->no_endereco;
    //             $novo_endereco->nu_endereco = $request->nu_endereco;
    //             $novo_endereco->no_bairro = $request->no_bairro;
    //             $novo_endereco->nu_cep = preg_replace('#[^0-9]#', '', $request->nu_cep);
    //             $novo_endereco->no_complemento = $request->no_complemento;
    //
    //             if ($novo_endereco->save()) {
    //                 $nova_pessoa->enderecos()->attach($novo_endereco);
    //             } else {
    //                 throw new Exception('Erro ao salvar o endereço da pessoa.');
    //             }
    //
    //             // Telefone
    //             $novo_telefone = new telefone();
    //             $novo_telefone->id_tipo_telefone = $request->id_tipo_telefone;
    //             $novo_telefone->id_classificacao_telefone = 1;
    //             $novo_telefone->nu_ddd = $request->nu_ddd;
    //             $novo_telefone->nu_telefone = Helper::somente_numeros($request->nu_telefone);
    //
    //             if ($novo_telefone->save()) {
    //                 $nova_pessoa->telefones()->attach($novo_telefone);
    //             } else {
    //                 throw new Exception('Erro ao salvar o telefone da pessoa.');
    //             }
    //
    //             if ($request->in_credor_fiduciario === 'S') {
    //                 // Salva a agencia
    //                 $agencia = new agencia();
    //                 $agencia->id_banco = $request->id_banco;
    //                 $agencia->codigo_agencia = $request->codigo_agencia;
    //                 $agencia->abv_agencia = '';
    //                 $agencia->no_agencia = $request->no_agencia;
    //                 $agencia->no_endereco = $request->no_endereco;
    //                 $agencia->no_bairro = $request->no_bairro;
    //                 $agencia->id_cidade = $request->id_cidade;
    //                 $agencia->nu_cep = preg_replace('#[^0-9]#', '', $request->nu_cep);
    //                 $agencia->nu_ddd = $request->nu_ddd;
    //                 $agencia->nu_fone = Helper::somente_numeros($request->nu_telefone);
    //                 $agencia->id_usuario_cad = Auth::User()->id_usuario;
    //
    //                 if ($agencia->save()) {
    //                     $id_agencia = $agencia->id_agencia;
    //                 } else {
    //                     throw new Exception('Erro ao salvar a agência.');
    //                 }
    //
    //                 // Insere o registro fiduciário credor
    //                 $registro_fiduciario_credor = new registro_fiduciario_credor();
    //                 $registro_fiduciario_credor->id_agencia = $id_agencia;
    //                 $registro_fiduciario_credor->id_cidade = $request->id_cidade;
    //                 $registro_fiduciario_credor->nu_cpf_cnpj = preg_replace('#[^0-9]#', '', $request->nu_cnpj);
    //                 $registro_fiduciario_credor->no_credor = $request->no_agencia;
    //                 $registro_fiduciario_credor->no_endereco = $request->no_endereco;
    //                 $registro_fiduciario_credor->nu_endereco = $request->nu_endereco;
    //                 $registro_fiduciario_credor->no_complemento = $request->no_complemento;
    //                 $registro_fiduciario_credor->no_bairro = $request->no_bairro;
    //                 $registro_fiduciario_credor->nu_cep = preg_replace('#[^0-9]#', '', $request->nu_cep);
    //                 $registro_fiduciario_credor->id_usuario_cad = Auth::User()->id_usuario;
    //
    //                 if (!$registro_fiduciario_credor->save()) {
    //                     throw new Exception('Erro ao salvar o registro fiduciário credor.');
    //                 }
    //             }
    //
    //             if ($request->in_usuario_existente=='S') {
    //                 $pessoa_existente = new pessoa();
    //                 $pessoa_existente = $pessoa_existente->where('nu_cpf_cnpj', $request->nu_cpf_usuario_existente)->first();
    //
    //                 // Vínculo do usuário
    //                 $novo_usuario_pessoa_usuario = new usuario_pessoa();
    //                 $novo_usuario_pessoa_usuario->id_usuario = $pessoa_existente->usuario->id_usuario;
    //                 $novo_usuario_pessoa_usuario->id_pessoa = $nova_pessoa->id_pessoa;
    //                 $novo_usuario_pessoa_usuario->in_usuario_master = 'S';
    //                 $novo_usuario_pessoa_usuario->id_usuario_cad = Auth::User()->id_usuario;
    //                 if (!$novo_usuario_pessoa_usuario->save()) {
    //                     throw new Exception("Erro ao salvar o vínculo do usuário.");
    //                 }
    //             } else {
    //                 $nova_pessoa_usuario = new pessoa();
    //                 $nova_pessoa_usuario->no_pessoa = $request->no_pessoa_usuario;
    //                 $nova_pessoa_usuario->tp_pessoa = 'F';
    //                 $nova_pessoa_usuario->no_email_pessoa = $request->email_usuario;
    //                 $nova_pessoa_usuario->nu_cpf_cnpj = preg_replace('#[^0-9]#', '', $request->nu_cpf_usuario);
    //                 $nova_pessoa_usuario->dt_nascimento = ($request->dt_nascimento_usuario ? Carbon::createFromFormat('d/m/Y', $request->dt_nascimento_usuario) : NULL);
    //                 $nova_pessoa_usuario->tp_sexo = $request->tp_sexo_usuario;
    //                 $nova_pessoa_usuario->id_tipo_pessoa = 5;
    //
    //                 if ($nova_pessoa_usuario->save()) {
    //                     $novo_usuario = new usuario();
    //                     $novo_usuario->id_pessoa = $nova_pessoa_usuario->id_pessoa;
    //                     $novo_usuario->codigo_usuario = '';
    //                     $novo_usuario->no_usuario = $nova_pessoa_usuario->no_pessoa;
    //                     $novo_usuario->email_usuario = $request->email_usuario;
    //                     $novo_usuario->login = $request->email_usuario;
    //                     $novo_usuario->dt_usuario_ativo = Carbon::now();
    //                     $novo_usuario->in_registro_ativo = 'S';
    //                     $novo_usuario->id_unidade_gestora = 1;
    //                     $novo_usuario->in_usuario_master = 'S';
    //                     $novo_usuario->id_usuario_cad = Auth::User()->id_usuario;
    //                     $novo_usuario->dt_ini_periodo = Carbon::now();
    //                     $novo_usuario->in_confirmado = 'S';
    //                     $novo_usuario->dt_usuario_confirmado = Carbon::now();
    //                     $novo_usuario->in_aprovado = 'S';
    //                     $novo_usuario->dt_usuario_aprovado = Carbon::now();
    //
    //                     if ($novo_usuario->save()) {
    //                         $senha_gerada = Str::random(10);
    //
    //                         $nova_senha = new usuario_senha();
    //                         $nova_senha->id_usuario = $novo_usuario->id_usuario;
    //                         $nova_senha->dt_ini_periodo = Carbon::now();
    //                         $nova_senha->in_alterar_senha = 'S';
    //                         $nova_senha->senha = Hash::make($senha_gerada);
    //                         if (!$nova_senha->save()) {
    //                             throw new Exception('Erro ao salvar a senha do usuário.');
    //                         }
    //
    //                         // Vínculo do usuário
    //                         $novo_usuario_pessoa_usuario = new usuario_pessoa();
    //                         $novo_usuario_pessoa_usuario->id_usuario = $novo_usuario->id_usuario;
    //                         $novo_usuario_pessoa_usuario->id_pessoa = $nova_pessoa->id_pessoa;
    //                         $novo_usuario_pessoa_usuario->in_usuario_master = 'S';
    //                         $novo_usuario_pessoa_usuario->id_usuario_cad = Auth::User()->id_usuario;
    //                         if (!$novo_usuario_pessoa_usuario->save()) {
    //                             throw new Exception("Erro ao salvar o vínculo do usuário.");
    //                         }
    //                     } else {
    //                         throw new Exception('Erro ao salvar o usuário.');
    //                     }
    //                 } else {
    //                     throw new Exception('Erro ao salvar a pessoa do usuário.');
    //                 }
    //             }
    //         } else {
    //             throw new Exception('Erro ao salvar a pessoa do banco.');
    //         }
    //
    //         LogDB::insere(
    //             Auth::user()->id_usuario,
    //             6,
    //             'O banco foi inserido com sucesso.',
    //             'Manter Bancos',
    //             'N',
    //             request()->ip()
    //         );
    //
    //         if ($request->in_usuario_existente=='N') {
    //             Mail::to($novo_usuario->email_usuario, $novo_usuario->no_usuario)->queue(new UsuariosNovoUsuarioMail($novo_usuario, [$nova_pessoa], $senha_gerada));
    //         }
    //
    //         DB::commit();
    //
    //         $response_json = [
    //             'status'=> 'sucesso',
    //             'recarrega' => 'true',
    //             'message' => 'O informante foi inserido com sucesso.'
    //         ];
    //         return response()->json($response_json);
    //     } catch(Exception $e) {
    //         DB::rollback();
    //
    //         LogDB::insere(
    //             Auth::user()->id_usuario,
    //             6,
    //             'Erro na Inserção de banco.',
    //             'Manter Bancos',
    //             'N',
    //             request()->ip(),
    //             ' Linha ' . $e->getLine() . ' do arquivo ' . $e->getFile() . '.'
    //         );
    //
    //         $response_json = [
    //             'status'=> 'erro',
    //             'recarrega' => 'false',
    //             'message' => $e->getMessage() . (config('app.env')!='production'?' Linha ' . $e->getLine() . ' do arquivo ' . $e->getFile() . '.':'')
    //         ];
    //         return response()->json($response_json);
    //     }
    // }
    //
    //
    // public function alterar_banco(Request $request, estado $estado, pessoa $pessoa,cidade $cidades)
    // {
    //     $pessoa = $pessoa->find($request->id_pessoa);
    //     $estados = $estado->orderBy('no_estado')->get();
	// 	$cidades = $cidades->where('id_estado', $pessoa->enderecos[0]->cidade->id_estado)->orderBy('cidade.no_cidade')->get();
    //
    //     // Argumentos para o retorno da view
    //     $compact_args = [
    //         'request' => $request,
    //         'class' => $this,
    //         'estados' => $estados,
    //         'cidades' => $cidades,
    //         'pessoa' => $pessoa
    //     ];
    //     return view('app.configuracoes.bancos.geral-alterar-banco', $compact_args);
    // }
    //
    // public function salvar_banco(BancosAlterar $request)
    // {
    //     DB::beginTransaction();
    //
    //     try {
    //         $pessoa = new pessoa;
    //         $pessoa_banco = $pessoa->find($request->id_pessoa);
    //
    //         $pessoa_banco->no_pessoa = $request->no_pessoa;
    //         $pessoa_banco->tp_pessoa = 'J';
    //         $pessoa_banco->no_email_pessoa = $request->no_email_pessoa;
    //         $pessoa_banco->nu_cpf_cnpj = preg_replace('#[^0-9]#', '', $request->nu_cnpj);
    //         $pessoa_banco->nu_inscricao_municipal = $request->nu_inscricao_municipal;
    //         $pessoa_banco->no_fantasia = $request->no_fantasia;
    //         $pessoa_banco->tp_sexo = 'N';
    //         $pessoa_banco->id_tipo_pessoa = 8;
    //
    //         if ($pessoa_banco->save()) {
    //
    //             /* Verifica endereços antigos
    // 			 * 		Se houver qualquer diferença entre o endereço recebido via
    // 			 *		formulário e o existente no sistema, o sistema deverá:
    // 			 *			- Desabilitar os endereços antigos;
    // 			 *			- Inserir o novo endereço.
    // 			 */
    // 			$insere_novo_endereco = false;
    // 			$desativar_enderecos_antigos = false;
    //             if (count($pessoa_banco->enderecos)>0) {
    //                 $endereco = $pessoa_banco->enderecos[0];
    //                 if ($endereco->id_cidade != $request->id_cidade or
    //                     $endereco->no_endereco != $request->no_endereco or
    //                     $endereco->nu_endereco != $request->nu_endereco or
    //                     $endereco->no_bairro != $request->no_bairro or
    //                     $endereco->nu_cep != Helper::somente_numeros($request->nu_cep) or
    //                     $endereco->no_complemento != $request->no_complemento)
    //                 {
    //                     $insere_novo_endereco = true;
    //                     $desativar_enderecos_antigos = true;
    //                 }
    //             } else {
    //                 $insere_novo_endereco = true;
    //             }
    //
    // 			if ($desativar_enderecos_antigos) {
    // 				$desativar_enderecos = new pessoa_endereco();
    // 				$desativar_enderecos = $desativar_enderecos->where('id_pessoa', $pessoa_banco->id_pessoa)->first();
    // 				if (!$desativar_enderecos->update(['in_registro_ativo' => 'N'])) {
    // 					throw new Exception('Erro ao desativar os endereços antigos.');
    // 				}
    // 			}
    //
    // 			if ($insere_novo_endereco) {
    // 				$novo_endereco = new endereco();
    // 				$novo_endereco->id_cidade = $request->id_cidade;
    // 				$novo_endereco->no_endereco = $request->no_endereco;
    // 				$novo_endereco->nu_endereco = $request->nu_endereco;
    // 				$novo_endereco->no_bairro = $request->no_bairro;
    // 				$novo_endereco->nu_cep = Helper::somente_numeros($request->nu_cep);
    // 				$novo_endereco->no_complemento = $request->no_complemento;
    //
    // 				if ($novo_endereco->save()) {
    //                     $pessoa_banco->enderecos()->attach($novo_endereco);
    // 				} else {
    // 					throw new Exception('Erro ao salvar o novo endereço do banco.');
    // 				}
    // 			}
    //
    // 			/* Verifica telefones antigos
    // 			 * 		Se houver qualquer diferença entre o telefones recebido via
    // 			 *		formulário e o existente no sistema, o sistema deverá:
    // 			 *			- Desabilitar os telefones antigos;
    // 			 *			- Inserir o novo telefone.
    // 			 */
    // 			$insere_novo_telefone = false;
    // 			$desativar_telefones_antigos = false;
    //
    //             if (count($pessoa_banco->telefones)>0) {
    //                 $telefone = $pessoa_banco->telefones[0];
    //                 if ($telefone->id_tipo_telefone != $request->id_tipo_telefone or
    //                     trim($telefone->nu_ddd) != $request->nu_ddd or
    //                     trim($telefone->nu_telefone) != Helper::somente_numeros($request->nu_telefone))
    //                 {
    //                     $insere_novo_telefone = true;
    //                     $desativar_telefones_antigos = true;
    //                 }
    //             } else {
    //                 $insere_novo_telefone = true;
    //             }
    //
    // 			if ($desativar_telefones_antigos) {
    // 				$desativar_telefones = new pessoa_telefone();
    // 				$desativar_telefones = $desativar_telefones->where('id_pessoa', $pessoa_banco->id_pessoa);
    // 				if (!$desativar_telefones->update(['in_registro_ativo' => 'N'])) {
    // 					throw new Exception('Erro ao desativar os telefones antigos.');
    // 				}
    //
    // 				$telefone = $pessoa_banco->telefones[0];
    // 				$telefone->in_registro_ativo='N';
    // 				if (!$telefone->save()) {
    // 					throw new Exception('Erro ao desativar o telefone antigo.');
    // 				}
    // 			}
    //
    // 			if ($insere_novo_telefone) {
    // 				$novo_telefone = new telefone();
    // 				$novo_telefone->id_tipo_telefone = $request->id_tipo_telefone;
    // 				$novo_telefone->id_classificacao_telefone = 1;
    // 				$novo_telefone->nu_ddd = $request->nu_ddd;
    // 				$novo_telefone->nu_telefone = Helper::somente_numeros($request->nu_telefone);
    //
    // 				if ($novo_telefone->save()) {
    //                     $pessoa_banco->telefones()->attach($novo_telefone);
    // 				} else {
    // 					throw new Exception('Erro ao salvar o novo telefone da pessoa.');
    // 				}
    // 			}
    //         } else {
    //             throw new Exception('Erro ao alterar a pessoa do banco.');
    //         }
    //
    //         LogDB::insere(
    //             Auth::user()->id_usuario,
    //             6,
    //             'Alteração de banco. ('.$pessoa_banco->id_pessoa.')',
    //             'Manter Bancos',
    //             'N',
    //             request()->ip()
    //         );
    //
    //         DB::commit();
    //
    //         $response_json = [
    //             'status'=> 'sucesso',
    //             'recarrega' => 'true',
    //             'message' => 'O banco foi alterado com sucesso.'
    //         ];
    //         return response()->json($response_json);
    //     } catch(Exception $e) {
    //         DB::rollback();
    //
    //         LogDB::insere(
    //             Auth::user()->id_usuario,
    //             6,
    //             'Erro na alteração de banco.',
    //             'Manter Bancos',
    //             'N',
    //             request()->ip(),
    //             $e->getMessage() . ' na linha ' . $e->getLine() . ' do arquivo ' . $e->getFile()
    //         );
    //
    //         $response_json = [
    //             'status'=> 'erro',
    //             'recarrega' => 'false',
    //             'message' => $e->getMessage() . (config('app.env')!='production'?' Linha ' . $e->getLine() . ' do arquivo ' . $e->getFile() . '.':'')
    //         ];
    //         return response()->json($response_json);
    //     }
    // }
}
