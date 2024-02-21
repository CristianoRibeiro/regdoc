<?php

namespace App\Domain\Parte\Repositories;

use Illuminate\Database\Eloquent\Collection;

use App\Domain\Parte\Models\parte_emissao_certificado_situacao;
use App\Domain\Parte\Contracts\ParteEmissaoCertificadoSituacaoRepositoryInterface;

class ParteEmissaoCertificadoSituacaoRepository implements ParteEmissaoCertificadoSituacaoRepositoryInterface
{
    /**
     * @return Collection
     */
    public function listar() : Collection
    {
        return parte_emissao_certificado_situacao::where('in_registro_ativo', '=', 'S')->get();
    }
}
