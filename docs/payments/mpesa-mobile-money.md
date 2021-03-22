# Charge via Mpesa

This document describes how to collect payments via Mpesa.

```php
<?php

$tx_ref = Flutterwave::generateReference();

$data = [
    'amount' => 1500,
    'email' => 'wole@email.co',
    'phone_number' => '054709929220',
    'tx_ref' => $tx_ref
];

$charge = Flutterwave::payments()->mpesa($data);

if ($charge['status'] === 'success') {
    # code...
    // Redirect to the charge url
    $data = Flutterwave::verifyTransaction($charge['data']['id']);
    return dd($data);
    // Get the transaction from your DB using the transaction reference (txref)
    // Check if you have previously given value for the transaction. If you have, redirect to your successpage else, continue
    // Confirm that the $data['data']['status'] is 'successful'
    // Confirm that the currency on your db transaction is equal to the returned currency
    // Confirm that the db transaction amount is equal to the returned amount
    // Update the db transaction record (including parameters that didn't exist before the transaction is completed. for audit purpose)
    // Give value for the transaction
    // Update the transaction to note that you have given value for the transaction
    // You can also redirect to your success page from here
}
```

## Parameters

| Parameter          | Required | Description                                                                                                                                                                              |
| ------------------ | -------- | ---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- |
| amount             | True     | This is the amount to be charged. Expected value is ZMW                                                                                                                                  |
| email              | True     | This is the email address of the customer.                                                                                                                                               |
| tx_ref             | True     | This is a unique reference, unique to the particular transaction being carried out. It is generated when it is not provided by the merchant for every transaction.                       |
| fullname           | False    | This is the customers full name. It should include first and last name of the customer.                                                                                                  |
| phone_number       | True     | This is the phone number linked to the customer's mobile money account.                                                                                                                  |
| client_ip          | False    | IP - Internet Protocol. This represents the current IP address of the customer carrying out the transaction                                                                              |
| device_fingerprint | False    | This is the fingerprint for the device being used. It can be generated using a library on whatever platform is being used.                                                               |
| meta               | False    | This is used to include additional payment information`                                                                                                                                  |
| subaccounts        | False    | This is an array of objects containing the subaccount IDs to split the payment into. Check our Split Payment page for more info. eg `[ ["id" => "RS_D87A9EE339AE28BFA2AE86041C6DE70E"]]` |
| meta               | False    | This is an object that helps you include additional payment information to your request e.g ['consumer_id'=>23, 'consumer_mac'=>'92a3-912ba-1192a']                                      |
