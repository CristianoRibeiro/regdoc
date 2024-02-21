<?php

namespace App\Domain\Construtora\Repositories;

use Illuminate\Database\Eloquent\Collection;

use App\Domain\Construtora\Contracts\ConstrutoraRepositoryInterface;

use App\Domain\Construtora\Models\construtora;

class ConstrutoraRepository implements ConstrutoraRepositoryInterface
{
    /**
     * @return Collection
     */
    public function lista_construtoras() : Collection
    {
        return construtora::orderBy('no_construtora', 'asc')
                          ->get();
    }

    /**
     * @return Collection
     */
    public function lista_construtora_pessoa(int $id_pessoa) : Collection
    {
        return construtora::where('id_pessoa', $id_pessoa)
                          ->orderBy('no_construtora', 'asc')
                          ->get();
    }

    /**
     * @param int $id
     * @return construtora
     */
    public function busca_construtora(int $id_construtora) : construtora
    {
        return construtora::where('id_construtora', $id_construtora)->first();
    }
}
