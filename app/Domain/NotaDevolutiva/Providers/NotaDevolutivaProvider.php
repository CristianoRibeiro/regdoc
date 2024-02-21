<?php

namespace App\Domain\NotaDevolutiva\Providers;

use Illuminate\Support\ServiceProvider;

use App\Domain\NotaDevolutiva\Contracts\NotaDevolutivaCumprimentoServiceInterface;
use App\Domain\NotaDevolutiva\Contracts\NotaDevolutivaCumprimentoRepositoryInterface;
use App\Domain\NotaDevolutiva\Repositories\NotaDevolutivaCumprimentoRepository;
use App\Domain\NotaDevolutiva\Services\NotaDevolutivaCumprimentoService;

use App\Domain\NotaDevolutiva\Contracts\NotaDevolutivaCausaClassificacaoServiceInterface;
use App\Domain\NotaDevolutiva\Contracts\NotaDevolutivaCausaClassificacaoRepositoryInterface;
use App\Domain\NotaDevolutiva\Repositories\NotaDevolutivaCausaClassificacaoRepository;
use App\Domain\NotaDevolutiva\Services\NotaDevolutivaCausaClassificacaoService;

use App\Domain\NotaDevolutiva\Contracts\NotaDevolutivaCausaGrupoServiceInterface;
use App\Domain\NotaDevolutiva\Contracts\NotaDevolutivaCausaGrupoRepositoryInterface;
use App\Domain\NotaDevolutiva\Repositories\NotaDevolutivaCausaGrupoRepository;
use App\Domain\NotaDevolutiva\Services\NotaDevolutivaCausaGrupoService;

use App\Domain\NotaDevolutiva\Contracts\NotaDevolutivaCausaRaizServiceInterface;
use App\Domain\NotaDevolutiva\Contracts\NotaDevolutivaCausaRaizRepositoryInterface;
use App\Domain\NotaDevolutiva\Repositories\NotaDevolutivaCausaRaizRepository;
use App\Domain\NotaDevolutiva\Services\NotaDevolutivaCausaRaizService;


class NotaDevolutivaProvider extends ServiceProvider
{
    public function register()
    {
        /**
         * Nota Devolutiva Cumprimento
         */
        $this->app->singleton(
            NotaDevolutivaCumprimentoRepositoryInterface::class,
            NotaDevolutivaCumprimentoRepository::class
        );

        $this->app->singleton(
            NotaDevolutivaCumprimentoServiceInterface::class,
            NotaDevolutivaCumprimentoService::class
        );

        /**
         * Nota Devolutiva Causa Classificação
         */
        $this->app->singleton(
            NotaDevolutivaCausaClassificacaoRepositoryInterface::class,
            NotaDevolutivaCausaClassificacaoRepository::class
        );

        $this->app->singleton(
            NotaDevolutivaCausaClassificacaoServiceInterface::class,
            NotaDevolutivaCausaClassificacaoService::class
        );

        /**
         * Nota Devolutiva Causa Grupo
         */
        $this->app->singleton(
            NotaDevolutivaCausaGrupoRepositoryInterface::class,
            NotaDevolutivaCausaGrupoRepository::class
        );

        $this->app->singleton(
            NotaDevolutivaCausaGrupoServiceInterface::class,
            NotaDevolutivaCausaGrupoService::class
        );

         /**
         * Nota Devolutiva Causa Raiz
         */
        $this->app->singleton(
            NotaDevolutivaCausaRaizRepositoryInterface::class,
            NotaDevolutivaCausaRaizRepository::class
        );

        $this->app->singleton(
            NotaDevolutivaCausaRaizServiceInterface::class,
            NotaDevolutivaCausaRaizService::class
        );

        

    }
}
