<?php

namespace App\Domain\Usuario\Services;

use App\Domain\Usuario\Models\usuario;
use App\Domain\Usuario\Contracts\UsuarioServiceInterface;
use App\Domain\Usuario\Contracts\UsuarioRepositoryInterface;
use App\Domain\Pessoa\Contracts\PessoaServiceInterface;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;

use stdClass;

class UsuarioService implements UsuarioServiceInterface
{
    /**
     * @var UsuarioRepositoryInterface
     * @var PessoaServiceInterface
     */
    protected $UsuarioRepositoryInterface;
    protected $PessoaServiceInterface;

    /**
     * UsuarioService constructor.
     * @param UsuarioRepositoryInterface $UsuarioRepositoryInterface
     * @param PessoaServiceInterface $PessoaServiceInterface
     */
    public function __construct(UsuarioRepositoryInterface $UsuarioRepositoryInterface,
        PessoaServiceInterface $PessoaServiceInterface)
    {
        $this->UsuarioRepositoryInterface = $UsuarioRepositoryInterface;
        $this->PessoaServiceInterface = $PessoaServiceInterface;
    }

    /**
     * @param int $id_usuario
     * @return usuario|null
     */
    public function buscar(int $id_usuario) : ?usuario
    {
        return $this->UsuarioRepositoryInterface->buscar($id_usuario);
    }

    /**
     * @param int $id_pessoa
     * @return Collection
     */
    public function listar_por_entidade(int $id_pessoa) : Collection
    {
        return $this->UsuarioRepositoryInterface->listar_por_entidade($id_pessoa);
    }

    /**
     * @param stdClass $args
     * @return usuario
     */
    public function inserir(stdClass $args) : usuario
    {
        return $this->UsuarioRepositoryInterface->inserir($args);
    }

    public function cadastrarUsuario(stdClass $args): usuario
    {
        return $this->UsuarioRepositoryInterface->cadastrarUsuario($args);
    }

    /**
     * @param stdClass $args
     * @return usuario
     */
    public function inserir_parte(stdClass $args) : usuario
    {
        // Insere a pessoa do usuário
        $args_nova_pessoa = new stdClass();
        $args_nova_pessoa->no_pessoa = $args->no_usuario;
        $args_nova_pessoa->tp_pessoa = (strlen($args->nu_cpf_cnpj) > 11 ? 'J' : 'F');
        $args_nova_pessoa->nu_cpf_cnpj = $args->nu_cpf_cnpj;
        $args_nova_pessoa->no_email_pessoa = $args->email_usuario;
        $args_nova_pessoa->id_tipo_pessoa = 3;

        $nova_pessoa = $this->PessoaServiceInterface->inserir($args_nova_pessoa);

        // Substitui as variáveis da inserção do usuário
        $args->id_pessoa = $nova_pessoa->id_pessoa;
        $args->in_confirmado = 'S';
        $args->in_aprovado = 'S';
        $args->in_cliente = 'S';

        $novo_usuario = $this->inserir($args);

        // Vincular a pessoa com o usuário
        $novo_usuario->pessoas()->attach($nova_pessoa, ['id_usuario_cad' => Auth::id()]);

        return $novo_usuario;
    }

    /**
     * @param usuario $usuario
     * @param stdClass $args
     * @return usuario
     */
    public function alterar(usuario $usuario, stdClass $args) : usuario
    {
        return $this->UsuarioRepositoryInterface->alterar($usuario, $args);
    }

    /**
     * @return mixed
     */
    public function getAuthPassword()
    {
        return $this->UsuarioRepositoryInterface->getAuthPassword();
    }
}
