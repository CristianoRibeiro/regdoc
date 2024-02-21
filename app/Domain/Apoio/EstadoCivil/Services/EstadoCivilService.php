<?php

namespace App\Domain\Apoio\EstadoCivil\Services;

use Illuminate\Database\Eloquent\Collection;

use App\Domain\Apoio\EstadoCivil\Contracts\EstadoCivilRepositoryInterface;
use App\Domain\Apoio\EstadoCivil\Contracts\EstadoCivilServiceInterface;

class EstadoCivilService implements EstadoCivilServiceInterface
{
    /**
     * @var EstadoCivilRepositoryInterface
     */
    protected $EstadoCivilRepositoryInterface;

    /**
     * EstadoCivilService constructor.
     * @param EstadoCivilRepositoryInterface $EstadoCivilRepositoryInterface
     */
    public function __construct(EstadoCivilRepositoryInterface $EstadoCivilRepositoryInterface)
    {
        $this->EstadoCivilRepositoryInterface = $EstadoCivilRepositoryInterface;
    }

    /**
     * @return Collection
     */
    public function listar(): Collection
    {
        return $this->EstadoCivilRepositoryInterface->listar();
    }
}
