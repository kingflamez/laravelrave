<?php

namespace Tests\Feature;

use Tests\TestCase;
use Tests\Stubs\Request;
use KingFlamez\Rave\Rave;
use Tests\Stubs\PaymentEventHandler;
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
        return $rave;
    }

    /**
     * Test if hash is created.
     *
     * @test
     * @depends getParams
     * @return void
     */
    function creatingCheckSum(Rave $rave) {

        $rave = $rave->createReferenceNumber();
        $publicKey = "FLWPUBK-MOCK-1cf610974690c2560cb4c36f4921244a-X";
        $rave = $rave->setData("http://localhost");
        $rave = $rave->createCheckSum();

        $hash = $this->extractProperty($rave, "integrityHash");

        $this->assertEquals(64, strlen($hash["value"]));

        return $rave;
    }

    /**
     * Testing payment.
     *
     * @test
     * @depends creatingCheckSum
     * @return void
     */
    function paymentInitialize(Rave $rave) {

        $rave->setHandler(new PaymentEventHandler)->initialize("http://localhost");
    }
}
