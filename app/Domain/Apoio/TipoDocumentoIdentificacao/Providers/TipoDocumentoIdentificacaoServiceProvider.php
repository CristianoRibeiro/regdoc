<?php

namespace App\Domain\Apoio\TipoDocumentoIdentificacao\Providers;

use Illuminate\Support\ServiceProvider;

use App\Domain\Apoio\TipoDocumentoIdentificacao\Contracts\TipoDocumentoIdentificacaoRepositoryInterface;
use App\Domain\Apoio\TipoDocumentoIdentificacao\Contracts\TipoDocumentoIdentificacaoServiceInterface;
use App\Domain\Apoio\TipoDocumentoIdentificacao\Repositories\TipoDocumentoIdentificacaoRepository;
use App\Domain\Apoio\TipoDocumentoIdentificacao\Services\TipoDocumentoIdentificacaoService;

class TipoDocumentoIdentificacaoServiceProvider extends ServiceProvider
{
    public function register()
    {
        /**
         * TipoDocumentoIdentificacao
         */
        $this->app->singleton(
            TipoDocumentoIdentificacaoRepositoryInterface::class,
            TipoDocumentoIdentificacaoRepository::class
        );

        $this->app->singleton(
            TipoDocumentoIdentificacaoServiceInterface::class,
            TipoDocumentoIdentificacaoService::class
        );
    }
}
