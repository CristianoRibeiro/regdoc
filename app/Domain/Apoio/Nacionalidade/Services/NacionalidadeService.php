<?php

namespace App\Domain\Apoio\Nacionalidade\Services;

use Illuminate\Database\Eloquent\Collection;

use App\Domain\Apoio\Nacionalidade\Contracts\NacionalidadeRepositoryInterface;
use App\Domain\Apoio\Nacionalidade\Contracts\NacionalidadeServiceInterface;

class NacionalidadeService implements NacionalidadeServiceInterface
{
    /**
     * @var NacionalidadeRepositoryInterface
     */
    protected $NacionalidadeRepositoryInterface;

    /**
     * NacionalidadeService constructor.
     * @param NacionalidadeRepositoryInterface $NacionalidadeRepositoryInterface
     */
    public function __construct(NacionalidadeRepositoryInterface $NacionalidadeRepositoryInterface)
    {
        $this->NacionalidadeRepositoryInterface = $NacionalidadeRepositoryInterface;
    }

    /**
     * @return Collection
     */
    public function listar(): Collection
    {
        return $this->NacionalidadeRepositoryInterface->listar();
    }
}
