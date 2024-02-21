<?php

namespace App\Domain\RegistroFiduciario\Services;

use App\Domain\RegistroFiduciario\Contracts\RegistroFiduciarioImovelLocalizacaoRepositoryInterface;
use App\Domain\RegistroFiduciario\Contracts\RegistroFiduciarioImovelLocalizacaoServiceInterface;
use Illuminate\Database\Eloquent\Collection;

class RegistroFiduciarioImovelLocalizacaoService implements RegistroFiduciarioImovelLocalizacaoServiceInterface
{
    /**
     * @var RegistroFiduciarioImovelLocalizacaoRepositoryInterface
     */
    protected $RegistroFiduciarioImovelLocalizacaoRepositoryInterface;

    /**
     * RegistroFiduciarioImovelLocalizacaoService constructor.
     * @param RegistroFiduciarioImovelLocalizacaoRepositoryInterface $RegistroFiduciarioImovelLocalizacaoRepositoryInterface
     */
    public function __construct(RegistroFiduciarioImovelLocalizacaoRepositoryInterface $RegistroFiduciarioImovelLocalizacaoRepositoryInterface)
    {
        $this->RegistroFiduciarioImovelLocalizacaoRepositoryInterface = $RegistroFiduciarioImovelLocalizacaoRepositoryInterface;
    }

    /**
     * @return Collection
     */
    public function imovel_localizacoes(): Collection
    {
        return $this->RegistroFiduciarioImovelLocalizacaoRepositoryInterface->imovel_localizacoes();
    }
}