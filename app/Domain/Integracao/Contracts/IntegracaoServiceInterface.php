<?php

namespace App\Domain\Integracao\Contracts;

use stdClass;
use Illuminate\Database\Eloquent\Collection;

use App\Domain\Integracao\Models\integracao;

interface IntegracaoServiceInterface
{
    /**
     * @param int $id_integracao
     * @return integracao|null
     */
    public function buscar(int $id_integracao): ?integracao;

    /**
     * @return Collection
     */
    public function listar() : Collection;
}
