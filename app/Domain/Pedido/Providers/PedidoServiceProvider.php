<?php

namespace App\Domain\Pedido\Providers;

use App\Domain\Pedido\Contracts\PedidoPessoaRepositoryInterface;
use App\Domain\Pedido\Contracts\PedidoPessoaServiceInterface;
use App\Domain\Pedido\Repositories\PedidoPessoaRepository;
use App\Domain\Pedido\Services\PedidoPessoaService;
use Illuminate\Support\ServiceProvider;

use App\Domain\Pedido\Contracts\HistoricoPedidoRepositoryInterface;
use App\Domain\Pedido\Contracts\HistoricoPedidoServiceInterface;

use App\Domain\Pedido\Contracts\PedidoCentralRepositoryInterface;
use App\Domain\Pedido\Contracts\PedidoCentralServiceInterface;
use App\Domain\Pedido\Repositories\PedidoCentralRepository;
use App\Domain\Pedido\Services\PedidoCentralService;

use App\Domain\Pedido\Contracts\PedidoCentralHistoricoServiceInterface;
use App\Domain\Pedido\Contracts\PedidoCentralHistoricoRepositoryInterface;
use App\Domain\Pedido\Repositories\PedidoCentralHistoricoRepository; 
use App\Domain\Pedido\Services\PedidoCentralHistoricoService;


use App\Domain\Pedido\Contracts\PedidoRepositoryInterface;
use App\Domain\Pedido\Contracts\PedidoServiceInterface;
use App\Domain\Pedido\Contracts\PedidoTipoOrigemRepositoryInterface;
use App\Domain\Pedido\Contracts\PedidoTipoOrigemServiceInterface;
use App\Domain\Pedido\Contracts\PedidoUsuarioRepositoryInterface;
use App\Domain\Pedido\Contracts\PedidoUsuarioSenhaRepositoryInterface;
use App\Domain\Pedido\Contracts\PedidoUsuarioSenhaServiceInterface;
use App\Domain\Pedido\Contracts\PedidoUsuarioServiceInterface;
use App\Domain\Pedido\Repositories\HistoricoPedidoRepository;
use App\Domain\Pedido\Repositories\PedidoRepository;
use App\Domain\Pedido\Repositories\PedidoTipoOrigemRepository;
use App\Domain\Pedido\Repositories\PedidoUsuarioRepository;
use App\Domain\Pedido\Repositories\PedidoUsuarioSenhaRepository;
use App\Domain\Pedido\Services\HistoricoPedidoService;
use App\Domain\Pedido\Services\PedidoService;
use App\Domain\Pedido\Services\PedidoTipoOrigemService;
use App\Domain\Pedido\Services\PedidoUsuarioSenhaService;
use App\Domain\Pedido\Services\PedidoUsuarioService;



class PedidoServiceProvider extends ServiceProvider
{
    public function register()
    {
        /**
         * Pedido
         */
        $this->app->singleton(
            PedidoRepositoryInterface::class,
            PedidoRepository::class
        );

        $this->app->singleton(
            PedidoServiceInterface::class,
            PedidoService::class
        );

        /**
         * Pedido Usuário
         */
        $this->app->singleton(
            PedidoUsuarioRepositoryInterface::class,
            PedidoUsuarioRepository::class
        );

        $this->app->singleton(
            PedidoUsuarioServiceInterface::class,
            PedidoUsuarioService::class
        );

        /**
         * Pedido Usuário Senha
         */
        $this->app->singleton(
            PedidoUsuarioSenhaRepositoryInterface::class,
            PedidoUsuarioSenhaRepository::class
        );

        $this->app->singleton(
            PedidoUsuarioSenhaServiceInterface::class,
            PedidoUsuarioSenhaService::class
        );

        /**
         * Pedido Tipo Origem
         */
        $this->app->singleton(
            PedidoTipoOrigemRepositoryInterface::class,
            PedidoTipoOrigemRepository::class
        );

        $this->app->singleton(
            PedidoTipoOrigemServiceInterface::class,
            PedidoTipoOrigemService::class
        );

        /**
         * Historico Pedido
         */
        $this->app->singleton(
            HistoricoPedidoRepositoryInterface::class,
            HistoricoPedidoRepository::class
        );

        $this->app->singleton(
            HistoricoPedidoServiceInterface::class,
            HistoricoPedidoService::class
        );

        /**
         * Pedido Pessoa
         */
        $this->app->singleton(
            PedidoPessoaServiceInterface::class,
            PedidoPessoaService::class
        );

        $this->app->singleton(
            PedidoPessoaRepositoryInterface::class,
            PedidoPessoaRepository::class
        );

        /**
         * Pedido Central
         */
        $this->app->singleton(
            PedidoCentralServiceInterface::class,
            PedidoCentralService::class
        );

        $this->app->singleton(
            PedidoCentralRepositoryInterface::class,
            PedidoCentralRepository::class
        );

         /**
         * Pedido Central Historico
         */
        $this->app->singleton(
            PedidoCentralHistoricoServiceInterface::class,
            PedidoCentralHistoricoService::class
        );

        $this->app->singleton(
            PedidoCentralHistoricoRepositoryInterface::class,
            PedidoCentralHistoricoRepository::class
        );
    }
}
