<?php

namespace App\Domain\Estado\Services;

use App\Domain\Estado\Contracts\EstadoRepositoryInterface;
use App\Domain\Estado\Contracts\EstadoServiceInterface;
use Illuminate\Database\Eloquent\Collection;

class EstadoService implements EstadoServiceInterface
{

    /**
     * @var EstadoRepositoryInterface
     */
    protected $EstadoRepositoryInterface;

    /**
     * EstadoService constructor.
     * @param EstadoRepositoryInterface $EstadoRepositoryInterface
     */
    public function __construct(EstadoRepositoryInterface $EstadoRepositoryInterface)
    {
        $this->EstadoRepositoryInterface = $EstadoRepositoryInterface;
    }

    /**
     * @return Collection
     */
    public function estados_disponiveis(): Collection
    {
        return $this->EstadoRepositoryInterface->estados_disponiveis();
    }

    /**
     * @param int $id_produto
     * @return Collection
     */
    public function estados_disponiveis_calculadora(int $id_produto): Collection
    {
        return $this->EstadoRepositoryInterface->estados_disponiveis_calculadora($id_produto);
    }
}
