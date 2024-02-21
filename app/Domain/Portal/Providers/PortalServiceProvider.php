<?php

namespace App\Domain\Portal\Providers;

use Illuminate\Support\ServiceProvider;

use App\Domain\Portal\Contracts\CertificadoVidaasRepositoryInterface;
use App\Domain\Portal\Contracts\CertificadoVidaasServiceInterface;
use App\Domain\Portal\Repositories\CertificadoVidaasRepository;
use App\Domain\Portal\Services\CertificadoVidaasService;

use App\Domain\Portal\Contracts\CertificadoVidaasClienteRepositoryInterface;
use App\Domain\Portal\Contracts\CertificadoVidaasClienteServiceInterface;
use App\Domain\Portal\Repositories\CertificadoVidaasClienteRepository;
use App\Domain\Portal\Services\CertificadoVidaasClienteService;

class PortalServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(
            CertificadoVidaasRepositoryInterface::class,
            CertificadoVidaasRepository::class
        );

        $this->app->singleton(
            CertificadoVidaasServiceInterface::class,
            CertificadoVidaasService::class
        );

        $this->app->singleton(
            CertificadoVidaasClienteRepositoryInterface::class,
            CertificadoVidaasClienteRepository::class
        );

        $this->app->singleton(
            CertificadoVidaasClienteServiceInterface::class,
            CertificadoVidaasClienteService::class
        );
    }
}
