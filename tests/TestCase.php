<?php

namespace Tests;

use Orchestra\Testbench\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase {

    /**
     * Register package.
     *
     * @param  \Illuminate\Foundation\Application  $app
     * @return array      Packages to register
     */
    protected function getPackageProviders($app)
    {
        return [ \KingFlamez\Rave\RaveServiceProvider::class ];
    }

    protected function getPackageAliases($app)
    {
        return [
            "Rave" => \KingFlamez\Rave\Facades\Rave::class
        ];
    }

    /**
     * Configure Environment.
     *
     * @param  \Illuminate\Foundation\Application  $app
     * @return void
     */
    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set("rave.prefix", "rave");
    }
}
