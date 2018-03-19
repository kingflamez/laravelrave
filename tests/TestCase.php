<?php

namespace Tests;

use Mockery;
use Orchestra\Testbench\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase {

    public $m;

    function setUp () {

        $this->m = new Mockery;

        parent::setUp();
    }

    /**
     * Clear mockery after every test in preparation for a new mock.
     *
     * @return void
     */
    function tearDown() {

        $this->m->close();

        parent::tearDown();
    }

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

    /**
     * Get alias packages from app.
     *
     * @param  \illuminate\Foundation\Application $app
     * @return array      Aliases.
     */
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
        $envVars = (array) include_once __DIR__ . "/Stubs/env.php";

        array_walk($envVars, function ($value, $key) use (&$app) {

            $app["config"]->set("rave.{$key}", $value);
        });
    }
}
