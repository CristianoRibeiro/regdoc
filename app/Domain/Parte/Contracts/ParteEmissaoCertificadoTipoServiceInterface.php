<?php

namespace App\Domain\Parte\Contracts;

use Illuminate\Database\Eloquent\Collection;

use App\Domain\Parte\Models\parte_emissao_certificado_tipo;

interface ParteEmissaoCertificadoTipoServiceInterface
{
    /**
     * @return Collection
     */
    public function listar() : Collection;
}
