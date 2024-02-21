<?php

namespace App\Domain\Estado\Contracts;

use Illuminate\Database\Eloquent\Collection;

use App\Domain\Estado\Models\cidade;

interface CidadeRepositoryInterface
{
    /**
     * @param int $id_cidade
     * @return cidade
     */
    public function buscar_cidade(int $id_cidade) : cidade;

    /**
     * @param int $id_estado
     * @return Collection
     */
    public function cidades_disponiveis(int $id_estado) : ?Collection;

    /**
     * @param int $co_ibge
     * @return cidade
     */
    public function buscar_ibge(string $co_ibge) : cidade;
}