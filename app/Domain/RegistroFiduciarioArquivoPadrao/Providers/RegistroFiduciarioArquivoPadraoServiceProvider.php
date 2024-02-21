<?php

namespace App\Domain\RegistroFiduciarioArquivoPadrao\Providers;

use Illuminate\Support\ServiceProvider;

use App\Domain\RegistroFiduciarioArquivoPadrao\Contracts\RegistroFiduciarioArquivoPadraoRepositoryInterface;
use App\Domain\RegistroFiduciarioArquivoPadrao\Contracts\RegistroFiduciarioArquivoPadraoServiceInterface;
use App\Domain\RegistroFiduciarioArquivoPadrao\Repositories\RegistroFiduciarioArquivoPadraoRepository;
use App\Domain\RegistroFiduciarioArquivoPadrao\Services\RegistroFiduciarioArquivoPadraoService;

class RegistroFiduciarioArquivoPadraoServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(
            RegistroFiduciarioArquivoPadraoRepositoryInterface::class,
            RegistroFiduciarioArquivoPadraoRepository::class
        );

        $this->app->singleton(
            RegistroFiduciarioArquivoPadraoServiceInterface::class,
            RegistroFiduciarioArquivoPadraoService::class
        );
    }
}
