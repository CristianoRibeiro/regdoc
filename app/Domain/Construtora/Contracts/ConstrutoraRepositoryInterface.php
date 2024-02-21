<?php

namespace App\Domain\Construtora\Contracts;

use Illuminate\Database\Eloquent\Collection;

use App\Domain\Construtora\Models\construtora;

interface ConstrutoraRepositoryInterface
{
    /**
     * @return Collection
     */
    public function lista_construtoras() : Collection;

    /**
     * @return Collection
     */
    public function lista_construtora_pessoa(int $id_pessoa) : Collection;

    /**
     * @param int $id
     * @return construtora
     */
    public function busca_construtora(int $id_construtora) : construtora;
}
