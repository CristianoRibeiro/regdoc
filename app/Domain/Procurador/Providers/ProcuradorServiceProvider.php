<?php

namespace App\Domain\Procurador\Providers;

use Illuminate\Support\ServiceProvider;

use App\Domain\Procurador\Contracts\ProcuradorRepositoryInterface;
use App\Domain\Procurador\Contracts\ProcuradorServiceInterface;
use App\Domain\Procurador\Repositories\ProcuradorRepository;
use App\Domain\Procurador\Services\ProcuradorService;

class ProcuradorServiceProvider extends ServiceProvider
{
    public function register()
    {
        /**
         * Procurador
         */
        $this->app->singleton(
            ProcuradorRepositoryInterface::class,
            ProcuradorRepository::class
        );

        $this->app->singleton(
            ProcuradorServiceInterface::class,
            ProcuradorService::class
        );
    }
}
