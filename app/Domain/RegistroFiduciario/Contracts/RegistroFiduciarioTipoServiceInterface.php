<?php

namespace App\Domain\RegistroFiduciario\Contracts;

use Illuminate\Database\Eloquent\Collection;

use App\Domain\RegistroFiduciario\Models\registro_fiduciario_tipo;

interface RegistroFiduciarioTipoServiceInterface
{
    /**
     * @param int $id_registro_fiduciario_tipo
     * @return registro_fiduciario_tipo
     */
    public function buscar(int $id_registro_fiduciario_tipo) : registro_fiduciario_tipo;

    /**
     * @param int $id_produto
     * @return Collection
     */
    public function tipos_registro(int $id_produto) : Collection;
}
