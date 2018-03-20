<?php

/**
 * Sample request data.
 *
 * @return array
 */
return [
    /**
     * Request mocking data
     */
    "form" => [
        "amount" => 30000,
        "title" => "A Title",
        "lastname" => "Last",
        "currency" => "naira",
        "country" => "Nigeria",
        "firstname" => "First",
        "payment_method" => "card",
        "phonenumber" => "08012345678",
        "email" => "email@example.org",
        "pay_button_text" => "Pay Now",
        "metadata" => "[{ flightid:3849 }]",
        "description" => "Some random description.",
        "logo" => "https://files.readme.io/ee907a0-small-rave_by_flutterwave.png",
    ],

    /**
     * Rave original properties representation.
     */
    "class" => [
        "amount",
        "country",
        "currency",
        "lastname",
        "firstname",
        ["logo" => "customLogo"],
        "phonenumber",
        ["title" => "customTitle"],
        "payButtonText",
        ["email" => "customerEmail"],
        "payment_method",
        ["description" => "customeDescription"],
        "metadata",
    ]
];
