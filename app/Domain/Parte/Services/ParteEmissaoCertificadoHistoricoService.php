<?php

namespace App\Domain\Parte\Services;

use Illuminate\Database\Eloquent\Collection;

use stdClass;

use App\Domain\Parte\Models\parte_emissao_certificado_historico;

use App\Domain\Parte\Contracts\ParteEmissaoCertificadoHistoricoRepositoryInterface;
use App\Domain\Parte\Contracts\ParteEmissaoCertificadoHistoricoServiceInterface;

class ParteEmissaoCertificadoHistoricoService implements ParteEmissaoCertificadoHistoricoServiceInterface
{
    /**
     * @var ParteEmissaoCertificadoHistoricoRepositoryInterface;
     */
    protected $ParteEmissaoCertificadoHistoricoRepositoryInterface;

    /**
     * ParteEmissaoCertificadoHistoricoService constructor.
     * @param ParteEmissaoCertificadoHistoricoRepositoryInterface $ParteEmissaoCertificadoHistoricoRepositoryInterface
     */
    public function __construct(ParteEmissaoCertificadoHistoricoRepositoryInterface $ParteEmissaoCertificadoHistoricoRepositoryInterface)
    {
        $this->ParteEmissaoCertificadoHistoricoRepositoryInterface = $ParteEmissaoCertificadoHistoricoRepositoryInterface;
    }

    /**
     * @param stdClass $args
     * @return parte_emissao_certificado_historico
     */
    public function inserir(stdClass $args): parte_emissao_certificado_historico
    {
        return $this->ParteEmissaoCertificadoHistoricoRepositoryInterface->inserir($args);
    }
}
