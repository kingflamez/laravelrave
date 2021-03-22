# Charge via NGN bank transfer (One time Payment)

This allows your customer to pay via a NIP (NIBBS Instant Payment) transfer.

```php
<?php

$reference = Flutterwave::generateReference();

$data = [
    'amount' => 100,
    'email' => 'wole@email.co',
    'tx_ref' => $reference,
    'narration' => 'Dstv - Bill Payment'
];

$bankDetails = Flutterwave::payments()->nigeriaBankTransfer($data);

if ($bankDetails['status'] === 'success') {
    # show bank details to user
    
}
```

## Parameters

| Parameter          | Required | Description                                                                                                                                                        |
| ------------------ | -------- | ------------------------------------------------------------------------------------------------------------------------------------------------------------------ |
| amount             | True     | This is the amount to be charged. Expected value is NGN                                                                                                            |
| email              | True     | This is the email address of the customer.                                                                                                                         |
| tx_ref             | True     | This is a unique reference, unique to the particular transaction being carried out. It is generated when it is not provided by the merchant for every transaction. |
| fullname           | False    | This is the customers full name. It should include first and last name of the customer.                                                                            |
| phone_number       | False    | This is the phone number linked to the customer.                                                                                            |
| client_ip          | False    | IP - Internet Protocol. This represents the current IP address of the customer carrying out the transaction                                                        |
| device_fingerprint | False    | This is the fingerprint for the device being used. It can be generated using a library on whatever platform is being used.                                         |
| meta               | False    | This is used to include additional payment information`                                                                                                            |
| subaccounts        | False    | This is an array of objects containing the subaccount IDs to split the payment into. Check our Split Payment page for more info. eg ```[    ["id" => "RS_D87A9EE339AE28BFA2AE86041C6DE70E"]]```|
|meta | False | This is an object that helps you include additional payment information to your request e.g ['consumer_id'=>23, 'consumer_mac'=>'92a3-912ba-1192a']|
