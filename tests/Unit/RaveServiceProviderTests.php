<?php

namespace Tests\Unit;

use Tests\TestCase;
use KingFlamez\Rave\Rave;

class RaveServiceProviderTests extends TestCase
{
    /**
     * Tests if service provider Binds alias "laravelrave" to \KingFlamez\Rave\Rave
     *
     * @test
     */
    public function isBound()
    {
        $this->assertTrue($this->app->bound('laravelrave'));
    }
    /**
     * Test if service provider returns \Rave as alias for \KingFlamez\Rave\Rave
     *
     * @test
     */
    public function hasAliased()
    {
        $this->assertTrue($this->app->isAlias(Rave::class));
        $this->assertEquals('laravelrave', $this->app->getAlias(Rave::class));
    }
}
