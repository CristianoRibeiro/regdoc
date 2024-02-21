<?php

namespace App\Domain\RegistroFiduciario\Services;

use App\Domain\RegistroFiduciario\Contracts\RegistroFiduciarioImovelLivroRepositoryInterface;
use App\Domain\RegistroFiduciario\Contracts\RegistroFiduciarioImovelLivroServiceInterface;
use Illuminate\Database\Eloquent\Collection;

class RegistroFiduciarioImovelLivroService implements RegistroFiduciarioImovelLivroServiceInterface
{
    /**
     * @var RegistroFiduciarioImovelLivroRepositoryInterface
     */
    protected $RegistroFiduciarioImovelLivroRepositoryInterface;

    /**
     * RegistroFiduciarioImovelLivroService constructor.
     * @param RegistroFiduciarioImovelLivroRepositoryInterface $RegistroFiduciarioImovelLivroRepositoryInterface
     */
    public function __construct(RegistroFiduciarioImovelLivroRepositoryInterface $RegistroFiduciarioImovelLivroRepositoryInterface)
    {
        $this->RegistroFiduciarioImovelLivroRepositoryInterface = $RegistroFiduciarioImovelLivroRepositoryInterface;
    }

    /**
     * @return Collection
     */
    public function imovel_livros() : Collection
    {
        return $this->RegistroFiduciarioImovelLivroRepositoryInterface->imovel_livros();
    }
}