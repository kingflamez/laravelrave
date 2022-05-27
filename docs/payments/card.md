# Charge via Card

This document describes how to collect payments via Card.

```php
<?php

$tx_ref = Flutterwave::generateReference();
$order_id = Flutterwave::generateReference('card');

$data = [
    'amount' => 100,
    'email' => 'wole@email.co',
    'redirect_url' => route('callback')
    'tx_ref' => $tx_ref,
    'card_number' => '5399670123490229',
    'cvv' => 123,
    'expiry_month' => '05',
    'expiry_year' => '45',
    'subaccounts' => [ 
        ["id" => "RS_D87A9EE339AE28BFA2AE86041C6DE70E"],
        ["id" => "RS_B45A9VV221HQ28UYA2AE97681C6DR44R"]
    ]
];

$charge = Flutterwave::payments()->card($data);

if ($charge['status'] === 'success') {
    # code...
    //Handle Authorization Mode
    if($charge['data']['mode'] == 'redirect'){
        // Redirect to the charge url
        return redirect($charge['data']['redirect']);

    }elseif($charge['data']['mode'] == 'otp'){
        // Validate with OTP and FLW_REF

    }elseif($charge['data']['mode'] == 'avs_noauth'){
        //Charge again with the following data city, address, state, country, and zipcode

    }elseif($charge['data']['mode'] == 'pin'){
        //Charge again with the card PIN
        
    }
    
}
```

## Parameters

| Parameter          | Required | Description                                                                                                                                                                              |
| ------------------ | -------- | ---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- |
| amount             | True     | This is the amount to be charged. Expected value is ZMW                                                                                                                                  |
| card_number              | True     | This is the number on the cardholders card. E.g. 5399 6701 2349 0229                                                                                                                                 |
| cvv             | True     | Card security code. This is 3/4 digit code at the back of the customers card, used for web payments.                       |
| expiry_month           | True     | Two-digit number representing the card's expiration month. It is usually the first two digits of the expiry date on the card.                                                                                              |
| expiry_year           | True     | Unique ref for the mobilemoney transaction to be provided by the merchant.                                                                                                               |
| email            | True     | Two-digit number representing the card's expiration year. It is the last two digits of the expiry date on the card.                                                                                              |
| tx_ref           | True    | This is a unique reference peculiar to the transaction being carried out.                                                                                                  |
| currency           | False    | This is the specified currency to charge in.                                                                                                  |
| phone_number       | False    | This is the phone number linked to the customer's Bank account or mobile money account                                                                                                                  |
| fullname       | False    | This is the name of the customer making the payment.                                                                                                            |
| preauthoize       | False    | This should be set to true for preauthoize card transactions.                                                                                                        |
| redirect_url       | False    | URL to redirect to when a transaction is completed.                                                                                                                                      |
| client_ip          | False    | IP - Internet Protocol. This represents the current IP address of the customer carrying out the transaction                                                                              |
| device_fingerprint | False    | This is the fingerprint for the device being used. It can be generated using a library on whatever platform is being used.                                                               |
| meta               | False    | This is used to include additional payment information`                                                                                                                                  |
| subaccounts        | False    | This is an array of objects containing the subaccount IDs to split the payment into. Check our Split Payment page for more info. eg `[ ["id" => "RS_D87A9EE339AE28BFA2AE86041C6DE70E"]]` |
| meta               | False    | This is an object that helps you include additional payment information to your request e.g ['consumer_id'=>23, 'consumer_mac'=>'92a3-912ba-1192a']                                      |


