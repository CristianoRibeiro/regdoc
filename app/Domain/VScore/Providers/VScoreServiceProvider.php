<?php

namespace App\Domain\VScore\Providers;

use Illuminate\Support\ServiceProvider;

use App\Domain\VScore\Contracts\VScoreTransacaoLoteRepositoryInterface;
use App\Domain\VScore\Contracts\VScoreTransacaoLoteServiceInterface;
use App\Domain\VScore\Repositories\VScoreTransacaoLoteRepository;
use App\Domain\VScore\Services\VScoreTransacaoLoteService;

use App\Domain\VScore\Contracts\VScoreTransacaoRepositoryInterface;
use App\Domain\VScore\Contracts\VScoreTransacaoServiceInterface;
use App\Domain\VScore\Repositories\VScoreTransacaoRepository;
use App\Domain\VScore\Services\VScoreTransacaoService;

use App\Domain\VScore\Contracts\VScoreTransacaoSituacaoRepositoryInterface;
use App\Domain\VScore\Contracts\VScoreTransacaoSituacaoServiceInterface;
use App\Domain\VScore\Repositories\VScoreTransacaoSituacaoRepository;
use App\Domain\VScore\Services\VScoreTransacaoSituacaoService;

class VScoreServiceProvider extends ServiceProvider
{
    public function register()
    {
        /**
         * VScoreTransacaoLote
         */
        $this->app->singleton(
            VScoreTransacaoLoteRepositoryInterface::class,
            VScoreTransacaoLoteRepository::class
        );

        $this->app->singleton(
            VScoreTransacaoLoteServiceInterface::class,
            VScoreTransacaoLoteService::class
        );
        
        /**
         * VScoreTransacao
         */
        $this->app->singleton(
            VScoreTransacaoRepositoryInterface::class,
            VScoreTransacaoRepository::class
        );

        $this->app->singleton(
            VScoreTransacaoServiceInterface::class,
            VScoreTransacaoService::class
        );  

        /**
         * VScoreTransacaoSituacao
         */
        $this->app->singleton(
            VScoreTransacaoSituacaoRepositoryInterface::class,
            VScoreTransacaoSituacaoRepository::class
        );

        $this->app->singleton(
            VScoreTransacaoSituacaoServiceInterface::class,
            VScoreTransacaoSituacaoService::class
        );
    }
}
