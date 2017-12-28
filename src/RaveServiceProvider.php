<?php

namespace KingFlamez\Rave;

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
        $this->app->bind('laravelrave', function () {

            return new Rave;

        });
    }
}