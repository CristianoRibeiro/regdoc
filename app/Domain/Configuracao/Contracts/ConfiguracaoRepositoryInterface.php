<?php

namespace App\Domain\Configuracao\Contracts;

use Illuminate\Database\Eloquent\Collection;

interface ConfiguracaoRepositoryInterface
{
    /**
     * @return Collection
     */
    public function listar() : Collection;
}
