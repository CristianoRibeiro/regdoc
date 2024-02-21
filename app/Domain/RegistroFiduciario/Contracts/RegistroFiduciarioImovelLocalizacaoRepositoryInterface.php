<?php

namespace App\Domain\RegistroFiduciario\Contracts;

use Illuminate\Database\Eloquent\Collection;

interface RegistroFiduciarioImovelLocalizacaoRepositoryInterface
{
    /**
     * @return Collection
     */
    public function imovel_localizacoes() : Collection;
}