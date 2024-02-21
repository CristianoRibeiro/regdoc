<?php

namespace App\Domain\Parte\Services;

use Illuminate\Database\Eloquent\Collection;

use App\Domain\Parte\Models\parte_emissao_certificado_tipo;

use App\Domain\Parte\Contracts\ParteEmissaoCertificadoTipoRepositoryInterface;
use App\Domain\Parte\Contracts\ParteEmissaoCertificadoTipoServiceInterface;

class ParteEmissaoCertificadoTipoService implements ParteEmissaoCertificadoTipoServiceInterface
{
    /**
     * @var ParteEmissaoCertificadoTipoRepositoryInterface;
     */
    protected $ParteEmissaoCertificadoTipoRepositoryInterface;

    /**
     * ParteEmissaoCertificadoTipoRepositoryInterface constructor.
     * @param ParteEmissaoCertificadoTipoRepositoryInterface $ParteEmissaoCertificadoTipoRepositoryInterface
     */
    public function __construct(ParteEmissaoCertificadoTipoRepositoryInterface $ParteEmissaoCertificadoTipoRepositoryInterface)
    {
        $this->ParteEmissaoCertificadoTipoRepositoryInterface = $ParteEmissaoCertificadoTipoRepositoryInterface;
    }

    /**
     * @return Collection
     */
    public function listar(): Collection
    {
        return $this->ParteEmissaoCertificadoTipoRepositoryInterface->listar();
    }
}
