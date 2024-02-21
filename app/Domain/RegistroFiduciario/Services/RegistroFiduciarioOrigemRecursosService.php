<?php

namespace App\Domain\RegistroFiduciario\Services;

use App\Domain\RegistroFiduciario\Contracts\RegistroFiduciarioOrigemRecursosRepositoryInterface;
use App\Domain\RegistroFiduciario\Contracts\RegistroFiduciarioOrigemRecursosServiceInterface;
use Illuminate\Database\Eloquent\Collection;

class RegistroFiduciarioOrigemRecursosService implements RegistroFiduciarioOrigemRecursosServiceInterface
{
    /**
     * @var $RegistroFiduciarioOrigemRecursosRepositoryInterface
     */
    protected $RegistroFiduciarioOrigemRecursosRepositoryInterface;

    public function __construct(RegistroFiduciarioOrigemRecursosRepositoryInterface $RegistroFiduciarioOrigemRecursosRepositoryInterface)
    {
        $this->RegistroFiduciarioOrigemRecursosRepositoryInterface = $RegistroFiduciarioOrigemRecursosRepositoryInterface;
    }

    /**
     * @return Collection
     */
    public function listar() : Collection
    {
        return $this->RegistroFiduciarioOrigemRecursosRepositoryInterface->listar();
    }
}
