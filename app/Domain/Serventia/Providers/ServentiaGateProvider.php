<?php

namespace App\Domain\Serventia\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

use Gate;

class ServentiaGateProvider extends ServiceProvider
{
    /**
     * Register Gates.
     *
     * @return void
     */
    public function boot()
    {
        /**
         * Gate:Nova Serventia
         *      Utilização: Usado para inserir uma nova serventia;
         *      Entidades: 1 (Administrador),  13 (Suporte).
         */
        Gate::define('serventia-nova', function ($user) {
            if (in_array($user->pessoa_ativa->id_tipo_pessoa, [1, 13])) {
                return true;
            }

            return false;
        });

        /**
         * Gate:Detalhes Serventia
         *      Utilização: Usado para ver os detalhes da serventia;
         *      Entidades: 1 (Administrador),  13 (Suporte).
         */
        Gate::define('serventia-detalhes', function ($user) {
            if (in_array($user->pessoa_ativa->id_tipo_pessoa, [1, 13])) {
                return true;
            }

            return false;
        });

    }
}
