<?php

namespace App\Domain\AccessToken\Providers;

use App\Domain\AccessToken\Contracts\AccessTokenRepositoryInterface;
use App\Domain\AccessToken\Contracts\AccessTokenServiceInterface;
use Illuminate\Support\ServiceProvider;
use App\Domain\AccessToken\Repositories\AccessTokenRepository;
use App\Domain\AccessToken\Services\AccessTokenService;

class AccessTokenAppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(
            AccessTokenRepositoryInterface::class,
            AccessTokenRepository::class
        );


        $this->app->singleton(
            AccessTokenServiceInterface::class,
            AccessTokenService::class
        );

    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
