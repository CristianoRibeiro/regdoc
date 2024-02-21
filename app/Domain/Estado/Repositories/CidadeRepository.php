<?php

namespace App\Domain\Estado\Repositories;

use App\Domain\Estado\Contracts\CidadeRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

use App\Domain\Estado\Models\cidade;

class CidadeRepository implements CidadeRepositoryInterface
{
    /**
     * @param int $id_cidade
     * @return cidade
     */
    public function buscar_cidade(int $id_cidade) : cidade
    {
        return cidade::find($id_cidade);
    }

    /**
     * @param int $id_estado
     * @return Collection
     */
    public function cidades_disponiveis(int $id_estado) : ?Collection
    {
        return cidade::where('id_estado', $id_estado)->orderBy('no_cidade')->get();
    }

    /**
     * @param int $co_ibge
     * @return cidade
     */
    public function buscar_ibge(string $co_ibge) : cidade
    {
        return cidade::where('co_ibge', $co_ibge)->first();
    }

}