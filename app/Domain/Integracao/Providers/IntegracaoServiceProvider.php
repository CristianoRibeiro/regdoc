<?php

namespace App\Domain\Integracao\Providers;

use Illuminate\Support\ServiceProvider;

use App\Domain\Integracao\Contracts\IntegracaoRepositoryInterface;
use App\Domain\Integracao\Contracts\IntegracaoServiceInterface;
use App\Domain\Integracao\Repositories\IntegracaoRepository;
use App\Domain\Integracao\Services\IntegracaoService;

use App\Domain\Integracao\Contracts\IntegracaoRegistroFiduciarioRepositoryInterface;
use App\Domain\Integracao\Contracts\IntegracaoRegistroFiduciarioServiceInterface;
use App\Domain\Integracao\Repositories\IntegracaoRegistroFiduciarioRepository;
use App\Domain\Integracao\Services\IntegracaoRegistroFiduciarioService;

class IntegracaoServiceProvider extends ServiceProvider
{
    public function register()
    {
        /**
         * Registro Fiduciario
         */
        $this->app->singleton(
            IntegracaoRepositoryInterface::class,
            IntegracaoRepository::class
        );

        $this->app->singleton(
            IntegracaoServiceInterface::class,
            IntegracaoService::class
        );

        /**
         * Integracao Registro Fiduciario
         */
        $this->app->singleton(
            IntegracaoRegistroFiduciarioRepositoryInterface::class,
            IntegracaoRegistroFiduciarioRepository::class
        );

        $this->app->singleton(
            IntegracaoRegistroFiduciarioServiceInterface::class,
            IntegracaoRegistroFiduciarioService::class
        );

    }
}
