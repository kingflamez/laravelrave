<?php

namespace Tests\Stubs;

use Illuminate\Http\Request as BaseRequest;

class Request extends BaseRequest {

    public $email;
    public $amount;
    public $country;
    public $currency;
    public $lastname;
    public $firstname;
    public $phonenumber;
    public $description;
    public $payment_method;
    public $pay_button_text;

    function __construct( ) {

        $data = (array) include __DIR__ . "/request_data.php";

        array_walk($data["form"], function($value, $key) {

            $this->{$key} = $value;
        });
    }
}
