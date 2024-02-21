<?php

namespace App\Domain\Configuracao\Providers;

use Illuminate\Support\ServiceProvider;

use App\Domain\Configuracao\Contracts\ConfiguracaoRepositoryInterface;
use App\Domain\Configuracao\Contracts\ConfiguracaoServiceInterface;
use App\Domain\Configuracao\Repositories\ConfiguracaoRepository;
use App\Domain\Configuracao\Services\ConfiguracaoService;

use App\Domain\Configuracao\Contracts\ConfiguracaoTipoPessoaRepositoryInterface;
use App\Domain\Configuracao\Contracts\ConfiguracaoTipoPessoaServiceInterface;
use App\Domain\Configuracao\Repositories\ConfiguracaoTipoPessoaRepository;
use App\Domain\Configuracao\Services\ConfiguracaoTipoPessoaService;

use App\Domain\Configuracao\Contracts\ConfiguracaoPessoaRepositoryInterface;
use App\Domain\Configuracao\Contracts\ConfiguracaoPessoaServiceInterface;
use App\Domain\Configuracao\Repositories\ConfiguracaoPessoaRepository;
use App\Domain\Configuracao\Services\ConfiguracaoPessoaService;

class ConfiguracaoServiceProvider extends ServiceProvider
{
    public function register()
    {
        /**
         * Configuracao
         */
        $this->app->singleton(
            ConfiguracaoRepositoryInterface::class,
            ConfiguracaoRepository::class
        );

        $this->app->singleton(
            ConfiguracaoServiceInterface::class,
            ConfiguracaoService::class
        );

        /**
         * Configuracao Tipo Pessoa
         */
        $this->app->singleton(
            ConfiguracaoTipoPessoaRepositoryInterface::class,
            ConfiguracaoTipoPessoaRepository::class
        );

        $this->app->singleton(
            ConfiguracaoTipoPessoaServiceInterface::class,
            ConfiguracaoTipoPessoaService::class
        );

        /**
         * Configuracao Pessoa
         */
        $this->app->singleton(
            ConfiguracaoPessoaRepositoryInterface::class,
            ConfiguracaoPessoaRepository::class
        );

        $this->app->singleton(
            ConfiguracaoPessoaServiceInterface::class,
            ConfiguracaoPessoaService::class
        );
    }
}
