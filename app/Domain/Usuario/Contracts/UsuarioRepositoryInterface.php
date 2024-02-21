<?php

namespace App\Domain\Usuario\Contracts;

use Illuminate\Database\Eloquent\Collection;

use stdClass;

use App\Domain\Usuario\Models\usuario;

interface UsuarioRepositoryInterface
{
    /**
     * @param int $id_usuario
     * @return usuario|null
     */
    public function buscar(int $id_usuario) : ?usuario;

    public function cadastrarUsuario(stdClass $args): usuario;

    /**
     * @param int $id_pessoa
     * @return Collection
     */
    public function listar_por_entidade(int $id_pessoa) : Collection;

    /**
     * @param stdClass $args
     * @return usuario
     */
    public function inserir(stdClass $args) : usuario;

    /**
     * @param usuario $usuario
     * @param stdClass $args
     * @return usuario
     */
    public function alterar(usuario $usuario, stdClass $args): usuario;

    /**
     * @return mixed
     */
    public function getAuthPassword();
}
