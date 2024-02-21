<?php

namespace App\Domain\Configuracao\Contracts;

use Illuminate\Database\Eloquent\Collection;

interface ConfiguracaoTipoPessoaRepositoryInterface
{
    /**
     * @param int $id_tipo_pessoa
     * @return Collection
     */
    public function listar(int $id_tipo_pessoa) : Collection;
}
