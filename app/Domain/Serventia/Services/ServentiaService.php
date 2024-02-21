<?php

namespace App\Domain\Serventia\Services;

use Illuminate\Database\Eloquent\Collection;
use stdClass;

use App\Domain\Serventia\Models\serventia;

use App\Domain\Serventia\Contracts\ServentiaRepositoryInterface;
use App\Domain\Serventia\Contracts\ServentiaServiceInterface;

class ServentiaService implements ServentiaServiceInterface
{
    /**
     * @var ServentiaRepositoryInterface;
     */
    protected $ServentiaRepositoryInterface;

    /**
     * ServentiaService constructor.
     * @param ServentiaRepositoryInterface $ServentiaRepositoryInterface
     */
    public function __construct(ServentiaRepositoryInterface $ServentiaRepositoryInterface)
    {
        $this->ServentiaRepositoryInterface = $ServentiaRepositoryInterface;
    }

    /**
     * @param stdClass $filtros
     * @return \Illuminate\Database\Eloquent\Builder
     * @throws Exception
     */
    public function listar(stdClass $filtros): \Illuminate\Database\Eloquent\Builder
    {
        return $this->ServentiaRepositoryInterface->listar($filtros);
    }

    /**
     * @param int $id_serventia
     * @return serventia|null
     */
    public function buscar(int $id_serventia) : ?serventia
    {
        return $this->ServentiaRepositoryInterface->buscar($id_serventia);
    }

    /**
     * @param string $codigo_cns_completo
     * @return serventia|null
     */
    public function buscar_cns(string $codigo_cns_completo) : ?serventia
    {
        return $this->ServentiaRepositoryInterface->buscar_cns($codigo_cns_completo);
    }

    /**
     * @param stdClass $args
     * @return serventia
     */
    public function inserir(stdClass $args): serventia
    {
        return $this->ServentiaRepositoryInterface->inserir($args);
    }

    /**
     * @param serventia $serventia
     * @param stdClass $args
     * @return serventia
     */
    public function alterar(serventia $serventia, stdClass $args): serventia
    {
        return $this->ServentiaRepositoryInterface->alterar($serventia, $args);
    }

}
