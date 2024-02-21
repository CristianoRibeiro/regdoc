<?php

namespace App\Domain\Procurador\Repositories;

use Illuminate\Database\Eloquent\Collection;

use App\Domain\Procurador\Contracts\ProcuradorRepositoryInterface;

use App\Domain\Procurador\Models\procurador;

class ProcuradorRepository implements ProcuradorRepositoryInterface
{
    /**
     * @return Collection
     */
    public function lista_procuradores() : Collection
    {
        return procurador::orderBy('no_procurador', 'asc')->get();
    }

    /**
     * @param int $id
     * @return procurador
     */
    public function busca_procurador(int $id_procurador) : procurador
    {
        return procurador::where('id_procurador', $id_procurador)->first();
    }
}
