<?php

namespace App\Domain\Parte\Contracts;

use Illuminate\Database\Eloquent\Collection;

use stdClass;
use Illuminate\Pagination\LengthAwarePaginator;

use App\Domain\Parte\Models\parte_emissao_certificado_historico;

interface ParteEmissaoCertificadoHistoricoRepositoryInterface
{
    /**
     * @param stdClass $args
     * @return parte_emissao_certificado_historico
     */
    public function inserir(stdClass $args) : parte_emissao_certificado_historico;
}
