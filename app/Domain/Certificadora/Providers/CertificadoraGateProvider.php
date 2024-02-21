<?php

namespace App\Domain\Certificadora\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider;
use Illuminate\Support\Facades\Gate;

class CertificadoraGateProvider extends AuthServiceProvider
{
    public function boot()
    {
      Gate::define('certificadora-update', function ($user)
      {
        return in_array($user->pessoa_ativa->id_tipo_pessoa, [16]);
      });
    }
}
