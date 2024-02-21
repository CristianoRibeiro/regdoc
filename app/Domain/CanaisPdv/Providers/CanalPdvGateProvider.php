<?php

namespace App\Domain\CanaisPdv\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

use Gate;

class CanalPdvGateProvider extends ServiceProvider
{
    /**
     * Register Gates.
     *
     * @return void
     */
    public function boot()
    {
        /**
         * Gate:Nova Canal pdv parceiro
         *      Utilização: Usado para inserir uma novo canal de parceiro;
         *      Entidades: 1 (Administrador),  13 (Suporte).
         */
        Gate::define('novo-canal-pdv-parceiro', function ($user) {
            if (in_array($user->pessoa_ativa->id_tipo_pessoa, [1, 13])) {
                return true;
            }

            return false;
        });

        /**
         * Gate:Detalhes Canal pdv parceiro
         *      Utilização: Usado para ver os detalhes do canal parceiro;
         *      Entidades: 1 (Administrador),  8 (Instituição financeira), 13 (Suporte).
         */
        Gate::define('detalhes-canal-pdv-parceiro', function ($user) {
            if (in_array($user->pessoa_ativa->id_tipo_pessoa, [1, 8, 13])) {
                return true;
            }

            return false;
        });

    }
}
