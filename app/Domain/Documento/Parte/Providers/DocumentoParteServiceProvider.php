<?php

namespace App\Domain\Documento\Parte\Providers;

use Illuminate\Support\ServiceProvider;

use App\Domain\Documento\Parte\Contracts\DocumentoTipoParteRepositoryInterface;
use App\Domain\Documento\Parte\Contracts\DocumentoTipoParteServiceInterface;
use App\Domain\Documento\Parte\Repositories\DocumentoTipoParteRepository;
use App\Domain\Documento\Parte\Services\DocumentoTipoParteService;

use App\Domain\Documento\Parte\Contracts\DocumentoParteRepositoryInterface;
use App\Domain\Documento\Parte\Contracts\DocumentoParteServiceInterface;
use App\Domain\Documento\Parte\Repositories\DocumentoParteRepository;
use App\Domain\Documento\Parte\Services\DocumentoParteService;

use App\Domain\Documento\Parte\Contracts\DocumentoProcuradorRepositoryInterface;
use App\Domain\Documento\Parte\Contracts\DocumentoProcuradorServiceInterface;
use App\Domain\Documento\Parte\Repositories\DocumentoProcuradorRepository;
use App\Domain\Documento\Parte\Services\DocumentoProcuradorService;

use App\Domain\Documento\Parte\Contracts\DocumentoParteTipoOrdemAssinaturaRepositoryInterface;
use App\Domain\Documento\Parte\Contracts\DocumentoParteTipoOrdemAssinaturaServiceInterface;
use App\Domain\Documento\Parte\Repositories\DocumentoParteTipoOrdemAssinaturaRepository;
use App\Domain\Documento\Parte\Services\DocumentoParteTipoOrdemAssinaturaService;

class DocumentoParteServiceProvider extends ServiceProvider
{
    public function register()
    {
        /**
         * DocumentoTipoParte
         */
        $this->app->singleton(
            DocumentoTipoParteRepositoryInterface::class,
            DocumentoTipoParteRepository::class
        );

        $this->app->singleton(
            DocumentoTipoParteServiceInterface::class,
            DocumentoTipoParteService::class
        );

        /**
         * DocumentoParte
         */
        $this->app->singleton(
            DocumentoParteRepositoryInterface::class,
            DocumentoParteRepository::class
        );

        $this->app->singleton(
            DocumentoParteServiceInterface::class,
            DocumentoParteService::class
        );

        /**
         * DocumentoProcurador
         */
        $this->app->singleton(
            DocumentoProcuradorRepositoryInterface::class,
            DocumentoProcuradorRepository::class
        );

        $this->app->singleton(
            DocumentoProcuradorServiceInterface::class,
            DocumentoProcuradorService::class
        );

        /**
         * DocumentoParteTipoOrdemAssinatura
         */
        $this->app->singleton(
            DocumentoParteTipoOrdemAssinaturaRepositoryInterface::class,
            DocumentoParteTipoOrdemAssinaturaRepository::class
        );

        $this->app->singleton(
            DocumentoParteTipoOrdemAssinaturaServiceInterface::class,
            DocumentoParteTipoOrdemAssinaturaService::class
        );
    }
}
