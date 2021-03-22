# Charge via ACH payment

This helps you to accept South African ACH charges from your customers

```php
<?php

$reference = Flutterwave::generateReference();

$data = [
    'amount' => 100,
    'email' => 'wole@email.co',
    'currency' => 'ZAR',
    'tx_ref' => $reference,
];

$response = Flutterwave::payments()->ach($data);

if ($response['status'] === 'success') {
    # code...
}
```

## Parameters

| Parameter          | Required | Description                                                                                                                                                        |
| ------------------ | -------- | ------------------------------------------------------------------------------------------------------------------------------------------------------------------ |
| amount             | True     | This is the amount to be charged. Expected value is ZAR                                                                                                            |
| currency           | True     | ZAR or USD                                                                                                                                                         |
| email              | True     | This is the email address of the customer.                                                                                                                         |
| tx_ref             | True     | This is a unique reference, unique to the particular transaction being carried out. It is generated when it is not provided by the merchant for every transaction. |
| fullname           | False    | This is the customers full name. It should include first and last name of the customer.                                                                            |
| phone_number       | False    | This is the phone number linked to the customer's mobile money account.                                                                                            |
| client_ip          | False    | IP - Internet Protocol. This represents the current IP address of the customer carrying out the transaction                                                        |
| device_fingerprint | False    | This is the fingerprint for the device being used. It can be generated using a library on whatever platform is being used.                                         |
| meta               | False    | This is used to include additional payment information`                                                                                                            |
| subaccounts        | False    | This is an array of objects containing the subaccount IDs to split the payment into. Check our Split Payment page for more info                                    |
| meta               | False    | This is an object that helps you include additional payment information to your request e.g ['consumer_id'=>23, 'consumer_mac'=>'92a3-912ba-1192a']                |
| redirect_url       | False    | This is a url you provide, we redirect to it after the customer completes payment and append the response to it as query parameters.                               |
| country            | False    | Pass your country as US for US ACH payments and ZA for SA ACH payments.                                                                                            |
