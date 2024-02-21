<?php

namespace App\Domain\Configuracao\Contracts;

use Illuminate\Database\Eloquent\Collection;

interface ConfiguracaoServiceInterface
{
    /**
     * @return Collection
     */
    public function listar() : Collection;
}
