<?php

namespace App\Domain\RegistroFiduciario\Repositories;

use App\Domain\RegistroFiduciario\Contracts\RegistroFiduciarioImovelLocalizacaoRepositoryInterface;
use App\Domain\RegistroFiduciario\Models\registro_fiduciario_imovel_localizacao;
use Illuminate\Database\Eloquent\Collection;

class RegistroFiduciarioImovelLocalizacaoRepository implements RegistroFiduciarioImovelLocalizacaoRepositoryInterface
{
    /**
     * @return Collection
     */
    public function imovel_localizacoes(): Collection
    {
        return registro_fiduciario_imovel_localizacao::where('in_registro_ativo', 'S')
            ->get();
    }
}