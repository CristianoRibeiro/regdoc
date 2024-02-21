<?php

namespace App\Http\Controllers;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Configuracoes\MinhaContaDadosAcessoSalvar;
use App\Http\Requests\Configuracoes\MinhaContaDadosPessoaisSalvar;
use App\Http\Requests\Configuracoes\MinhaContaDadosServentiaSalvar;
use App\Models\cidade;
use App\Models\endereco;
use App\Models\estado;
use App\Models\pessoa;
use App\Models\pessoa_endereco;
use App\Models\pessoa_telefone;
use App\Models\telefone;
use App\Models\usuario;
use App\Models\usuario_2fa_email;
use App\Domain\Usuario\Models\usuario_key;
use Illuminate\Http\Request;

use Auth;
use Illuminate\Support\Str;
use Session;
use DB;
use LogDB;
use Exception;
use Carbon\Carbon;
use Hash;
use Mail;

use App\Models\usuario_senha;

use App\Http\Requests\Configuracoes\SalvaAcesso;

class UsuarioController extends Controller {

	public function configuracoes(Request $request)
    {
        // Argumentos para o retorno da view
        $compact_args = [
			'request' => $request,
            'class' => $this
        ];

        return view('app.configuracoes.usuario.geral-configuracoes', $compact_args);
    }

	public function salvar_acesso(SalvaAcesso $request) {
        DB::beginTransaction();

        try {
			$senha_atual = Auth::User()->usuario_senha()->orderBy('dt_cadastro', 'desc')->first();
			if (!Hash::check($request->senha_atual, $senha_atual->senha)) {
				return response()->json([
	                'status' => 'alerta',
	                'recarrega' => 'false',
	                'message' => 'A senha atual digitada é inválida.',
	            ]);
			}
			// Atualiza a senha atual
            $senha_atual->dt_fim_periodo = Carbon::now();
			if (!$senha_atual->save()) {
				throw new Exception('Erro ao desabilitar a senha antiga no banco de dados.');
			}

			// Verifica se a senha não foi utilizada anteriormente
			$senhas = new usuario_senha();
            $senhas = $senhas->where('id_usuario', Auth::User()->id_usuario)->get();
            $senha_existente = false;

            if (count($senhas) > 0) {
                foreach ($senhas as $senha) {
                    if (Hash::check($request->nova_senha, $senha->senha)) {
                        $senha_existente = true;
                    }
                }
            }
            if ($senha_existente) {
				return response()->json([
	                'status' => 'alerta',
	                'recarrega' => 'false',
	                'message' => 'A nova senha digitada já foi utilizada.',
	            ]);
            }

			$nova_senha = new usuario_senha();
			$nova_senha->dt_ini_periodo = Carbon::now();
			$nova_senha->id_usuario = Auth::User()->id_usuario;
			$nova_senha->senha = Hash::make($request->nova_senha);

			if (!$nova_senha->save()) {
				throw new Exception('Erro ao salvar a nova senha no banco de dados.');
			}

            DB::commit();
            return response()->json([
                'status' => 'sucesso',
                'recarrega' => 'true',
                'message' => 'A senha foi atualizada com sucesso.',
            ]);
        } catch (Exception $e) {
            DB::rollback();

            return response()->json([
                'status' => 'erro',
                'recarrega' => 'false',
                'message' => 'Por favor, tente novamente mais tarde.' . (config('app.env')!='production'? ' Descrição: '.$e->getMessage().' - Linha: '.$e->getLine() : '')
            ], 500);
        }
    }

    /* Função que realiza a troca de pessoa ativa do usuário
     */
	public function troca_pessoa(Request $request)
	{
		if ($request->key>=0) {
			$usuario_pessoas = Auth::User()->usuario_pessoa;
			if (isset($usuario_pessoas[$request->key])) {
				Auth::User()->pessoa_ativa = $usuario_pessoas[$request->key]->pessoa;
                Auth::User()->pessoa_ativa_in_usuario_master = $usuario_pessoas[$request->key]->in_usuario_master;
				return response(200);
			} else {
				$response_json = [
	                'message' => 'O vínculo selecionado não existe.'
	            ];
	            return response()->json($response_json,400);
			}
		} else {
			$response_json = [
                'message' => 'A chave do vínculo não foi informada.'
            ];
            return response()->json($response_json,400);
		}
	}

    /**
     * @param Request $request
     * @param estado $estado
     * @param pessoa $pessoa
     * @param cidade $cidade
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application|\Illuminate\View\View
     */
    public function minha_conta(Request $request, estado $estado, pessoa $pessoa, cidade $cidade)
    {
        $estados = $estado->orderBy('no_estado')->get();

        $pessoa_usuario = $pessoa->find(Auth::User()->pessoa->id_pessoa);
        $cidades_usuario = [];
        if(count($pessoa_usuario->enderecos)>0) {
            $cidades_usuario = $cidade->where('id_estado', $pessoa_usuario->enderecos[0]->cidade->id_estado)->orderBy('cidade.no_cidade')->get();
        }

        $pessoa_serventia = NULL;
        $cidades_serventia = [];
        if (Auth::User()->pessoa_ativa->id_tipo_pessoa==2) {
            $pessoa_serventia = $pessoa->find(Auth::User()->pessoa_ativa->id_pessoa);
            if(count($pessoa_serventia->enderecos)>0) {
                $cidades_serventia = $cidade->where('id_estado', $pessoa_serventia->enderecos[0]->cidade->id_estado)->orderBy('cidade.no_cidade')->get();
            }
        }

        $usuario_key = NULL;
        if (in_array(Auth::User()->pessoa_ativa->id_tipo_pessoa, [8])) {
            $usuario_key = new usuario_key();
            $usuario_key = $usuario_key->where('id_usuario', '=', Auth::User()->id_usuario)
                ->where('id_pessoa', Auth::User()->pessoa_ativa->id_pessoa)
                ->where('in_registro_ativo', '=', 'S')
                ->first();
        }

        // Argumentos para o retorno da view
        $compact_args = [
            'request' => $request,
            'class' => $this,
            'estados' => $estados,
            'pessoa_usuario' => $pessoa_usuario,
            'cidades_usuario' => $cidades_usuario,
            'pessoa_serventia' => $pessoa_serventia,
            'cidades_serventia' => $cidades_serventia,
            'usuario_key' => $usuario_key
        ];


        return view('app.usuario.geral-minha-conta', $compact_args);

    }

    /**
     * @param MinhaContaDadosPessoaisSalvar $request
     * @param pessoa $pessoa
     * @return \Illuminate\Http\JsonResponse
     */
    public function salvar_dados_pessoais(MinhaContaDadosPessoaisSalvar $request, pessoa $pessoa)
    {
        DB::beginTransaction();

        try {
            $pessoa_usuario = $pessoa->find(Auth::User()->pessoa->id_pessoa);

            $atualizar_pessoa = $pessoa_usuario;
            $atualizar_pessoa->no_pessoa = $request->no_pessoa;
            $atualizar_pessoa->tp_pessoa = $request->tp_pessoa;
            if ($request->tp_pessoa=='F') {
                $atualizar_pessoa->no_pessoa = $request->no_pessoa_pf;
                $atualizar_pessoa->nu_cpf_cnpj = preg_replace( '#[^0-9]#', '', $request->nu_cpf_cnpj_pf);
                $atualizar_pessoa->dt_nascimento = Carbon::createFromFormat('d/m/Y',$request->dt_nascimento);
                $atualizar_pessoa->tp_sexo = $request->tp_sexo;
            } elseif ($request->tp_pessoa=='J') {
                $atualizar_pessoa->no_pessoa = $request->no_pessoa_pj;
                $atualizar_pessoa->nu_cpf_cnpj = preg_replace( '#[^0-9]#', '', $request->nu_cpf_cnpj_pj);
                $atualizar_pessoa->nu_inscricao_municipal = $request->nu_inscricao_municipal;
                $atualizar_pessoa->no_fantasia = $request->no_fantasia;
                $atualizar_pessoa->tp_sexo = 'N';
            }

            if (!$atualizar_pessoa->save()) {
                throw new Exception('Erro ao atualizar a pessoa do usuário.');
            }

            $atualizar_usuario = $pessoa_usuario->usuario;
            $atualizar_usuario->no_usuario = $atualizar_pessoa->no_pessoa;

            if (!$atualizar_usuario->save()) {
                throw new Exception('Erro ao atualizar o usuário.');
            }

            /* Verifica endereços antigos
             * 		Se houver qualquer diferença entre o endereço recebido via
             *		formulário e o existente no sistema, o sistema deverá:
             *			- Desabilitar os endereços antigos;
             *			- Inserir o novo endereço.
             */
            $insere_novo_endereco = false;
            $desativar_enderecos_antigos = false;
            if ($request->in_digitar_endereco=='S') {
                if (count($pessoa_usuario->enderecos)>0) {
                    $endereco = $pessoa_usuario->enderecos[0];
                    if ($endereco->id_cidade != $request->id_cidade or
                        $endereco->no_endereco != $request->no_endereco or
                        $endereco->nu_endereco != $request->nu_endereco or
                        $endereco->no_bairro != $request->no_bairro or
                        $endereco->nu_cep != Helper::somente_numeros($request->nu_cep) or
                        $endereco->no_complemento != $request->no_complemento)
                    {
                        $insere_novo_endereco = true;
                        $desativar_enderecos_antigos = true;
                    }
                } else {
                    $insere_novo_endereco = true;
                }
            } else {
                if (count($pessoa_usuario->enderecos)>0) {
                    $desativar_enderecos_antigos = true;
                }
            }

            if ($desativar_enderecos_antigos) {
                $desativar_enderecos = new pessoa_endereco();
                $desativar_enderecos = $desativar_enderecos->where('id_pessoa', $pessoa_usuario->id_pessoa);
                if (!$desativar_enderecos->update(['in_registro_ativo' => 'N'])) {
                    throw new Exception('Erro ao desativar os endereços antigos.');
                }
            }

            if ($insere_novo_endereco) {
                $novo_endereco = new endereco();
                $novo_endereco->id_cidade = $request->id_cidade;
                $novo_endereco->no_endereco = $request->no_endereco;
                $novo_endereco->nu_endereco = $request->nu_endereco;
                $novo_endereco->no_bairro = $request->no_bairro;
                $novo_endereco->nu_cep = Helper::somente_numeros($request->nu_cep);
                $novo_endereco->no_complemento = $request->no_complemento;

                if ($novo_endereco->save()) {
                    $pessoa_usuario->enderecos()->attach($novo_endereco);
                } else {
                    throw new Exception('Erro ao salvar o novo endereço da pessoa.');
                }
            }

            /* Verifica telefones antigos
             * 		Se houver qualquer diferença entre o telefones recebido via
             *		formulário e o existente no sistema, o sistema deverá:
             *			- Desabilitar os telefones antigos;
             *			- Inserir o novo telefone.
             */
            $insere_novo_telefone = false;
            $desativar_telefones_antigos = false;
            if ($request->in_digitar_telefone=='S') {
                if (count($pessoa_usuario->telefones)>0) {
                    $telefone = $pessoa_usuario->telefones[0];

                    if ($telefone->id_tipo_telefone != $request->id_tipo_telefone or
                        trim($telefone->nu_ddd) != $request->nu_ddd or
                        trim($telefone->nu_telefone) != Helper::somente_numeros($request->nu_telefone))
                    {
                        $insere_novo_telefone = true;
                        $desativar_telefones_antigos = true;
                    }
                } else {
                    $insere_novo_telefone = true;
                }
            } else {
                if (count($pessoa_usuario->telefones)>0) {
                    $desativar_telefones_antigos = true;
                }
            }

            if ($desativar_telefones_antigos) {
                $desativar_telefones = new pessoa_telefone();
                $desativar_telefones = $desativar_telefones->where('id_pessoa', $pessoa_usuario->id_pessoa);

                if (!$desativar_telefones->update(['in_registro_ativo' => 'N'])) {
                    throw new Exception('Erro ao desativar os telefones antigos.');
                }

                $telefone = $pessoa_usuario->telefones[0];
                $telefone->in_registro_ativo='N';
                if (!$telefone->save()) {
                    throw new Exception('Erro ao desativar o telefone antigo.');
                }
            }

            if ($insere_novo_telefone) {
                $novo_telefone = new telefone();
                $novo_telefone->id_tipo_telefone = $request->id_tipo_telefone;
                $novo_telefone->id_classificacao_telefone = 1;
                $novo_telefone->nu_ddd = $request->nu_ddd;
                $novo_telefone->nu_telefone = Helper::somente_numeros($request->nu_telefone);

                if ($novo_telefone->save()) {
                    $pessoa_usuario->telefones()->attach($novo_telefone);
                } else {
                    throw new Exception('Erro ao salvar o novo telefone da pessoa.');
                }
            }

            DB::commit();

            $response_json = [
                'status' => 'sucesso',
                'recarrega' => 'true',
                'message' => 'Seus dados foram atualizados com sucesso.'
            ];

            LogDB::insere(
                Auth::User()->id_usuario,
                2,
                'Alterou os dados pessoais.',
                'Minha Conta',
                'N',
                request()->ip()
            );

            return response()->json($response_json);
        } catch(Exception $e) {
            DB::rollback();

            $response_json = [
                'status' => 'erro',
                'recarrega' => 'false',
                'message' => 'Por favor, tente novamente mais tarde.' . (config('app.env')!='production'? ' Descrição: '.$e->getMessage().' - Linha: '.$e->getLine(). ' - Arquivo: ' . $e->getFile() : '')
            ];

            return response()->json($response_json, 500);
        }
    }

    /**
     * @param MinhaContaDadosAcessoSalvar $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function salvar_dados_acesso(MinhaContaDadosAcessoSalvar $request)
    {
        DB::beginTransaction();

        try {
            $senha_atual = Auth::User()->usuario_senha()->orderBy('dt_cadastro', 'desc')->first();
            if (!Hash::check($request->senha_atual, $senha_atual->senha)) {
                return response()->json([
                    'status' => 'alerta',
                    'recarrega' => 'false',
                    'message' => 'A senha atual digitada é inválida.',
                ]);
            }
            // Atualiza a senha atual
            $senha_atual->dt_fim_periodo = Carbon::now();
            if (!$senha_atual->save()) {
                throw new Exception('Erro ao desabilitar a senha antiga no banco de dados.');
            }

            // Verifica se a senha não foi utilizada anteriormente
            $senhas = new usuario_senha();
            $senhas = $senhas->where('id_usuario', Auth::User()->id_usuario)->get();

            $senha_existente = false;
            if (count($senhas) > 0) {
                foreach ($senhas as $senha) {
                    if (Hash::check($request->nova_senha, $senha->senha)) {
                        $senha_existente = true;
                    }
                }
            }
            if ($senha_existente) {
                return response()->json([
                    'status' => 'alerta',
                    'recarrega' => 'false',
                    'message' => 'A nova senha digitada já foi utilizada.',
                ]);
            }

            $nova_senha = new usuario_senha();
            $nova_senha->dt_ini_periodo = Carbon::now();
            $nova_senha->id_usuario = Auth::User()->id_usuario;
            $nova_senha->senha = Hash::make($request->nova_senha);
            if (!$nova_senha->save()) {
                throw new Exception('Erro ao salvar a nova senha no banco de dados.');
            }

            DB::commit();

            $response_json = [
                'status' => 'sucesso',
                'recarrega' => 'true',
                'message' => 'A senha foi atualizada com sucesso.',
            ];

            LogDB::insere(
                Auth::User()->id_usuario,
                2,
                'Alterou os dados de acesso.',
                'Minha Conta',
                'N',
                request()->ip()
            );

            return response()->json($response_json);
        } catch (Exception $e) {
            DB::rollback();

            $response_json = [
                'status' => 'erro',
                'recarrega' => 'false',
                'message' => 'Por favor, tente novamente mais tarde.' . (config('app.env')!='production'? ' Descrição: '.$e->getMessage().' - Linha: '.$e->getLine(). ' - Arquivo: ' . $e->getFile() : '')
            ];

            return response()->json($response_json, 500);
        }
    }

    /**
     * @param Request $request
     * @param pessoa $pessoa
     * @return \Illuminate\Http\JsonResponse
     */
    public function salvar_dados_serventia(MinhaContaDadosServentiaSalvar $request, pessoa $pessoa)
    {
        DB::beginTransaction();

        try {
            $pessoa_serventia = $pessoa->find(Auth::User()->pessoa_ativa->id_pessoa);

            $atualizar_pessoa = $pessoa_serventia;
            $atualizar_pessoa->no_pessoa = $request->no_serventia;
            if ($request->in_cartorio_cnpj=='S') {
                $atualizar_pessoa->tp_pessoa = 'J';
                $atualizar_pessoa->nu_cpf_cnpj = preg_replace('#[^0-9]#', '', $request->nu_cpf_cnpj);
            } else {
                $atualizar_pessoa->tp_pessoa = 'N';
                $atualizar_pessoa->nu_cpf_cnpj = NULL;
            }
            $atualizar_pessoa->nu_inscricao_municipal = $request->nu_inscricao_municipal;
            $atualizar_pessoa->no_fantasia = $request->no_serventia;

            if (!$atualizar_pessoa->save()) {
                throw new Exception('Erro ao atualizar a pessoa da serventia.');
            }

            $atualizar_serventia = $pessoa_serventia->serventia;
            $atualizar_serventia->no_serventia = $request->no_serventia;
            $atualizar_serventia->no_titulo = $request->no_titulo;

            $atualizar_serventia->hora_inicio_expediente = $request->hora_inicio_expediente;
            $atualizar_serventia->hora_termino_expediente = $request->hora_termino_expediente;
            $atualizar_serventia->hora_inicio_almoco = $request->hora_inicio_almoco;
            $atualizar_serventia->hora_termino_almoco = $request->hora_termino_almoco;
            $atualizar_serventia->no_oficial = $request->no_oficial;
            $atualizar_serventia->no_substituto = $request->no_substituto;
            $atualizar_serventia->codigo_cns = $request->codigo_cns;
            $atualizar_serventia->dv_codigo_cns = $request->dv_codigo_cns;
            if (!$atualizar_serventia->save()){
                throw new Exception('Erro ao atualizar a serventia.');
            }

            /* Verifica endereços antigos
             * 		Se houver qualquer diferença entre o endereço recebido via
             *		formulário e o existente no sistema, o sistema deverá:
             *			- Desabilitar os endereços antigos;
             *			- Inserir o novo endereço.
             */
            $insere_novo_endereco = false;
            $desativar_enderecos_antigos = false;
            if ($request->in_digitar_endereco=='S') {
                if (count($pessoa_serventia->enderecos)>0) {
                    $endereco = $pessoa_serventia->enderecos[0];
                    if ($endereco->id_cidade != $request->id_cidade or
                        $endereco->no_endereco != $request->no_endereco or
                        $endereco->nu_endereco != $request->nu_endereco or
                        $endereco->no_bairro != $request->no_bairro or
                        $endereco->nu_cep != Helper::somente_numeros($request->nu_cep) or
                        $endereco->no_complemento != $request->no_complemento)
                    {
                        $insere_novo_endereco = true;
                        $desativar_enderecos_antigos = true;
                    }
                } else {
                    $insere_novo_endereco = true;
                }
            } else {
                if (count($pessoa_serventia->enderecos)>0) {
                    $desativar_enderecos_antigos = true;
                }
            }

            if ($desativar_enderecos_antigos) {
                $desativar_enderecos = new pessoa_endereco();
                $desativar_enderecos = $desativar_enderecos->where('id_pessoa', $pessoa_serventia->id_pessoa);
                if (!$desativar_enderecos->update(['in_registro_ativo' => 'N'])) {
                    throw new Exception('Erro ao desativar os endereços antigos.');
                }
            }

            if ($insere_novo_endereco) {
                $novo_endereco = new endereco();
                $novo_endereco->id_cidade = $request->id_cidade;
                $novo_endereco->no_endereco = $request->no_endereco;
                $novo_endereco->nu_endereco = $request->nu_endereco;
                $novo_endereco->no_bairro = $request->no_bairro;
                $novo_endereco->nu_cep = Helper::somente_numeros($request->nu_cep);
                $novo_endereco->no_complemento = $request->no_complemento;

                if ($novo_endereco->save()) {
                    $pessoa_serventia->enderecos()->attach($novo_endereco);
                } else {
                    throw new Exception('Erro ao salvar o novo endereço da pessoa.');
                }
            }

            /* Verifica telefones antigos
             * 		Se houver qualquer diferença entre o telefones recebido via
             *		formulário e o existente no sistema, o sistema deverá:
             *			- Desabilitar os telefones antigos;
             *			- Inserir o novo telefone.
             */
            $insere_novo_telefone = false;
            $desativar_telefones_antigos = false;
            if ($request->in_digitar_telefone=='S') {
                if (count($pessoa_serventia->telefones)>0) {
                    $telefone = $pessoa_serventia->telefones[0];

                    if ($telefone->id_tipo_telefone != $request->id_tipo_telefone or
                        trim($telefone->nu_ddd) != $request->nu_ddd or
                        trim($telefone->nu_telefone) != Helper::somente_numeros($request->nu_telefone))
                    {
                        $insere_novo_telefone = true;
                        $desativar_telefones_antigos = true;
                    }
                } else {
                    $insere_novo_telefone = true;
                }
            } else {
                if (count($pessoa_serventia->telefones)>0) {
                    $desativar_telefones_antigos = true;
                }
            }

            if ($desativar_telefones_antigos) {
                $desativar_telefones = new pessoa_telefone();
                $desativar_telefones = $desativar_telefones->where('id_pessoa', $pessoa_serventia->id_pessoa);
                if (!$desativar_telefones->update(['in_registro_ativo' => 'N'])) {
                    throw new Exception('Erro ao desativar os telefones antigos.');
                }

                $telefone = $pessoa_serventia->telefones[0];
                $telefone->in_registro_ativo='N';
                if (!$telefone->save()) {
                    throw new Exception('Erro ao desativar o telefone antigo.');
                }
            }

            if ($insere_novo_telefone) {
                $novo_telefone = new telefone();
                $novo_telefone->id_tipo_telefone = $request->id_tipo_telefone;
                $novo_telefone->id_classificacao_telefone = 1;
                $novo_telefone->nu_ddd = $request->nu_ddd;
                $novo_telefone->nu_telefone = Helper::somente_numeros($request->nu_telefone);

                if ($novo_telefone->save()) {
                    $pessoa_serventia->telefones()->attach($novo_telefone);
                } else {
                    throw new Exception('Erro ao salvar o novo telefone da pessoa.');
                }
            }

            DB::commit();

            $response_json = [
                'status' => 'sucesso',
                'recarrega' => 'true',
                'message' => 'Seus dados foram atualizados com sucesso.'
            ];

            LogDB::insere(
                Auth::User()->id_usuario,
                2,
                'Alterou dados de serventia.',
                'Minha Conta',
                'N',
                request()->ip()
            );

            return response()->json($response_json);
        } catch(Exception $e) {
            DB::rollback();

            $response_json = [
                'status' => 'erro',
                'recarrega' => 'false',
                'message' => 'Por favor, tente novamente mais tarde.' . (config('app.env')!='production'? ' Descrição: '.$e->getMessage().' - Linha: '.$e->getLine(). ' - Arquivo: ' . $e->getFile() : '')
            ];

            return response()->json($response_json, 500);
        }
    }

    /**
     * @param usuario_key $usuario_key
     * @return \Illuminate\Http\JsonResponse
     */
    public function salvar_dados_api(usuario_key $usuario_key) {
        DB::beginTransaction();

        try {
            $usuario_key->where('id_usuario', Auth::User()->id_usuario)
                ->where('id_pessoa', Auth::User()->pessoa_ativa->id_pessoa)
                ->update(['in_registro_ativo' => 'N']);

            $novo_usuario_key = new usuario_key();
            $novo_usuario_key->id_usuario = Auth::User()->id_usuario;
            $novo_usuario_key->id_pessoa = Auth::User()->pessoa_ativa->id_pessoa;
            $novo_usuario_key->no_codigo = Str::random(12);
            $novo_usuario_key->no_key = Str::random(64);
            $novo_usuario_key->in_registro_ativo = 'S';
            $novo_usuario_key->id_usuario_cad = Auth::User()->id_usuario;
            $novo_usuario_key->dt_cadastro = Carbon::now();
            if (!$novo_usuario_key->save()) {
                throw new Exception('Erro ao salvar a chave do usuário.');
            }

            DB::commit();

            $response_json = [
                'status' => 'sucesso',
                'recarrega' => 'true',
                'message' => 'Uma nova chave de API foi gerada com sucesso.'
            ];

            LogDB::insere(
                Auth::User()->id_usuario,
                2,
                'Alterou dados de api.',
                'Minha Conta',
                'N',
                request()->ip()
            );

            return response()->json($response_json);
        } catch(Exception $e) {
            DB::rollback();

            $response_json = [
                'status' => 'erro',
                'recarrega' => 'false',
                'message' => 'Por favor, tente novamente mais tarde.' . (config('app.env')!='production'? ' Descrição: '.$e->getMessage().' - Linha: '.$e->getLine(). ' - Arquivo: ' . $e->getFile() : '')
            ];

            return response()->json($response_json, 500);
        }
    }

    public function alterar_senha(Request $request)
    {
        // Argumentos para o retorno da view
        $compact_args = [
            'request' => $request,
            'class' => $this
        ];

        return view('app.usuario.geral-minha-conta-alterar-senha', $compact_args);

    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function salvar_autenticacao_email(Request $request)
    {
        if(Auth::User()->in_autenticacao_email_obrigatorio === 'S') {
            $response_json = [
                'status' => 'erro',
                'recarrega' => 'false',
                'message' => 'O seu usuário não permite a alteração desta opção.'
            ];

            return response()->json($response_json, 200);
        }
        DB::beginTransaction();

        try {
            $autenticacao = usuario::where('id_usuario', Auth::User()->id_usuario)->first();
            $autenticacao->in_autenticacao_email = $request->in_autenticacao_email;
            if (!$autenticacao->save()) {
                throw new Exception('Erro ao salvar a autenticação.');
            }

            $autenticacao->refresh();

            DB::commit();

            $response_json = [
                'status' => 'sucesso',
                'recarrega' => 'true',
                'message' => 'Duplo Fator de autenticação ativado com sucesso.'
            ];

            LogDB::insere(
                Auth::User()->id_usuario,
                2,
                'Ativou o duplo fator de autenticação.',
                'Minha Conta',
                'N',
                request()->ip()
            );

            return response()->json($response_json);
        } catch (Exception $e) {
            DB::rollback();

            $response_json = [
                'status' => 'erro',
                'recarrega' => 'false',
                'message' => 'Por favor, tente novamente mais tarde.' . (config('app.env')!='production'? ' Descrição: '.$e->getMessage().' - Linha: '.$e->getLine(). ' - Arquivo: ' . $e->getFile() : '')
            ];

            return response()->json($response_json, 500);
        }
    }
}
