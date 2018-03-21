<?php

namespace Tests\Feature;

use Tests\TestCase;
use Tests\Stubs\Request;
use KingFlamez\Rave\Rave;
use Unirest\Request\Body;
use Illuminate\Support\Collection;
use Tests\Stubs\PaymentEventHandler;
use Tests\Concerns\ExtractProperties;
use Unirest\Request as UnirestRequest;

class FeatureTests extends TestCase {

    use ExtractProperties;

    /**
     * Test if parameters are set on setData.
     *
     * @test
     * @return \KingFlamez\Rave\Rave
     */
    function getParams () {

        $request = new Request;
        $rave = new Rave($request, new UnirestRequest, new Body);
        $rave = $rave->setData("http://localhost");

        $this->assertInstanceOf("KingFlamez\Rave\Rave", $rave);
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

        $response = $rave->eventHandler(new PaymentEventHandler)->initialize("http://localhost");

        $values = json_decode($response, true);

        $class = $this->data["class"];

        $this->assertArrayHasKey("meta", $values);
        $this->assertArrayHasKey("txref", $values);
        $this->assertArrayHasKey("amount", $values);
        $this->assertArrayHasKey("country", $values);
        $this->assertArrayHasKey("currency", $values);
        $this->assertArrayHasKey("PBFPubKey", $values);
        $this->assertArrayHasKey("custom_logo", $values);
        $this->assertArrayHasKey("redirect_url", $values);
        $this->assertArrayHasKey("integrity_hash", $values);
        $this->assertArrayHasKey("payment_method", $values);
        $this->assertArrayHasKey("customer_phone", $values);
        $this->assertArrayHasKey("customer_email", $values);
        $this->assertArrayHasKey("pay_button_text", $values);
        $this->assertArrayHasKey("customer_lastname", $values);
        $this->assertArrayHasKey("custom_description", $values);
        $this->assertArrayHasKey("customer_firstname", $values);
    }

    /**
     * Test if proper actions are taken when payment is cancelled.
     *
     * @test
     * @return void
     */
    function paymentCancelledTest() {

        $rave = new Rave(new Request, new UnirestRequest, new Body);
        $rave = $rave->createReferenceNumber();
        $ref = $rave->getReferenceNumber();

        // This section tests if json is returned when no handler is set.
        $returned = $rave->paymentCanceled($ref);

        $this->assertInternalType("string", $returned);

        // Tests if json has certain keys when payment is cancelled.
        $returned = json_decode($returned, true);

        $this->assertArrayHasKey("txref", $returned);
        $this->assertArrayHasKey("status", $returned);

        // This section tests if instance of rave is returned when a handler is set.
        $rave = $rave->eventHandler(new PaymentEventHandler)->paymentCanceled($ref);

        $this->assertInstanceOf("KingFlamez\Rave\Rave", $rave);

        return $ref;
    }

    /**
     * Testing requery transactions.
     *
     * @test
     * @depends paymentCancelledTest
     * @dataProvider providesResponse
     * @runInSeparateProcess
     * @preserveGlobalState disabled
     * @param  string $ref txref
     */
    function requeryTransactionTransactionTest($mResponse, string $ref) {

        $data = [
            'txref' => $ref,
            'SECKEY' => $this->app->config->get("secretKey"),
            'last_attempt' => '1'
            // 'only_successful' => '1'
        ];

        $url = "https://rave-api-v2.herokuapp.com";
        $headers = ['Content-Type' => 'application/json'];

        $data = Body::json($data);
        $response = json_encode($mResponse);

        $decodedResponse = json_decode($response);

        $mRequest = $this->m->mock("alias:Unirest\Request");
        $mRequest->shouldReceive("post")
                 ->andReturn($decodedResponse);

        $rave = new Rave(new Request, $mRequest, new Body);

        $raveResponse = $rave->requeryTransaction($ref);

        // Test if data is returned when no handler.
        // $this->assertEquals($decodedResponse->body->status, $raveResponse->status);

        $this->setProperty($rave, "handler", new PaymentEventHandler);

        $raveResponse = $rave->requeryTransaction($ref);

        // Tests that an instance of rave is returned when a handler is set
        $this->assertInstanceOf("KingFlamez\Rave\Rave", $raveResponse);
    }

    /**
     * Provides data for all events of requery transaction.
     *
     * @return array
     */
    function providesResponse () {

        return [
            [
                [
                    "body" => [
                        "status" => "unknown",
                        "data" => ["status", "unknown"]
                    ],
                ],
            ],
            [
                [
                    "body" => [
                        "status" => "success",
                    ],
                ]
            ],
            [
                [
                    "body" => [
                        "status" => "success",
                        "data" => [
                            "status" => "failed"
                        ]
                    ],
                ]
            ],
            [
                [
                    "body" => [
                        "status" => "success",
                        "data" => [
                            "status" => "successful"
                        ]
                    ],
                ]
            ]
        ];
    }
}
