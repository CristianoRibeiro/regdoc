<?php

namespace App\Domain\VTicket\Providers;

use Illuminate\Support\ServiceProvider;

use App\Domain\VTicket\Contracts\VTicketSituacaoRepositoryInterface;
use App\Domain\VTicket\Contracts\VTicketSituacaoServiceInterface;
use App\Domain\VTicket\Repositories\VTicketSituacaoRepository;
use App\Domain\VTicket\Services\VTicketSituacaoService;

class VTicketServiceProvider extends ServiceProvider
{
    public function register()
    {
        /**
         * VTicketSituacao
         */
        $this->app->singleton(
            VTicketSituacaoRepositoryInterface::class,
            VTicketSituacaoRepository::class
        );

        $this->app->singleton(
            VTicketSituacaoServiceInterface::class,
            VTicketSituacaoService::class
        );
    }
}
