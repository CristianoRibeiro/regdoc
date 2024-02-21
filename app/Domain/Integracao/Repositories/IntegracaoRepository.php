<?php

namespace App\Domain\Integracao\Repositories;

use stdClass;
use Illuminate\Database\Eloquent\Collection;

use App\Domain\Integracao\Contracts\IntegracaoRepositoryInterface;
use App\Domain\Integracao\Models\integracao;

class IntegracaoRepository implements IntegracaoRepositoryInterface
{
    /**
     * @param int $id_integracao
     * @return integracao|null
     */
    public function buscar(int $id_integracao) : ?integracao
    {
        return integracao::find($id_integracao);
    }

    /**
     * @return Collection
     */
    public function listar() : Collection
    {
        return integracao::where('in_registro_ativo', 'S')->get();
    }
}
