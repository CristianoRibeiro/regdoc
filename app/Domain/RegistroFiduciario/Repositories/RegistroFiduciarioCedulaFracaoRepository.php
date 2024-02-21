<?php

namespace App\Domain\RegistroFiduciario\Repositories;

use App\Domain\RegistroFiduciario\Contracts\RegistroFiduciarioCedulaFracaoRepositoryInterface;
use App\Domain\RegistroFiduciario\Models\registro_fiduciario_cedula_fracao;
use Illuminate\Database\Eloquent\Collection;

class RegistroFiduciarioCedulaFracaoRepository implements RegistroFiduciarioCedulaFracaoRepositoryInterface
{
    /**
     * @return Collection
     */
    public function cedula_fracoes(): Collection
    {
        return registro_fiduciario_cedula_fracao::where('in_registro_ativo', 'S')->get();
    }
}