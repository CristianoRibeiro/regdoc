<?php

namespace App\Domain\Usuario\Repositories;

use App\Domain\Pessoa\Models\pessoa;
use App\Domain\Usuario\Models\usuario;
use App\Domain\Usuario\Models\usuario_pessoa;
use App\Domain\Usuario\Contracts\UsuarioRepositoryInterface;
use App\Domain\Usuario\Models\usuario_senha;

use Carbon\Carbon;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

use Exception;
use stdClass;

class UsuarioRepository implements UsuarioRepositoryInterface
{
    /**
     * @param int $id_usuario
     * @return usuario|null
     */
    public function buscar(int $id_usuario) : ?usuario
    {
        return usuario::findOrFail($id_usuario);
    }

    /**
     * @param int $id_pessoa
     * @return Collection
     */
    public function listar_por_entidade(int $id_pessoa) : Collection
    {
        return usuario::select('usuario.*')
            ->join('usuario_pessoa', function($join) use ($id_pessoa) {
                $join->on('usuario_pessoa.id_usuario', '=', 'usuario.id_usuario')
                    ->where('usuario_pessoa.id_pessoa', $id_pessoa);
            })
            ->orderBy('usuario.no_usuario')
            ->get();
    }

    /**
     * @param stdClass $args
     * @return usuario
     * @throws Exception
     */
    public function inserir(stdClass $args): usuario
    {
        $novo_usuario = new usuario();
        $novo_usuario->id_pessoa = $args->id_pessoa;
        $novo_usuario->no_usuario = $args->no_usuario;
        $novo_usuario->email_usuario = mb_strtolower($args->email_usuario, 'UTF-8');
        $novo_usuario->login = $args->login ?? NULL;
        $novo_usuario->dt_ini_periodo = $args->dt_ini_periodo ?? Carbon::now();
        $novo_usuario->dt_fim_periodo = $args->dt_fim_periodo ?? NULL;
        $novo_usuario->in_alterar_senha = $args->in_alterar_senha ?? 'N';
        $novo_usuario->id_usuario_cad = Auth::User()->id_usuario;
        $novo_usuario->confirmar_token = $args->confirmar_token ?? NULL;
        $novo_usuario->in_registro_ativo = $args->in_registro_ativo ?? 'S';
        $novo_usuario->dt_usuario_ativo = Carbon::now();
        if (isset($args->in_confirmado)) {
            $novo_usuario->in_confirmado = $args->in_confirmado;
            $novo_usuario->dt_usuario_confirmado = ($args->in_confirmado == 'S' ? Carbon::now() : NULL);
        }
        if (isset($args->in_aprovado)) {
            $novo_usuario->in_aprovado = $args->in_aprovado;
            $novo_usuario->dt_usuario_aprovado = ($args->in_aprovado == 'S' ? Carbon::now() : NULL);
        }
        $novo_usuario->in_cliente = $args->in_cliente ?? 'N';

        if (!$novo_usuario->save()) {
            throw new Exception('Erro ao inserir o usuario.');
        }

        return $novo_usuario;
    }

    /**
     * @param usuario $usuario
     * @param stdClass $args
     * @return usuario
     * @throws Exception
     */
    public function alterar(usuario $usuario, stdClass $args) : usuario
    {
        if (isset($args->no_usuario)) {
            $usuario->no_usuario = $args->no_usuario;
        }
        if (isset($args->email_usuario)) {
            $usuario->email_usuario = $args->email_usuario;
        }
        if (isset($args->login)) {
            $usuario->login = $args->login;
        }
        if (!$usuario->save()) {
            throw new Exception('Erro ao atualizar o usuário.');
        }

        $usuario->refresh();

        return $usuario;
    }

    public function cadastrarUsuario(stdClass $args): usuario
    {
        $nova_pessoa = new pessoa();
        $nova_pessoa->no_pessoa = $args->nome_completo;
        $nova_pessoa->tp_pessoa = 'F';
        $nova_pessoa->nu_cpf_cnpj = '';
        $nova_pessoa->no_email_pessoa = $args->email;
        $nova_pessoa->id_tipo_pessoa = config('constants.USUARIO.ID_TIPO_PESSOA_USUARIO');

        if(!$nova_pessoa->save()) throw new Exception('Erro ao salvar a pessoa no banco de dados.');

        $novo_usuario = new usuario();
        $novo_usuario->id_pessoa = $nova_pessoa->id_pessoa;
        $novo_usuario->no_usuario = $args->nome_completo;
        $novo_usuario->email_usuario = $args->email;
        $novo_usuario->login = $args->email;
        $novo_usuario->dt_ini_periodo = Carbon::now();
        $novo_usuario->in_confirmado = 'S';
        $novo_usuario->in_aprovado = 'S';
        $novo_usuario->dt_usuario_confirmado = Carbon::now();
        $novo_usuario->dt_usuario_aprovado = Carbon::now();
        $novo_usuario->in_completar_cadastro = 'S';

        if(!$novo_usuario->save()) throw new Exception('Erro ao salvar o usuário no banco de dados.');

        $senha_gerada = Str::random(20);

        $novo_usuario_senha = new usuario_senha();
        $novo_usuario_senha->id_usuario = $novo_usuario->id_usuario;
        $novo_usuario_senha->senha = Hash::make($senha_gerada);
        $novo_usuario_senha->dt_ini_periodo = Carbon::now();
        $novo_usuario_senha->in_alterar_senha = 'N';

        if(!$novo_usuario_senha->save()) throw new Exception("Erro ao salvar a senha do usuário no banco de dados.");

        $novo_usuario_pessoa = new usuario_pessoa();
        $novo_usuario_pessoa->id_usuario = $novo_usuario->id_usuario;
        $novo_usuario_pessoa->id_pessoa = $args->id_pessoa_relacionada;
        $novo_usuario_pessoa->in_usuario_master = 'N';
        $novo_usuario_pessoa->id_usuario_cad = 1;

        if(!$novo_usuario_pessoa->save()) throw new Exception("Erro ao salvar o vínculo no banco de dados.");

        return $novo_usuario;
    }

    /**
     * @return false
     */
    public function getAuthPassword()
    {
        $senha = new usuario();

        if (count($senha->usuario_senha) > 0) {
            $senha->usuario_senha()->where('dt_ini_periodo', '<=', Carbon::now())
                ->where(function($where) {
                    $where->where('dt_fim_periodo', '>=', Carbon::now())
                        ->orWhereNull('dt_fim_periodo');
                })
                ->orderBy('dt_cadastro','desc')
                ->first()
                ->senha;
        } else {
            return false;
        }
    }
}
