<?php

namespace KingFlamez\Rave;

use Unirest\Request;
use Unirest\Request\Body;
use Illuminate\Support\ServiceProvider;

class RaveServiceProvider extends ServiceProvider
{
    protected $defer = false;

    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot()
    {
        $config = realpath(__DIR__.'/../resources/config/rave.php');

        $this->publishes([
            $config => config_path('rave.php')
        ]);
    }

    /**
     * Register any package services.
     *
     * @return void
     */
    public function register()
    {

        $this->app->singleton('laravelrave', function ($app) {

            return new Rave($app->make("request"), new Request, new Body);

        });

        $this->app->alias('laravelrave', "KingFlamez\Rave\Rave");
    }

    /**
    * Get the services provided by the provider
    *
    * @return array
    */
    public function provides()
    {
        return ['laravelrave'];
    }
}
