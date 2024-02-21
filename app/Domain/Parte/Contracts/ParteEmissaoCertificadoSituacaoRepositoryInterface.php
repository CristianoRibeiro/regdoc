<?php

namespace App\Domain\Parte\Contracts;

use Illuminate\Database\Eloquent\Collection;

use stdClass;

use App\Domain\Parte\Models\parte_emissao_certificado_situacao;

interface ParteEmissaoCertificadoSituacaoRepositoryInterface
{
    /**
     * @return Collection
     */
    public function listar() : Collection;
}
