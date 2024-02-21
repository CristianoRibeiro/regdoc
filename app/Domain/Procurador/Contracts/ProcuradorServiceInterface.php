<?php

namespace App\Domain\Procurador\Contracts;

use Illuminate\Database\Eloquent\Collection;

use App\Domain\Procurador\Models\procurador;

interface ProcuradorServiceInterface
{
    /**
     * @return Collection
     */
    public function lista_procuradores() : Collection;

    /**
     * @param int $id
     * @return procurador
     */
    public function busca_procurador(int $id_procurador) : procurador;
}
