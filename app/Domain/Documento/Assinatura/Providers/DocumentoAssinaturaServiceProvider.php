<?php

namespace App\Domain\Documento\Assinatura\Providers;

use Illuminate\Support\ServiceProvider;

use App\Domain\Documento\Assinatura\Contracts\DocumentoAssinaturaTipoRepositoryInterface;
use App\Domain\Documento\Assinatura\Contracts\DocumentoAssinaturaTipoServiceInterface;
use App\Domain\Documento\Assinatura\Repositories\DocumentoAssinaturaTipoRepository;
use App\Domain\Documento\Assinatura\Services\DocumentoAssinaturaTipoService;

use App\Domain\Documento\Assinatura\Contracts\DocumentoAssinaturaRepositoryInterface;
use App\Domain\Documento\Assinatura\Contracts\DocumentoAssinaturaServiceInterface;
use App\Domain\Documento\Assinatura\Repositories\DocumentoAssinaturaRepository;
use App\Domain\Documento\Assinatura\Services\DocumentoAssinaturaService;

use App\Domain\Documento\Assinatura\Contracts\DocumentoParteAssinaturaRepositoryInterface;
use App\Domain\Documento\Assinatura\Contracts\DocumentoParteAssinaturaServiceInterface;
use App\Domain\Documento\Assinatura\Repositories\DocumentoParteAssinaturaRepository;
use App\Domain\Documento\Assinatura\Services\DocumentoParteAssinaturaService;

use App\Domain\Documento\Assinatura\Contracts\DocumentoParteAssinaturaArquivoRepositoryInterface;
use App\Domain\Documento\Assinatura\Contracts\DocumentoParteAssinaturaArquivoServiceInterface;
use App\Domain\Documento\Assinatura\Repositories\DocumentoParteAssinaturaArquivoRepository;
use App\Domain\Documento\Assinatura\Services\DocumentoParteAssinaturaArquivoService;

class DocumentoAssinaturaServiceProvider extends ServiceProvider
{
    public function register()
    {
        /**
         * DocumentoAssinaturaTipo
         */
        $this->app->singleton(
            DocumentoAssinaturaTipoRepositoryInterface::class,
            DocumentoAssinaturaTipoRepository::class
        );

        $this->app->singleton(
            DocumentoAssinaturaTipoServiceInterface::class,
            DocumentoAssinaturaTipoService::class
        );

        /**
         * DocumentoAssinatura
         */
        $this->app->singleton(
            DocumentoAssinaturaRepositoryInterface::class,
            DocumentoAssinaturaRepository::class
        );

        $this->app->singleton(
            DocumentoAssinaturaServiceInterface::class,
            DocumentoAssinaturaService::class
        );

        /**
         * DocumentoParteAssinatura
         */
        $this->app->singleton(
            DocumentoParteAssinaturaRepositoryInterface::class,
            DocumentoParteAssinaturaRepository::class
        );

        $this->app->singleton(
            DocumentoParteAssinaturaServiceInterface::class,
            DocumentoParteAssinaturaService::class
        );

        /**
         * DocumentoParteAssinaturaArquivo
         */
        $this->app->singleton(
            DocumentoParteAssinaturaArquivoRepositoryInterface::class,
            DocumentoParteAssinaturaArquivoRepository::class
        );

        $this->app->singleton(
            DocumentoParteAssinaturaArquivoServiceInterface::class,
            DocumentoParteAssinaturaArquivoService::class
        );
    }
}
