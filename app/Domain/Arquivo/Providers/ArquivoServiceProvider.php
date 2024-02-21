<?php

namespace App\Domain\Arquivo\Providers;

use Illuminate\Support\ServiceProvider;

use App\Domain\Arquivo\Contracts\ArquivoRepositoryInterface;
use App\Domain\Arquivo\Contracts\ArquivoServiceInterface;
use App\Domain\Arquivo\Repositories\ArquivoRepository;
use App\Domain\Arquivo\Services\ArquivoService;

use App\Domain\Arquivo\Contracts\ArquivoAssinaturaRepositoryInterface;
use App\Domain\Arquivo\Contracts\ArquivoAssinaturaServiceInterface;
use App\Domain\Arquivo\Repositories\ArquivoAssinaturaRepository;
use App\Domain\Arquivo\Services\ArquivoAssinaturaService;

class ArquivoServiceProvider extends ServiceProvider
{
    public function register()
    {
        /**
         * Arquivo
         */
        $this->app->singleton(
            ArquivoRepositoryInterface::class,
            ArquivoRepository::class
        );

        $this->app->singleton(
            ArquivoServiceInterface::class,
            ArquivoService::class
        );

        /**
         * Arquivo Assinatura
         */
        $this->app->singleton(
            ArquivoAssinaturaRepositoryInterface::class,
            ArquivoAssinaturaRepository::class
        );

        $this->app->singleton(
            ArquivoAssinaturaServiceInterface::class,
            ArquivoAssinaturaService::class
        );
    }
}
