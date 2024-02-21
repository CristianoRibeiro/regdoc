<?php

namespace App\Domain\RegistroFiduciario\Contracts;

use Illuminate\Database\Eloquent\Collection;

use App\Domain\RegistroFiduciario\Models\registro_fiduciario_imovel_tipo;

interface RegistroFiduciarioImovelTipoRepositoryInterface
{
    /**
     * @param int $id_registro_fiduciario_imovel_tipo
     * @return registro_fiduciario_imovel_tipo
     */
    public function buscar_tipo(int $id_registro_fiduciario_imovel_tipo) : registro_fiduciario_imovel_tipo;

    /**
     * @return Collection
     */
    public function imovel_tipos() : Collection;
}
