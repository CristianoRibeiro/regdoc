<?php

namespace App\Domain\Apoio\EstadoCivil\Providers;

use Illuminate\Support\ServiceProvider;

use App\Domain\Apoio\EstadoCivil\Contracts\EstadoCivilRepositoryInterface;
use App\Domain\Apoio\EstadoCivil\Contracts\EstadoCivilServiceInterface;
use App\Domain\Apoio\EstadoCivil\Repositories\EstadoCivilRepository;
use App\Domain\Apoio\EstadoCivil\Services\EstadoCivilService;

class EstadoCivilServiceProvider extends ServiceProvider
{
    public function register()
    {
        /**
         * EstadoCivil
         */
        $this->app->singleton(
            EstadoCivilRepositoryInterface::class,
            EstadoCivilRepository::class
        );

        $this->app->singleton(
            EstadoCivilServiceInterface::class,
            EstadoCivilService::class
        );
    }
}
