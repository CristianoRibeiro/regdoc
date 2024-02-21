<?php

namespace App\Domain\Checklist\Providers;

use Illuminate\Support\ServiceProvider;

use App\Domain\Checklist\Contracts\ChecklistServiceInterface;
use App\Domain\Checklist\Contracts\ChecklistRepositoryInterface;
use App\Domain\Checklist\Repositories\ChecklistRepository;
use App\Domain\Checklist\Services\ChecklistService;

use App\Domain\Checklist\Contracts\ChecklistRegistroFiduciarioServiceInterface;
use App\Domain\Checklist\Contracts\ChecklistRegistroFiduciarioRepositoryInterface;
use App\Domain\Checklist\Repositories\ChecklistRegistroFiduciarioRepository;
use App\Domain\Checklist\Services\ChecklistRegistroFiduciarioService;

class ChecklistServiceProvider extends ServiceProvider
{
    public function register()
    {
        /**
         * Checklist
         */

        $this->app->singleton(
            ChecklistRepositoryInterface::class,
            ChecklistRepository::class
        );

        $this->app->singleton(
            ChecklistServiceInterface::class,
            ChecklistService::class
        );

        /**
         * Checklist Registro Fiduciario
         */

        $this->app->singleton(
            ChecklistRegistroFiduciarioRepositoryInterface::class,
            ChecklistRegistroFiduciarioRepository::class
        );

        $this->app->singleton(
            ChecklistRegistroFiduciarioServiceInterface::class,
            ChecklistRegistroFiduciarioService::class
        );
    }
}
