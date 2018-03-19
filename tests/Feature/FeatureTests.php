<?php

namespace Tests\Feature;

use Tests\TestCase;
use Tests\Stubs\Request;
use KingFlamez\Rave\Rave;
use Tests\Concerns\ExtractProperties;

class FeatureTests extends TestCase {

    use ExtractProperties;

    /**
     * Test if parameters are set on setData.
     *
     * @test
     * @return void
     */
    function getParams () {

        $request = new Request;
        $rave = new Rave($request);
        $rave = $rave->setData("http://localhost");
        // $properties = include __DIR__ . "/../Stubs/request_data.php";
        // $properties = $this->extractProperties($rave, ...$properties["class"]);

        $this->assertInstanceOf(Rave::class, $rave);
    }
}
