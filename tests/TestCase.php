<?php

namespace Tests;

use Orchestra\Testbench\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase {

    protected function getPackageProviders($app)
    {
        return [ \KingFlamez\Rave\RaveServiceProvider::class ];
    }

    protected function getPackageAliases($app)
    {
        return [
            'Rave' => \KingFlamez\Rave\Facades\Rave::class,
        ];
    }
}
