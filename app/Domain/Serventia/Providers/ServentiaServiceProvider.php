<?php

namespace App\Domain\Serventia\Providers;

use Illuminate\Support\ServiceProvider;

use App\Domain\Serventia\Contracts\ServentiaRepositoryInterface;
use App\Domain\Serventia\Contracts\ServentiaServiceInterface;
use App\Domain\Serventia\Repositories\ServentiaRepository;
use App\Domain\Serventia\Services\ServentiaService;

class ServentiaServiceProvider extends ServiceProvider
{
    public function register()
    {
        /**
         * Serventia
         */
        $this->app->singleton(
            ServentiaRepositoryInterface::class,
            ServentiaRepository::class
        );

        $this->app->singleton(
            ServentiaServiceInterface::class,
            ServentiaService::class
        );
    }
}
