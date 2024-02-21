<?php

namespace App\Domain\RegistroFiduciario\Services;

use App\Domain\RegistroFiduciario\Contracts\RegistroFiduciarioNaturezaRepositoryInterface;
use App\Domain\RegistroFiduciario\Contracts\RegistroFiduciarioNaturezaServiceInterface;
use Illuminate\Database\Eloquent\Collection;

class RegistroFiduciarioNaturezaService implements RegistroFiduciarioNaturezaServiceInterface
{
    /**
     * @var RegistroFiduciarioNaturezaRepositoryInterface
     */
    protected $RegistroFiduciarioNaturezaRepositoryInterface;

    /**
     * RegistroFiduciarioNaturezaService constructor.
     * @param RegistroFiduciarioNaturezaRepositoryInterface $RegistroFiduciarioNaturezaRepositoryInterface
     */
    public function __construct(RegistroFiduciarioNaturezaRepositoryInterface $RegistroFiduciarioNaturezaRepositoryInterface)
    {
        $this->RegistroFiduciarioNaturezaRepositoryInterface = $RegistroFiduciarioNaturezaRepositoryInterface;
    }

    /**
     * @return Collection
     */
    public function naturezas_contrato(): Collection
    {
        return $this->RegistroFiduciarioNaturezaRepositoryInterface->naturezas_contrato();
    }
}