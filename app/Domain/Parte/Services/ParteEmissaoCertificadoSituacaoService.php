<?php

namespace App\Domain\Parte\Services;

use Illuminate\Database\Eloquent\Collection;

use App\Domain\Parte\Contracts\ParteEmissaoCertificadoSituacaoRepositoryInterface;
use App\Domain\Parte\Contracts\ParteEmissaoCertificadoSituacaoServiceInterface;

class ParteEmissaoCertificadoSituacaoService implements ParteEmissaoCertificadoSituacaoServiceInterface
{
    /**
     * @var ParteEmissaoCertificadoSituacaoRepositoryInterface;
     */
    protected $ParteEmissaoCertificadoSituacaoRepositoryInterface;

    /**
     * ParteEmissaoCertificadoService constructor.
     * @param ParteEmissaoCertificadoSituacaoRepositoryInterface $ParteEmissaoCertificadoSituacaoRepositoryInterface
     */
    public function __construct(ParteEmissaoCertificadoSituacaoRepositoryInterface $ParteEmissaoCertificadoSituacaoRepositoryInterface)
    {
        $this->ParteEmissaoCertificadoSituacaoRepositoryInterface = $ParteEmissaoCertificadoSituacaoRepositoryInterface;
    }

    /**
     * @return Collection
     */
    public function listar(): Collection
    {
        return $this->ParteEmissaoCertificadoSituacaoRepositoryInterface->listar();
    }
}
