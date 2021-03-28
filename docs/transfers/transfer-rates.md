# Check transfer rates

This endpoint helps you understand transfer rates when making international transfers

```php
<?php
$data = [
    'amount' => 1000,
    'destination_currency' => 'USD',
    'source_currency' => 'NGN'
];

$transfer = Flutterwave::transfers()->getTransferRate($data);

dd($transfer);
```

## Parameters

| Parameter | Required | Description                                                                                                                                                                                                 |
| --------- | -------- | ----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- |
| amount | True     | This is the amount to transfer to the recipient. |
| destination_currency     | True     | This is the wallet / currency you are making a transfer to.      |
| source_currency     | True     | This is the wallet / currency to be debited for the transfer.      |


::: warning This endpoint helps you calculate rates when making international transfers 
John owes Paul $1000.
John only has money in his Flutterwave NGN wallet.
So he wants to send $1000 to Paul's but he wants Flutterwave to debit his NGN wallet for it.
But first, John would like to know exactly how much in Naira is $1000.
So he makes a request to this endpoint, with this object :

```php
$data = [
    'amount' => 1000,
    'destination_currency' => 'USD',
    'source_currency' => 'NGN'
];
```
Which basically means "How much will it cost me to send $1000 from my NGN wallet to Paul?" Here's the response:

```php
[
    "status" => "success",
    "message" => "Transfer amount fetched",
    "data" => [
        "rate" => 415.264373,
        "source" => [
            "currency" => "NGN",
            "amount" => 415264.373
        ],
        "destination" => [
            "currency" => "USD",
            "amount" => 1000
        ]
    ]
]
```

The response tells John that "If you are sending $1000 from your NGN wallet to Paul, it will cost you NGN415,264.373"

This is one of the use cases for this endpoint. To help you understand the transfer rates when you are sending money across different currencies.

DISCLAIMER: This endpoint should NOT be used to determine FX rates from the central bank. Refer [here](https://www.abokifx.com/) for rates.
:::
