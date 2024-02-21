<?php

namespace App\Domain\RegistroFiduciario\Services;

use Illuminate\Database\Eloquent\Collection;

use App\Domain\RegistroFiduciario\Models\registro_fiduciario_tipo;

use App\Domain\RegistroFiduciario\Contracts\RegistroFiduciarioTipoRepositoryInterface;
use App\Domain\RegistroFiduciario\Contracts\RegistroFiduciarioTipoServiceInterface;

class RegistroFiduciarioTipoService implements RegistroFiduciarioTipoServiceInterface
{
    /**
     * @var RegistroFiduciarioTipoRepositoryInterface
     */
    protected $RegistroFiduciarioTipoRepositoryInterface;

    /**
     * RegistroFiduciarioTipoService constructor.
     * @param RegistroFiduciarioTipoRepositoryInterface $RegistroFiduciarioTipoRepositoryInterface
     */
    public function __construct(RegistroFiduciarioTipoRepositoryInterface $RegistroFiduciarioTipoRepositoryInterface)
    {
        $this->RegistroFiduciarioTipoRepositoryInterface = $RegistroFiduciarioTipoRepositoryInterface;
    }

    /**
     * @param int $id_registro_fiduciario_tipo
     * @return registro_fiduciario_tipo
     */
    public function buscar(int $id_registro_fiduciario_tipo) : registro_fiduciario_tipo
    {
        return $this->RegistroFiduciarioTipoRepositoryInterface->buscar($id_registro_fiduciario_tipo);
    }

    /**
     * @param int $id_produto
     * @return Collection
     */
    public function tipos_registro(int $id_produto) : Collection
    {
        return $this->RegistroFiduciarioTipoRepositoryInterface->tipos_registro($id_produto);
    }
}
