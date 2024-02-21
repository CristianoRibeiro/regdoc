<?php

namespace App\Domain\RegistroFiduciario\Services;

use App\Domain\RegistroFiduciario\Contracts\RegistroFiduciarioAlertaGrupoRepositoryInterface;
use App\Domain\RegistroFiduciario\Contracts\RegistroFiduciarioAlertaGrupoServiceInterface;
use Illuminate\Database\Eloquent\Collection;

class RegistroFiduciarioAlertaGrupoService implements RegistroFiduciarioAlertaGrupoServiceInterface
{
    /**
     * @var RegistroFiduciarioAlertaGrupoRepositoryInterface
     */
    protected $RegistroFiduciarioAlertaGrupoRepositoryInterface;

    /**
     * RegistroFiduciarioAlertaGrupoService constructor.
     * @param RegistroFiduciarioAlertaGrupoRepositoryInterface $RegistroFiduciarioAlertaGrupoRepositoryInterface
     */
    public function __construct(RegistroFiduciarioAlertaGrupoRepositoryInterface $RegistroFiduciarioAlertaGrupoRepositoryInterface)
    {
        $this->RegistroFiduciarioAlertaGrupoRepositoryInterface = $RegistroFiduciarioAlertaGrupoRepositoryInterface;
    }

    /**
     * @return Collection
     */
    public function grupos_alertas_disponiveis(): Collection
    {
        return $this->RegistroFiduciarioAlertaGrupoRepositoryInterface->grupos_alertas_disponiveis();
    }
}