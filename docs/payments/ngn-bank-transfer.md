# Charge via NGN bank transfer (One time Payment)

This allows your customer to pay via a NIP (NIBBS Instant Payment) transfer.

```php
<?php

$reference = Flutterwave::generateReference();

$data = [
    'amount' => 100,
    'email' => 'wole@email.co',
    'tx_ref' => $reference,
];

$bankDetails = Flutterwave::chargeNGNTransfer($data);

if ($bankDetails) {
    # code...
}
```

## Parameters

| Parameter          | Required | Description                                                                                                                                                        |
| ------------------ | -------- | ------------------------------------------------------------------------------------------------------------------------------------------------------------------ |
| amount             | True     | This is the amount to be charged. Expected value is ZAR                                                                                                            |
| email              | True     | This is the email address of the customer.                                                                                                                         |
| tx_ref             | True     | This is a unique reference, unique to the particular transaction being carried out. It is generated when it is not provided by the merchant for every transaction. |
| fullname           | False    | This is the customers full name. It should include first and last name of the customer.                                                                            |
| phone_number       | False    | This is the phone number linked to the customer's mobile money account.                                                                                            |
| client_ip          | False    | IP - Internet Protocol. This represents the current IP address of the customer carrying out the transaction                                                        |
| device_fingerprint | False    | This is the fingerprint for the device being used. It can be generated using a library on whatever platform is being used.                                         |
| meta               | False    | This is used to include additional payment information`                                                                                                            |
| subaccounts        | False    | This is an array of objects containing the subaccount IDs to split the payment into. Check our Split Payment page for more info. eg ```[    ["id" => "RS_D87A9EE339AE28BFA2AE86041C6DE70E"]]```|
|meta | False | This is an object that helps you include additional payment information to your request e.g ['consumer_id'=>23, 'consumer_mac'=>'92a3-912ba-1192a']|
|duration | False | This is represented in days e.g. passing 2 means 2 days. It is the expiry date for the account number. Setting to 2 would mean you want the account number to exist for 2 days before expiring.|
|frequency | False | This is the number of times a generated account number can receive payments. Represented as an Integer e.g. 'frequency': 10 means the account can receive payments up to 10 times before expiring.|
