<?php

namespace App\Domain\TabelaEmolumento\Providers;

use Illuminate\Support\ServiceProvider;

use App\Domain\TabelaEmolumento\Contracts\TabelaEmolumentoRepositoryInterface;
use App\Domain\TabelaEmolumento\Contracts\TabelaEmolumentoServiceInterface;
use App\Domain\TabelaEmolumento\Repositories\TabelaEmolumentoRepository;
use App\Domain\TabelaEmolumento\Services\TabelaEmolumentoService;

class TabelaEmolumentoServiceProvider extends ServiceProvider
{
    public function register()
    {
        /**
         * TabelaEmolumento
         */
        $this->app->singleton(
            TabelaEmolumentoRepositoryInterface::class,
            TabelaEmolumentoRepository::class
        );

        $this->app->singleton(
            TabelaEmolumentoServiceInterface::class,
            TabelaEmolumentoService::class
        );
    }
}
