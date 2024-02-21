<?php

namespace App\Domain\RegistroFiduciario\Services;

use App\Domain\RegistroFiduciario\Contracts\RegistroFiduciarioImovelTipoRepositoryInterface;
use App\Domain\RegistroFiduciario\Contracts\RegistroFiduciarioImovelTipoServiceInterface;
use Illuminate\Database\Eloquent\Collection;

use App\Domain\RegistroFiduciario\Models\registro_fiduciario_imovel_tipo;

class RegistroFiduciarioImovelTipoService implements RegistroFiduciarioImovelTipoServiceInterface
{
    /**
     * @var RegistroFiduciarioImovelTipoRepositoryInterface
     */
    protected $RegistroFiduciarioImovelTipoRepositoryInterface;

    public function __construct(RegistroFiduciarioImovelTipoRepositoryInterface $RegistroFiduciarioImovelTipoRepositoryInterface)
    {
        $this->RegistroFiduciarioImovelTipoRepositoryInterface = $RegistroFiduciarioImovelTipoRepositoryInterface;
    }

    /**
     * @param int $id_registro_fiduciario_imovel_tipo
     * @return registro_fiduciario_imovel_tipo
     */
    public function buscar_tipo(int $id_registro_fiduciario_imovel_tipo) : registro_fiduciario_imovel_tipo
    {
        return $this->RegistroFiduciarioImovelTipoRepositoryInterface->buscar_tipo($id_registro_fiduciario_imovel_tipo);
    }


    /**
     * @return Collection
     */
    public function imovel_tipos() : Collection
    {
        return $this->RegistroFiduciarioImovelTipoRepositoryInterface->imovel_tipos();
    }
}
