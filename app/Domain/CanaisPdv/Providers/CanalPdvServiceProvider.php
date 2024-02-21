<?php

namespace App\Domain\CanaisPdv\Providers;

use Illuminate\Support\ServiceProvider;

use App\Domain\CanaisPdv\Contracts\CanalPdvParceiroServiceInterface;
use App\Domain\CanaisPdv\Contracts\CanalPdvParceiroRepositoryInterface;
use App\Domain\CanaisPdv\Repositories\CanalPdvParceiroRepository;
use App\Domain\CanaisPdv\Services\CanalPdvParceiroService;

class CanalPdvServiceProvider extends ServiceProvider
{
    public function register()
    {
        /**
         * CanalPdvParceiro
         */

        $this->app->singleton(
            CanalPdvParceiroRepositoryInterface::class,
            CanalPdvParceiroRepository::class
        );

        $this->app->singleton(
            CanalPdvParceiroServiceInterface::class,
            CanalPdvParceiroService::class
        );

    }
}
