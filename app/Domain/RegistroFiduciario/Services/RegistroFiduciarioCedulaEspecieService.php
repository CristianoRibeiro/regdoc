<?php

namespace App\Domain\RegistroFiduciario\Services;

use App\Domain\RegistroFiduciario\Contracts\RegistroFiduciarioCedulaEspecieRepositoryInterface;
use App\Domain\RegistroFiduciario\Contracts\RegistroFiduciarioCedulaEspecieServiceInterface;
use Illuminate\Database\Eloquent\Collection;

class RegistroFiduciarioCedulaEspecieService implements RegistroFiduciarioCedulaEspecieServiceInterface
{
    /**
     * @var RegistroFiduciarioCedulaEspecieRepositoryInterface
     */
    protected $RegistroFiduciarioCedulaEspecieRepositoryInterface;

    /**
     * RegistroFiduciarioCedulaEspecieService constructor.
     * @param RegistroFiduciarioCedulaEspecieRepositoryInterface $RegistroFiduciarioCedulaEspecieRepositoryInterface
     */
    public function __construct(RegistroFiduciarioCedulaEspecieRepositoryInterface $RegistroFiduciarioCedulaEspecieRepositoryInterface)
    {
        $this->RegistroFiduciarioCedulaEspecieRepositoryInterface = $RegistroFiduciarioCedulaEspecieRepositoryInterface;
    }

    /**
     * @return Collection
     */
    public function cedula_especies(): Collection
    {
        return $this->RegistroFiduciarioCedulaEspecieRepositoryInterface->cedula_especies();
    }
}