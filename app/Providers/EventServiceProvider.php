<?php

namespace App\Providers;

use Aacotroneo\Saml2\Events\Saml2LoginEvent;

use App\Events\ParteCertificadoEvent;
use App\Events\SkipStatus;

use App\Listeners\SAML\BradescoListener;
use App\Listeners\SAML\TesteListener;
use App\Listeners\ChangeStatus;
use App\Listeners\VerificaSeTodasAsPartesEmitiramCertificadoEAvancaProcesso;

use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],

        ParteCertificadoEvent::class => [
            VerificaSeTodasAsPartesEmitiramCertificadoEAvancaProcesso::class,
        ],

        Saml2LoginEvent::class => [
            BradescoListener::class,
            TesteListener::class
        ],
        
        SkipStatus::class => [
            ChangeStatus::class,
        ]
    ];
}
