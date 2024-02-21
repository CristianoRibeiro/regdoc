<?php

namespace App\Domain\Apoio\Nacionalidade\Providers;

use Illuminate\Support\ServiceProvider;

use App\Domain\Apoio\Nacionalidade\Contracts\NacionalidadeRepositoryInterface;
use App\Domain\Apoio\Nacionalidade\Contracts\NacionalidadeServiceInterface;
use App\Domain\Apoio\Nacionalidade\Repositories\NacionalidadeRepository;
use App\Domain\Apoio\Nacionalidade\Services\NacionalidadeService;

class NacionalidadeServiceProvider extends ServiceProvider
{
    public function register()
    {
        /**
         * Nacionalidade
         */
        $this->app->singleton(
            NacionalidadeRepositoryInterface::class,
            NacionalidadeRepository::class
        );

        $this->app->singleton(
            NacionalidadeServiceInterface::class,
            NacionalidadeService::class
        );
    }
}
