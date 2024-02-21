<?php

namespace App\Domain\Procurador\Services;

use Illuminate\Database\Eloquent\Collection;

use App\Domain\Procurador\Contracts\ProcuradorRepositoryInterface;
use App\Domain\Procurador\Contracts\ProcuradorServiceInterface;

use App\Domain\Procurador\Models\procurador;

class ProcuradorService implements ProcuradorServiceInterface
{
    /**
     * @var ProcuradorRepositoryInterface
     */
    protected $ProcuradorRepositoryInterface;

    /**
     * ProcuradorService constructor.
     * @param ProcuradorRepositoryInterface $ProcuradorRepositoryInterface
     */
    public function __construct(ProcuradorRepositoryInterface $ProcuradorRepositoryInterface)
    {
        $this->ProcuradorRepositoryInterface = $ProcuradorRepositoryInterface;
    }

    /**
     * @return Collection
     */
    public function lista_procuradores() : Collection
    {
        return $this->ProcuradorRepositoryInterface->lista_procuradores();
    }

    /**
     * @param int $id
     * @return procurador
     */
    public function busca_procurador(int $id_procurador) : procurador
    {
        return $this->ProcuradorRepositoryInterface->busca_procurador($id_procurador);
    }
}
