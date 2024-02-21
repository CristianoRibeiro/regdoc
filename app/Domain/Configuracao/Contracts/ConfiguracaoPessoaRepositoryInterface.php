<?php

namespace App\Domain\Configuracao\Contracts;

use Illuminate\Database\Eloquent\Collection;

interface ConfiguracaoPessoaRepositoryInterface
{
    /**
     * @param int $id_pessoa
     * @return Collection
     */
    public function listar(int $id_pessoa) : Collection;

    /**
     * @param int $id_pessoa
     * @param array $slugs
     * @return array
     */
    public function listar_array(int $id_pessoa, array $slugs) : array;
}
