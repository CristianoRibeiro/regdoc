<?php

namespace App\Domain\Parte\Providers;

use Illuminate\Support\ServiceProvider;

use App\Domain\Parte\Contracts\ParteEmissaoCertificadoServiceInterface;
use App\Domain\Parte\Contracts\ParteEmissaoCertificadoRepositoryInterface;
use App\Domain\Parte\Repositories\ParteEmissaoCertificadoRepository;
use App\Domain\Parte\Services\ParteEmissaoCertificadoService;

use App\Domain\Parte\Contracts\ParteEmissaoCertificadoSituacaoServiceInterface;
use App\Domain\Parte\Contracts\ParteEmissaoCertificadoSituacaoRepositoryInterface;
use App\Domain\Parte\Repositories\ParteEmissaoCertificadoSituacaoRepository;
use App\Domain\Parte\Services\ParteEmissaoCertificadoSituacaoService;

use App\Domain\Parte\Contracts\ParteEmissaoCertificadoTipoServiceInterface;
use App\Domain\Parte\Contracts\ParteEmissaoCertificadoTipoRepositoryInterface;
use App\Domain\Parte\Repositories\ParteEmissaoCertificadoTipoRepository;
use App\Domain\Parte\Services\ParteEmissaoCertificadoTipoService;

use App\Domain\Parte\Contracts\ParteEmissaoCertificadoHistoricoServiceInterface;
use App\Domain\Parte\Contracts\ParteEmissaoCertificadoHistoricoRepositoryInterface;
use App\Domain\Parte\Repositories\ParteEmissaoCertificadoHistoricoRepository;
use App\Domain\Parte\Services\ParteEmissaoCertificadoHistoricoService;

class ParteServiceProvider extends ServiceProvider
{
    public function register()
    {
        /**
         * Parte Emissao Certificado
         */
        $this->app->singleton(
            ParteEmissaoCertificadoRepositoryInterface::class,
            ParteEmissaoCertificadoRepository::class
        );

        $this->app->singleton(
            ParteEmissaoCertificadoServiceInterface::class,
            ParteEmissaoCertificadoService::class
        );

        /**
         * Parte Emissao Certificado Situacao
         */
        $this->app->singleton(
            ParteEmissaoCertificadoSituacaoRepositoryInterface::class,
            ParteEmissaoCertificadoSituacaoRepository::class
        );

        $this->app->singleton(
            ParteEmissaoCertificadoSituacaoServiceInterface::class,
            ParteEmissaoCertificadoSituacaoService::class
        );

        /**
         * Parte Emissao Certificado Tipo
         */
        $this->app->singleton(
            ParteEmissaoCertificadoTipoRepositoryInterface::class,
            ParteEmissaoCertificadoTipoRepository::class
        );

        $this->app->singleton(
            ParteEmissaoCertificadoTipoServiceInterface::class,
            ParteEmissaoCertificadoTipoService::class
        );

        /**
         * Parte Emissao Certificado Historico
         */
        $this->app->singleton(
            ParteEmissaoCertificadoHistoricoRepositoryInterface::class,
            ParteEmissaoCertificadoHistoricoRepository::class
        );

        $this->app->singleton(
            ParteEmissaoCertificadoHistoricoServiceInterface::class,
            ParteEmissaoCertificadoHistoricoService::class
        );
    }
}
