<?php

namespace App\Domain\Procuracao\Providers;

use Illuminate\Support\ServiceProvider;

use App\Domain\Procuracao\Contracts\ProcuracaoRepositoryInterface;
use App\Domain\Procuracao\Contracts\ProcuracaoServiceInterface;
use App\Domain\Procuracao\Repositories\ProcuracaoRepository;
use App\Domain\Procuracao\Services\ProcuracaoService;

use App\Domain\Procuracao\Contracts\ProcuracaoArquivoGrupoRepositoryInterface;
use App\Domain\Procuracao\Contracts\ProcuracaoArquivoGrupoServiceInterface;
use App\Domain\Procuracao\Repositories\ProcuracaoArquivoGrupoRepository;
use App\Domain\Procuracao\Services\ProcuracaoArquivoGrupoService;

class ProcuracaoServiceProvider extends ServiceProvider
{
    public function register()
    {
        /**
         * Procuração
         */
        $this->app->singleton(
            ProcuracaoRepositoryInterface::class,
            ProcuracaoRepository::class
        );

        $this->app->singleton(
            ProcuracaoServiceInterface::class,
            ProcuracaoService::class
        );

         /**
         * Procuração Arquivo Grupo
         */
        $this->app->singleton(
            ProcuracaoArquivoGrupoRepositoryInterface::class,
            ProcuracaoArquivoGrupoRepository::class
        );

        $this->app->singleton(
            ProcuracaoArquivoGrupoServiceInterface::class,
            ProcuracaoArquivoGrupoService::class
        );
    }
}
