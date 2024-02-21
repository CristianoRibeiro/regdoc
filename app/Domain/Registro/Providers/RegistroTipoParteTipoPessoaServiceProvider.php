<?php

namespace App\Domain\Registro\Providers;

use Illuminate\Support\ServiceProvider;

use App\Domain\Registro\Contracts\RegistroProtocoloServiceInterface;
use App\Domain\Registro\Services\RegistroProtocoloService;
use App\Domain\Registro\Contracts\RegistroTipoParteTipoPessoaServiceInterface;
use App\Domain\Registro\Contracts\RegistroTipoParteTipoPessoaRepositoryInterface;
use App\Domain\Registro\Repositories\RegistroTipoParteTipoPessoaRepository;
use App\Domain\Registro\Services\RegistroTipoParteTipoPessoaService;

class RegistroTipoParteTipoPessoaServiceProvider extends ServiceProvider
{
    public function register()
    {
        /**
         * RegistroTipoParteTipoPessoa
         */

        $this->app->singleton(
            RegistroTipoParteTipoPessoaRepositoryInterface::class,
            RegistroTipoParteTipoPessoaRepository::class
        );

        $this->app->singleton(
            RegistroTipoParteTipoPessoaServiceInterface::class,
            RegistroTipoParteTipoPessoaService::class
        );

        $this->app->singleton(
            RegistroProtocoloServiceInterface::class,
            RegistroProtocoloService::class
        );

    }
}
