<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Application Name
    |--------------------------------------------------------------------------
    |
    | This value is the name of your application. This value is used when the
    | framework needs to place the application's name in a notification or
    | any other location as required by the application or its packages.
    |
    */

    'name' => env('APP_NAME', 'Laravel'),

    /*
    |--------------------------------------------------------------------------
    | Application Environment
    |--------------------------------------------------------------------------
    |
    | This value determines the "environment" your application is currently
    | running in. This may determine how you prefer to configure various
    | services your application utilizes. Set this in your ".env" file.
    |
    */

    'env' => env('APP_ENV', 'local'),
    'version' => env('APP_VERSION', '2.0.6'),
    'email_regdoc' => env('EMAIL_REGDOC', 'regdoc@valid.com'),
    'portal_vidaas_emails' => env('PORTAL_VIDAAS_EMAILS'),
    'max_upload' => env('MAX_UPLOAD', 5000),
    'force_ssl' => env('FORCE_SSL', false),

    /*
    |--------------------------------------------------------------------------
    | Application Debug Mode
    |--------------------------------------------------------------------------
    |
    | When your application is in debug mode, detailed error messages with
    | stack traces will be shown on every error that occurs within your
    | application. If disabled, a simple generic error page is shown.
    |
    */

    'debug' => env('APP_DEBUG', true),

    'debug_blacklist' => [
        '_COOKIE' => array_keys($_COOKIE),
        '_SERVER' => array_keys($_SERVER),
        '_ENV' => array_keys($_ENV),
    ],

    /*
    |--------------------------------------------------------------------------
    | Application URL
    |--------------------------------------------------------------------------
    |
    | This URL is used by the console to properly generate URLs when using
    | the Artisan command line tool. You should set this to the root of
    | your application so that it is used when running Artisan tasks.
    |
    */

    'url' => env('APP_URL', 'http://localhost'),

    'asset_url' => env('ASSET_URL', null),

    /*
    |--------------------------------------------------------------------------
    | Application Timezone
    |--------------------------------------------------------------------------
    |
    | Here you may specify the default timezone for your application, which
    | will be used by the PHP date and date-time functions. We have gone
    | ahead and set this to a sensible default for you out of the box.
    |
    */

    'timezone' => 'America/Sao_Paulo',

    /*
    |--------------------------------------------------------------------------
    | Application Locale Configuration
    |--------------------------------------------------------------------------
    |
    | The application locale determines the default locale that will be used
    | by the translation service provider. You are free to set this value
    | to any of the locales which will be supported by the application.
    |
    */

    'locale' => 'pt-br',

    /*
    |--------------------------------------------------------------------------
    | Application Fallback Locale
    |--------------------------------------------------------------------------
    |
    | The fallback locale determines the locale to use when the current one
    | is not available. You may change the value to correspond to any of
    | the language folders that are provided through your application.
    |
    */

    'fallback_locale' => 'pt-br',

    /*
    |--------------------------------------------------------------------------
    | Encryption Key
    |--------------------------------------------------------------------------
    |
    | This key is used by the Illuminate encrypter service and should be set
    | to a random, 32 character string, otherwise these encrypted strings
    | will not be safe. Please do this before deploying an application!
    |
    */

    'key' => env('APP_KEY'),

    'cipher' => 'AES-256-CBC',

    /*
    |--------------------------------------------------------------------------
    | Logging Configuration
    |--------------------------------------------------------------------------
    |
    | Here you may configure the log settings for your application. Out of
    | the box, Laravel uses the Monolog PHP logging library. This gives
    | you a variety of powerful log handlers / formatters to utilize.
    |
    | Available Settings: "single", "daily", "syslog", "errorlog"
    |
    */

    'log' => env('APP_LOG', 'single'),

    'log_level' => env('APP_LOG_LEVEL', 'debug'),

    /*
    |--------------------------------------------------------------------------
    | Autoloaded Service Providers
    |--------------------------------------------------------------------------
    |
    | The service providers listed here will be automatically loaded on the
    | request to your application. Feel free to add your own services to
    | this array to grant expanded functionality to your applications.
    |
    */

    'providers' => [

        /*
         * Laravel Framework Service Providers...
         */
        Illuminate\Auth\AuthServiceProvider::class,
        Illuminate\Broadcasting\BroadcastServiceProvider::class,
        Illuminate\Bus\BusServiceProvider::class,
        Illuminate\Cache\CacheServiceProvider::class,
        Illuminate\Foundation\Providers\ConsoleSupportServiceProvider::class,
        Illuminate\Cookie\CookieServiceProvider::class,
        Illuminate\Database\DatabaseServiceProvider::class,
        Illuminate\Encryption\EncryptionServiceProvider::class,
        Illuminate\Filesystem\FilesystemServiceProvider::class,
        Illuminate\Foundation\Providers\FoundationServiceProvider::class,
        Illuminate\Hashing\HashServiceProvider::class,
        Illuminate\Mail\MailServiceProvider::class,
        Illuminate\Notifications\NotificationServiceProvider::class,
        Illuminate\Pagination\PaginationServiceProvider::class,
        Illuminate\Pipeline\PipelineServiceProvider::class,
        Illuminate\Queue\QueueServiceProvider::class,
        Illuminate\Redis\RedisServiceProvider::class,
        Illuminate\Auth\Passwords\PasswordResetServiceProvider::class,
        Illuminate\Session\SessionServiceProvider::class,
        Illuminate\Translation\TranslationServiceProvider::class,
        Illuminate\Validation\ValidationServiceProvider::class,
        Illuminate\View\ViewServiceProvider::class,

        /*
         * Package Service Providers...
         */
        LaravelLegends\PtBrValidator\ValidatorProvider::class,
        Maatwebsite\Excel\ExcelServiceProvider::class,
        Superbalist\LaravelGoogleCloudStorage\GoogleCloudStorageServiceProvider::class,

        /*
         * Application Service Providers...
         */
        App\Providers\AppServiceProvider::class,
        App\Providers\AuthServiceProvider::class,
        // App\Providers\BroadcastServiceProvider::class,
        App\Providers\EventServiceProvider::class,
        App\Providers\RouteServiceProvider::class,

        App\Providers\GatesProvider::class,
        App\Domain\RegistroFiduciario\Providers\RegistroAPIGateProvider::class,

        /**
         * Providers
         */
        App\Domain\RegistroFiduciario\Providers\RegistroFiduciarioGateProvider::class,
        App\Domain\RegistroFiduciario\Providers\ProtocoloGateProvider::class,
        App\Domain\RegistroFiduciario\Providers\RegistroFiduciarioServiceProvider::class,
        App\Domain\RegistroFiduciarioAssinatura\Providers\RegistroFiduciarioAssinaturaServiceProvider::class,
        App\Domain\RegistroFiduciarioArquivoPadrao\Providers\RegistroFiduciarioArquivoPadraoServiceProvider::class,
        App\Domain\Registro\Providers\RegistroTipoParteTipoPessoaServiceProvider::class,

        App\Domain\Certificadora\Providers\CertificadoraGateProvider::class,
        App\Domain\Parte\Providers\ParteEmissaoCertificadoGateProvider::class,
        App\Domain\Construtora\Providers\ConstrutoraServiceProvider::class,
        App\Domain\Estado\Providers\EstadoServiceProvider::class,
        App\Domain\Pedido\Providers\PedidoServiceProvider::class,
        App\Domain\Pessoa\Providers\PessoaServiceProvider::class,
        App\Domain\Procurador\Providers\ProcuradorServiceProvider::class,
        App\Domain\Usuario\Providers\UsuarioServiceProvider::class,
        App\Domain\Arisp\Providers\ArispServiceProvider::class,
        App\Domain\Parte\Providers\ParteServiceProvider::class,
        App\Domain\Arquivo\Providers\ArquivoServiceProvider::class,
        App\Domain\Procuracao\Providers\ProcuracaoServiceProvider::class,
        App\Domain\Integracao\Providers\IntegracaoServiceProvider::class,
        App\Domain\Portal\Providers\PortalServiceProvider::class,
        App\Domain\Serventia\Providers\ServentiaGateProvider::class,
        App\Domain\Serventia\Providers\ServentiaServiceProvider::class,
        App\Domain\Configuracao\Providers\ConfiguracaoServiceProvider::class,
        App\Domain\Checklist\Providers\ChecklistServiceProvider::class,
        App\Domain\AccessToken\Providers\AccessTokenAppServiceProvider::class,

        // Domínios de apoio
        App\Domain\Apoio\TipoDocumentoIdentificacao\Providers\TipoDocumentoIdentificacaoServiceProvider::class,
        App\Domain\Apoio\Nacionalidade\Providers\NacionalidadeServiceProvider::class,
        App\Domain\Apoio\EstadoCivil\Providers\EstadoCivilServiceProvider::class,

        // Documento eletrônico
        App\Domain\Documento\Documento\Providers\DocumentoServiceProvider::class,
        App\Domain\Documento\Documento\Providers\DocumentoGateProvider::class,
        App\Domain\Documento\Assinatura\Providers\DocumentoAssinaturaServiceProvider::class,
        App\Domain\Documento\Parte\Providers\DocumentoParteServiceProvider::class,

        // Tabela Emolumentos
        App\Domain\TabelaEmolumento\Providers\TabelaEmolumentoServiceProvider::class,

        // V/Score
        App\Domain\VScore\Providers\VScoreServiceProvider::class,

        // VTicket
        App\Domain\VTicket\Providers\VTicketServiceProvider::class,

        // Nota Devolutiva
        App\Domain\NotaDevolutiva\Providers\NotaDevolutivaProvider::class,

        // Canais Pdv
        App\Domain\CanaisPdv\Providers\CanalPdvServiceProvider::class,
        App\Domain\CanaisPdv\Providers\CanalPdvGateProvider::class,
    ],

    /*
    |--------------------------------------------------------------------------
    | Class Aliases
    |--------------------------------------------------------------------------
    |
    | This array of class aliases will be registered when this application
    | is started. However, feel free to register as many as you wish as
    | the aliases are "lazy" loaded so they don't hinder performance.
    |
    */

    'aliases' => [

        'App' => Illuminate\Support\Facades\App::class,
        'Artisan' => Illuminate\Support\Facades\Artisan::class,
        'Auth' => Illuminate\Support\Facades\Auth::class,
        'Blade' => Illuminate\Support\Facades\Blade::class,
        'Broadcast' => Illuminate\Support\Facades\Broadcast::class,
        'Bus' => Illuminate\Support\Facades\Bus::class,
        'Cache' => Illuminate\Support\Facades\Cache::class,
        'Config' => Illuminate\Support\Facades\Config::class,
        'Cookie' => Illuminate\Support\Facades\Cookie::class,
        'Crypt' => Illuminate\Support\Facades\Crypt::class,
        'DB' => Illuminate\Support\Facades\DB::class,
        'Eloquent' => Illuminate\Database\Eloquent\Model::class,
        'Event' => Illuminate\Support\Facades\Event::class,
        'File' => Illuminate\Support\Facades\File::class,
        'Gate' => Illuminate\Support\Facades\Gate::class,
        'Hash' => Illuminate\Support\Facades\Hash::class,
        'Lang' => Illuminate\Support\Facades\Lang::class,
        'Log' => Illuminate\Support\Facades\Log::class,
        'Mail' => Illuminate\Support\Facades\Mail::class,
        'Notification' => Illuminate\Support\Facades\Notification::class,
        'Password' => Illuminate\Support\Facades\Password::class,
        'Queue' => Illuminate\Support\Facades\Queue::class,
        'Redirect' => Illuminate\Support\Facades\Redirect::class,
        'Redis' => Illuminate\Support\Facades\Redis::class,
        'Request' => Illuminate\Support\Facades\Request::class,
        'Response' => Illuminate\Support\Facades\Response::class,
        'Route' => Illuminate\Support\Facades\Route::class,
        'Schema' => Illuminate\Support\Facades\Schema::class,
        'Session' => Illuminate\Support\Facades\Session::class,
        'Storage' => Illuminate\Support\Facades\Storage::class,
        'URL' => Illuminate\Support\Facades\URL::class,
        'Validator' => Illuminate\Support\Facades\Validator::class,
        'View' => Illuminate\Support\Facades\View::class,
        'Str' => Illuminate\Support\Str::class,

        // Funções personalizadas
        'Helper' => App\Helpers\Helper::class,
        'Upload' => App\Helpers\Upload::class,
        'LacunaPades' => App\Helpers\LacunaPades::class,
        'SMS' => App\Helpers\SMS::class,
        'LogDB' => App\Helpers\LogDB::class,
        'ARISP' => App\Helpers\ARISP::class,
        'ARISPExtrato' => App\Helpers\ARISPExtrato::class,
        'PDAVH' => App\Helpers\PDAVH::class,
        'VALIDCorporate' => App\Helpers\VALIDCorporate::class,
        'VALIDTicket' => App\Helpers\VALIDTicket::class,
        'VALIDScore' => App\Helpers\VALIDScore::class,
        'CAProxy' => App\Helpers\CAProxy::class,

        //Excel
        'Excel' => Maatwebsite\Excel\Facades\Excel::class,
        'Debugbar' => Barryvdh\Debugbar\Facades\Debugbar::class,
    ],


    // File upload limit in bytes
    'UPLOAD_LIMIT' => env('UPLOAD_LIMIT', 5000)

];
