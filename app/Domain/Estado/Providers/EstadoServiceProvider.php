<?php

namespace App\Domain\Estado\Providers;

use Illuminate\Support\ServiceProvider;

use App\Domain\Estado\Contracts\CidadeRepositoryInterface;
use App\Domain\Estado\Contracts\CidadeServiceInterface;
use App\Domain\Estado\Contracts\EstadoRepositoryInterface;
use App\Domain\Estado\Contracts\EstadoServiceInterface;
use App\Domain\Estado\Repositories\CidadeRepository;
use App\Domain\Estado\Repositories\EstadoRepository;
use App\Domain\Estado\Services\CidadeService;
use App\Domain\Estado\Services\EstadoService;

class EstadoServiceProvider extends ServiceProvider
{
    public function register()
    {
        /**
         * Cidade
         */
        $this->app->singleton(
            CidadeRepositoryInterface::class,
            CidadeRepository::class
        );

        $this->app->singleton(
            CidadeServiceInterface::class,
            CidadeService::class
        );

        /**
         * Estado
         */
        $this->app->singleton(
            EstadoRepositoryInterface::class,
            EstadoRepository::class
        );

        $this->app->singleton(
            EstadoServiceInterface::class,
            EstadoService::class
        );
    }
}
