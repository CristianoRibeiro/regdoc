<?php

namespace App\Domain\Registro\Contracts;

use Illuminate\Database\Eloquent\Collection;

use stdClass;

use App\Domain\Registro\Models\registro_tipo_parte_tipo_pessoa;

interface RegistroTipoParteTipoPessoaRepositoryInterface
{
    /**
     * @param stdClass $args, stdClass|null $filtros
     * @return mixed
     */
    public function listar_partes(stdClass $args, ?stdClass $filtros);

    /**
     * @param int $id_registro_tipo_parte_tipo_pessoa
     * @return registro_tipo_parte_tipo_pessoa|null
     */
    public function buscar(int $id_registro_tipo_parte_tipo_pessoa) : ?registro_tipo_parte_tipo_pessoa;
}
