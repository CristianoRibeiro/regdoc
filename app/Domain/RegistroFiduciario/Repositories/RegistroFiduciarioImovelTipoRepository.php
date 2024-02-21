<?php

namespace App\Domain\RegistroFiduciario\Repositories;

use App\Domain\RegistroFiduciario\Contracts\RegistroFiduciarioImovelTipoRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

use App\Domain\RegistroFiduciario\Models\registro_fiduciario_imovel_tipo;

class RegistroFiduciarioImovelTipoRepository implements RegistroFiduciarioImovelTipoRepositoryInterface
{
    /**
     * @param int $id_registro_fiduciario_imovel_tipo
     * @return registro_fiduciario_imovel_tipo
     */
    public function buscar_tipo(int $id_registro_fiduciario_imovel_tipo) : registro_fiduciario_imovel_tipo
    {
        return registro_fiduciario_imovel_tipo::find($id_registro_fiduciario_imovel_tipo);
    }

    /**
     * @return Collection
     */
    public function imovel_tipos() : Collection
    {
        return registro_fiduciario_imovel_tipo::where('in_registro_ativo', 'S')->get();
    }
}
