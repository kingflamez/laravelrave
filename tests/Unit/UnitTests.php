<?php

namespace Tests\Unit;

use Rave;
use Tests\TestCase;

class UnitTests extends TestCase {

    /**
     * @test
     * @return void
     */
    function initiateRaveWithFacade () {
        $rave = new Rave;

        $this->assertTrue($rave instanceof \KingFlamez\Rave\Rave);
    }
}
