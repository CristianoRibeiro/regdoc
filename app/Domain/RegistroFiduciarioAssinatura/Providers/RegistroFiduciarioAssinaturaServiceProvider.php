<?php

namespace App\Domain\RegistroFiduciarioAssinatura\Providers;

use Illuminate\Support\ServiceProvider;

use App\Domain\RegistroFiduciarioAssinatura\Contracts\RegistroFiduciarioAssinaturaRepositoryInterface;
use App\Domain\RegistroFiduciarioAssinatura\Contracts\RegistroFiduciarioAssinaturaServiceInterface;
use App\Domain\RegistroFiduciarioAssinatura\Repositories\RegistroFiduciarioAssinaturaRepository;
use App\Domain\RegistroFiduciarioAssinatura\Services\RegistroFiduciarioAssinaturaService;

use App\Domain\RegistroFiduciarioAssinatura\Contracts\RegistroFiduciarioParteAssinaturaRepositoryInterface;
use App\Domain\RegistroFiduciarioAssinatura\Contracts\RegistroFiduciarioParteAssinaturaServiceInterface;
use App\Domain\RegistroFiduciarioAssinatura\Repositories\RegistroFiduciarioParteAssinaturaRepository;
use App\Domain\RegistroFiduciarioAssinatura\Services\RegistroFiduciarioParteAssinaturaService;

use App\Domain\RegistroFiduciarioAssinatura\Contracts\RegistroFiduciarioParteAssinaturaArquivoRepositoryInterface;
use App\Domain\RegistroFiduciarioAssinatura\Contracts\RegistroFiduciarioParteAssinaturaArquivoServiceInterface;
use App\Domain\RegistroFiduciarioAssinatura\Repositories\RegistroFiduciarioParteAssinaturaArquivoRepository;
use App\Domain\RegistroFiduciarioAssinatura\Services\RegistroFiduciarioParteAssinaturaArquivoService;

use App\Domain\RegistroFiduciarioAssinatura\Contracts\TipoParteRegistroFiduciarioOrdemRepositoryInterface;
use App\Domain\RegistroFiduciarioAssinatura\Contracts\TipoParteRegistroFiduciarioOrdemServiceInterface;
use App\Domain\RegistroFiduciarioAssinatura\Repositories\TipoParteRegistroFiduciarioOrdemRepository;
use App\Domain\RegistroFiduciarioAssinatura\Services\TipoParteRegistroFiduciarioOrdemService;

class RegistroFiduciarioAssinaturaServiceProvider extends ServiceProvider
{
    public function register()
    {
        /**
         * Registro Fiduciario Assinatura
         */
        $this->app->singleton(
            RegistroFiduciarioAssinaturaRepositoryInterface::class,
            RegistroFiduciarioAssinaturaRepository::class
        );

        $this->app->singleton(
            RegistroFiduciarioAssinaturaServiceInterface::class,
            RegistroFiduciarioAssinaturaService::class
        );

        /**
         * Registro Fiduciario Parte Assinatura
         */
        $this->app->singleton(
            RegistroFiduciarioParteAssinaturaRepositoryInterface::class,
            RegistroFiduciarioParteAssinaturaRepository::class
        );

        $this->app->singleton(
            RegistroFiduciarioParteAssinaturaServiceInterface::class,
            RegistroFiduciarioParteAssinaturaService::class
        );

        /**
         * Registro Fiduciario Parte Assinatura Arquivo
         */
        $this->app->singleton(
            RegistroFiduciarioParteAssinaturaArquivoRepositoryInterface::class,
            RegistroFiduciarioParteAssinaturaArquivoRepository::class
        );

        $this->app->singleton(
            RegistroFiduciarioParteAssinaturaArquivoServiceInterface::class,
            RegistroFiduciarioParteAssinaturaArquivoService::class
        );

        /**
         * Tipo Parte Registro Fiduciario Ordem
         */
        $this->app->singleton(
            TipoParteRegistroFiduciarioOrdemRepositoryInterface::class,
            TipoParteRegistroFiduciarioOrdemRepository::class
        );

        $this->app->singleton(
            TipoParteRegistroFiduciarioOrdemServiceInterface::class,
            TipoParteRegistroFiduciarioOrdemService::class
        );
    }
}
