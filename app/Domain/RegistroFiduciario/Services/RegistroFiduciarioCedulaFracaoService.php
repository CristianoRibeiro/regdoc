<?php

namespace App\Domain\RegistroFiduciario\Services;

use App\Domain\RegistroFiduciario\Contracts\RegistroFiduciarioCedulaFracaoRepositoryInterface;
use App\Domain\RegistroFiduciario\Contracts\RegistroFiduciarioCedulaFracaoServiceInterface;
use Illuminate\Database\Eloquent\Collection;

class RegistroFiduciarioCedulaFracaoService implements RegistroFiduciarioCedulaFracaoServiceInterface
{
    /**
     * @var RegistroFiduciarioCedulaFracaoRepositoryInterface
     */
    protected $RegistroFiduciarioCedulaFracaoRepositoryInterface;

    /**
     * RegistroFiduciarioCedulaFracaoService constructor.
     * @param RegistroFiduciarioCedulaFracaoRepositoryInterface $RegistroFiduciarioCedulaFracaoRepositoryInterface
     */
    public function __construct(RegistroFiduciarioCedulaFracaoRepositoryInterface $RegistroFiduciarioCedulaFracaoRepositoryInterface)
    {
        $this->RegistroFiduciarioCedulaFracaoRepositoryInterface = $RegistroFiduciarioCedulaFracaoRepositoryInterface;
    }

    /**
     * @return Collection
     */
    public function cedula_fracoes(): Collection
    {
        return $this->RegistroFiduciarioCedulaFracaoRepositoryInterface->cedula_fracoes();
    }
}