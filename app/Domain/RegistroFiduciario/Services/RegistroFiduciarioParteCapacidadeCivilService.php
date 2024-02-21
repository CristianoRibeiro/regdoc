<?php

namespace App\Domain\RegistroFiduciario\Services;

use Illuminate\Database\Eloquent\Collection;

use App\Domain\RegistroFiduciario\Models\registro_fiduciario_parte_capacidade_civil;

use App\Domain\RegistroFiduciario\Contracts\RegistroFiduciarioParteCapacidadeCivilRepositoryInterface;
use App\Domain\RegistroFiduciario\Contracts\RegistroFiduciarioParteCapacidadeCivilServiceInterface;

class RegistroFiduciarioParteCapacidadeCivilService implements RegistroFiduciarioParteCapacidadeCivilServiceInterface
{
    /**
     * @var RegistroFiduciarioParteCapacidadeCivilRepositoryInterface
     */
    protected $RegistroFiduciarioParteCapacidadeCivilRepositoryInterface;

    /**
     * RegistroFiduciarioParteCapacidadeCivilService constructor.
     * @param RegistroFiduciarioParteCapacidadeCivilRepositoryInterface $RegistroFiduciarioParteCapacidadeCivilRepositoryInterface
     */
    public function __construct(RegistroFiduciarioParteCapacidadeCivilRepositoryInterface $RegistroFiduciarioParteCapacidadeCivilRepositoryInterface)
    {
        $this->RegistroFiduciarioParteCapacidadeCivilRepositoryInterface = $RegistroFiduciarioParteCapacidadeCivilRepositoryInterface;
    }

    /**
     * @return Collection
     */
    public function listar(): Collection
    {
        return $this->RegistroFiduciarioParteCapacidadeCivilRepositoryInterface->listar();
    }

    /**
     * @param int $id_registro_fiduciario_parte_capacidade_civil
     * @return registro_fiduciario_parte_capacidade_civil|null
     */
    public function buscar(int $id_registro_fiduciario_parte_capacidade_civil): ?registro_fiduciario_parte_capacidade_civil
    {
        return $this->RegistroFiduciarioParteCapacidadeCivilServiceInterface->buscar($id_registro_fiduciario_parte_capacidade_civil);
    }

}
