<?php

namespace App\Domain\Pessoa\Providers;

use Illuminate\Support\ServiceProvider;

use App\Domain\Pessoa\Contracts\PessoaRepositoryInterface;
use App\Domain\Pessoa\Contracts\PessoaServiceInterface;
use App\Domain\Pessoa\Contracts\TelefoneRepositoryInterface;
use App\Domain\Pessoa\Contracts\TelefoneServiceInterface;
use App\Domain\Pessoa\Repositories\PessoaRepository;
use App\Domain\Pessoa\Repositories\TelefoneRepository;
use App\Domain\Pessoa\Services\PessoaService;
use App\Domain\Pessoa\Services\TelefoneService;

class PessoaServiceProvider extends ServiceProvider
{
    public function register()
    {
        /**
         * Pessoa
         */
        $this->app->singleton(
            PessoaRepositoryInterface::class,
            PessoaRepository::class
        );

        $this->app->singleton(
            PessoaServiceInterface::class,
            PessoaService::class
        );

        /**
         * Telefone
         */
        $this->app->singleton(
            TelefoneRepositoryInterface::class,
            TelefoneRepository::class
        );

        $this->app->singleton(
            TelefoneServiceInterface::class,
            TelefoneService::class
        );
    }
}
