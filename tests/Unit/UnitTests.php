<?php

namespace Tests\Unit;

use App;
use Rave as RaveFacade;
use Tests\TestCase;
use KingFlamez\Rave\Rave;

class UnitTests extends TestCase {

    /**
     * Tests if app returns \KingFlamez\Rave\Rave if called with ailas.
     *
     * @test
     * @return \KingFlamez\Rave\Rave
     */
    function initiateRaveFromApp () {
        $rave = $this->app->make("laravelrave");

        $this->assertTrue($rave instanceof Rave);

        return $rave;
    }

    /**
     * Tests if transaction reference is generated.
     *
     * @test
     * @depends initiateRaveFromApp
     * @return void
     */
    function generatesTransactionReference (Rave $rave) {
        $ref = $rave->getReferenceNumber();

        $prefix = $this->app->config->get("rave.prefix");

        $this->assertRegExp("/^{$prefix}_\w{13}$/", $ref);
    }
}
