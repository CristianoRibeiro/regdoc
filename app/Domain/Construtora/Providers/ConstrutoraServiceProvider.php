<?php

namespace App\Domain\Construtora\Providers;

use Illuminate\Support\ServiceProvider;

use App\Domain\Construtora\Contracts\ConstrutoraRepositoryInterface;
use App\Domain\Construtora\Contracts\ConstrutoraServiceInterface;
use App\Domain\Construtora\Repositories\ConstrutoraRepository;
use App\Domain\Construtora\Services\ConstrutoraService;

class ConstrutoraServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(
            ConstrutoraServiceInterface::class,
            ConstrutoraService::class
        );

        $this->app->singleton(
            ConstrutoraRepositoryInterface::class,
            ConstrutoraRepository::class
        );
    }
}
