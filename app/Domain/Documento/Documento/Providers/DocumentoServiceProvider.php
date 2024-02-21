<?php

namespace App\Domain\Documento\Documento\Providers;

use Illuminate\Support\ServiceProvider;

use App\Domain\Documento\Documento\Contracts\DocumentoTipoRepositoryInterface;
use App\Domain\Documento\Documento\Contracts\DocumentoTipoServiceInterface;
use App\Domain\Documento\Documento\Repositories\DocumentoTipoRepository;
use App\Domain\Documento\Documento\Services\DocumentoTipoService;

use App\Domain\Documento\Documento\Contracts\DocumentoRepositoryInterface;
use App\Domain\Documento\Documento\Contracts\DocumentoServiceInterface;
use App\Domain\Documento\Documento\Repositories\DocumentoRepository;
use App\Domain\Documento\Documento\Services\DocumentoService;

use App\Domain\Documento\Documento\Contracts\DocumentoComentarioRepositoryInterface;
use App\Domain\Documento\Documento\Contracts\DocumentoComentarioServiceInterface;
use App\Domain\Documento\Documento\Repositories\DocumentoComentarioRepository;
use App\Domain\Documento\Documento\Services\DocumentoComentarioService;

use App\Domain\Documento\Documento\Contracts\DocumentoObservadorRepositoryInterface;
use App\Domain\Documento\Documento\Contracts\DocumentoObservadorServiceInterface;
use App\Domain\Documento\Documento\Repositories\DocumentoObservadorRepository;
use App\Domain\Documento\Documento\Services\DocumentoObservadorService;

class DocumentoServiceProvider extends ServiceProvider
{
    public function register()
    {
        /**
         * DocumentoTipo
         */
        $this->app->singleton(
            DocumentoTipoRepositoryInterface::class,
            DocumentoTipoRepository::class
        );

        $this->app->singleton(
            DocumentoTipoServiceInterface::class,
            DocumentoTipoService::class
        );

        /**
         * Documento
         */
        $this->app->singleton(
            DocumentoRepositoryInterface::class,
            DocumentoRepository::class
        );

        $this->app->singleton(
            DocumentoServiceInterface::class,
            DocumentoService::class
        );

        /**
         * DocumentoComentario
         */
        $this->app->singleton(
            DocumentoComentarioRepositoryInterface::class,
            DocumentoComentarioRepository::class
        );

        $this->app->singleton(
            DocumentoComentarioServiceInterface::class,
            DocumentoComentarioService::class
        );

        /**
         * DocumentoObservador
         */
        $this->app->singleton(
            DocumentoObservadorRepositoryInterface::class,
            DocumentoObservadorRepository::class
        );

        $this->app->singleton(
            DocumentoObservadorServiceInterface::class,
            DocumentoObservadorService::class
        );

    }
}
