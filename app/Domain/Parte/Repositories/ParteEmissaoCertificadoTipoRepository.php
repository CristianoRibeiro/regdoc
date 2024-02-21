<?php

namespace App\Domain\Parte\Repositories;

use Illuminate\Database\Eloquent\Collection;

use App\Domain\Parte\Models\parte_emissao_certificado_tipo;

use App\Domain\Parte\Contracts\ParteEmissaoCertificadoTipoRepositoryInterface;

class ParteEmissaoCertificadoTipoRepository implements ParteEmissaoCertificadoTipoRepositoryInterface
{
     /**
     * @return Collection
     */
    public function listar() : Collection
    {
        return parte_emissao_certificado_tipo::get();
    }

}