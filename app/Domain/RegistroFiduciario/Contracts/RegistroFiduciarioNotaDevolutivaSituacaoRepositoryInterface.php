<?php

namespace App\Domain\RegistroFiduciario\Contracts;

use stdClass;
use Illuminate\Database\Eloquent\Collection;

use App\Domain\RegistroFiduciario\Models\registro_fiduciario_nota_devolutiva_situacao;

interface RegistroFiduciarioNotaDevolutivaSituacaoRepositoryInterface
{
    /**
     * @return Collection
     */
    public function listar() : Collection;

    /**
     * @param int $id_registro_fiduciario_nota_devolutiva_situacao
     * @return registro_fiduciario_nota_devolutiva_situacao|null
     */
    public function buscar(int $id_registro_fiduciario_nota_devolutiva_situacao) : ?registro_fiduciario_nota_devolutiva_situacao;

}
