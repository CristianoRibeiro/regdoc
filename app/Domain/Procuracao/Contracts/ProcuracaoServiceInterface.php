<?php

namespace App\Domain\Procuracao\Contracts;

use Illuminate\Database\Eloquent\Collection;

use App\Domain\Procuracao\Models\procuracao;

interface ProcuracaoServiceInterface
{
    /**
     * @return Collection<procuracao>
     */
    public function listar() : Collection;

    /**
     * @param string $uuid
     * @return procuracao|null
     */
    public function buscar_uuid(string $uuid) : ?procuracao;
}
