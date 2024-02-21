<?php

namespace App\Domain\Registro\Services;

use Illuminate\Database\Eloquent\Collection;

use stdClass;

use App\Domain\Registro\Models\registro_tipo_parte_tipo_pessoa;

use App\Domain\Registro\Contracts\RegistroTipoParteTipoPessoaRepositoryInterface;
use App\Domain\Registro\Contracts\RegistroTipoParteTipoPessoaServiceInterface;

class RegistroTipoParteTipoPessoaService implements RegistroTipoParteTipoPessoaServiceInterface
{
    /**
     * @var RegistroTipoParteTipoPessoaRepositoryInterface
     */
    protected $RegistroTipoParteTipoPessoaRepositoryInterface;

    /**
     * RegistroTipoParteTipoPessoaService constructor.
     * @param RegistroTipoParteTipoPessoaRepositoryInterface $RegistroTipoParteTipoPessoaRepositoryInterface
     */
    public function __construct(RegistroTipoParteTipoPessoaRepositoryInterface $RegistroTipoParteTipoPessoaRepositoryInterface)
    {
        $this->RegistroTipoParteTipoPessoaRepositoryInterface = $RegistroTipoParteTipoPessoaRepositoryInterface;
    }

    /**
     * @param stdClass $args, stdClass|null $filtros
     * @return mixed
     */
    public function listar_partes(stdClass $args, ?stdClass $filtros = null)
    {
        return $this->RegistroTipoParteTipoPessoaRepositoryInterface->listar_partes($args, $filtros);
    }

    /**
     * @param int $id_registro_tipo_parte_tipo_pessoa
     * @return registro_tipo_parte_tipo_pessoa|null
     */
    public function buscar(int $id_registro_tipo_parte_tipo_pessoa): ?registro_tipo_parte_tipo_pessoa
    {
        return $this->RegistroTipoParteTipoPessoaRepositoryInterface->buscar($id_registro_tipo_parte_tipo_pessoa);
    }
}
