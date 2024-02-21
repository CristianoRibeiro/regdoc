<?php

namespace App\Domain\Usuario\Providers;

use Illuminate\Support\ServiceProvider;

use App\Domain\Usuario\Contracts\UsuarioRepositoryInterface;
use App\Domain\Usuario\Contracts\UsuarioServiceInterface;
use App\Domain\Usuario\Repositories\UsuarioRepository;
use App\Domain\Usuario\Services\UsuarioService;

use App\Domain\Usuario\Contracts\UsuarioCertificadoRepositoryInterface;
use App\Domain\Usuario\Contracts\UsuarioCertificadoServiceInterface;
use App\Domain\Usuario\Repositories\UsuarioCertificadoRepository;
use App\Domain\Usuario\Services\UsuarioCertificadoService;

class UsuarioServiceProvider extends ServiceProvider
{
    public function register()
    {
        /**
         * Usuário
         */
        $this->app->singleton(
            UsuarioRepositoryInterface::class,
            UsuarioRepository::class
        );

        $this->app->singleton(
            UsuarioServiceInterface::class,
            UsuarioService::class
        );

        /**
         * Usuário Certificado
         */
        $this->app->singleton(
            UsuarioCertificadoRepositoryInterface::class,
            UsuarioCertificadoRepository::class
        );

        $this->app->singleton(
            UsuarioCertificadoServiceInterface::class,
            UsuarioCertificadoService::class
        );
    }
}
