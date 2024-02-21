<?php

namespace App\Domain\RegistroFiduciario\Services;

use App\Domain\RegistroFiduciario\Contracts\RegistroFiduciarioCedulaTipoRepositoryInterface;
use App\Domain\RegistroFiduciario\Contracts\RegistroFiduciarioCedulaTipoServiceInterface;
use Illuminate\Database\Eloquent\Collection;

class RegistroFiduciarioCedulaTipoService implements RegistroFiduciarioCedulaTipoServiceInterface
{
    /**
     * @var RegistroFiduciarioCedulaTipoRepositoryInterface
     */
    protected $RegistroFiduciarioCedulaTipoRepositoryInterface;

    /**
     * RegistroFiduciarioCedulaTipoService constructor.
     * @param RegistroFiduciarioCedulaTipoRepositoryInterface $RegistroFiduciarioCedulaTipoRepositoryInterface
     */
    public function __construct(RegistroFiduciarioCedulaTipoRepositoryInterface $RegistroFiduciarioCedulaTipoRepositoryInterface)
    {
        $this->RegistroFiduciarioCedulaTipoRepositoryInterface = $RegistroFiduciarioCedulaTipoRepositoryInterface;
    }

    /**
     * @return Collection
     */
    public function cedula_tipos(): Collection
    {
        return $this->RegistroFiduciarioCedulaTipoRepositoryInterface->cedula_tipos();
    }
}