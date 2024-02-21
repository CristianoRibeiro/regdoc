<?php

namespace App\Domain\Arisp\Providers;

use Illuminate\Support\ServiceProvider;

use App\Domain\Arisp\Contracts\ArispAnexoRepositoryInterface;
use App\Domain\Arisp\Contracts\ArispAnexoServiceInterface;
use App\Domain\Arisp\Repositories\ArispAnexoRepository;
use App\Domain\Arisp\Services\ArispAnexoService;

use App\Domain\Arisp\Contracts\ArispAnexoTipoRepositoryInterface;
use App\Domain\Arisp\Contracts\ArispAnexoTipoServiceInterface;
use App\Domain\Arisp\Repositories\ArispAnexoTipoRepository;
use App\Domain\Arisp\Services\ArispAnexoTipoService;

use App\Domain\Arisp\Contracts\ArispBoletoRepositoryInterface;
use App\Domain\Arisp\Contracts\ArispBoletoServiceInterface;
use App\Domain\Arisp\Repositories\ArispBoletoRepository;
use App\Domain\Arisp\Services\ArispBoletoService;

use App\Domain\Arisp\Contracts\ArispArquivoRepositoryInterface;
use App\Domain\Arisp\Contracts\ArispArquivoServiceInterface;
use App\Domain\Arisp\Repositories\ArispArquivoRepository;
use App\Domain\Arisp\Services\ArispArquivoService;

use App\Domain\Arisp\Contracts\ArispPedidoHistoricoRepositoryInterface;
use App\Domain\Arisp\Contracts\ArispPedidoHistoricoServiceInterface;
use App\Domain\Arisp\Repositories\ArispPedidoHistoricoRepository;
use App\Domain\Arisp\Services\ArispPedidoHistoricoService;

class ArispServiceProvider extends ServiceProvider
{
    public function register()
    {
        /**
         * Arisp Anexo
         */
        $this->app->singleton(
            ArispAnexoRepositoryInterface::class,
            ArispAnexoRepository::class
        );
        $this->app->singleton(
            ArispAnexoServiceInterface::class,
            ArispAnexoService::class
        );

        /**
         * Arisp Anexo Tipo
         */
        $this->app->singleton(
            ArispAnexoTipoRepositoryInterface::class,
            ArispAnexoTipoRepository::class
        );
        $this->app->singleton(
            ArispAnexoTipoServiceInterface::class,
            ArispAnexoTipoService::class
        );

        /**
         * Arisp Boleto
         */
        $this->app->singleton(
            ArispBoletoRepositoryInterface::class,
            ArispBoletoRepository::class
        );
        $this->app->singleton(
            ArispBoletoServiceInterface::class,
            ArispBoletoService::class
        );

        /**
         * Arisp Arquivo
         */
        $this->app->singleton(
            ArispArquivoRepositoryInterface::class,
            ArispArquivoRepository::class
        );

        $this->app->singleton(
            ArispArquivoServiceInterface::class,
            ArispArquivoService::class
        );

        /**
         * Arisp Pedido Historio 
         */
        $this->app->singleton(
            ArispPedidoHistoricoRepositoryInterface::class,
            ArispPedidoHistoricoRepository::class
        );

        $this->app->singleton(
            ArispPedidoHistoricoServiceInterface::class,
            ArispPedidoHistoricoService::class
        );
    }
}
