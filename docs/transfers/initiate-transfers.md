# Initiate Transfer

This will show you how to initiate a transfer.

```php
<?php
$reference = Flutterwave::generateReference();

$data = [
    'account_bank'=> '044',
    'account_number'=> '0690000040',
    'amount' => 5500,
    'narration' => 'Payment for goods purchased',
    'currency' => 'NGN',
    'reference' => $reference
];

$transfer = Flutterwave::transfers()->initiate($data);

dd($transfer);
```

Please setup a [webhook](/verification/webhook) to get status on your transfers. When you initiate a transfer you get a queuing status, once the transfer is successful or failed, we hit your webhook to alert you, you can update the status of the transfer from there.

> You can also setup a cron job that checks all pending transfers status in your db and updates them accordingly

```php

$transferId = 187092; // get transfer ID from your DB
$transfer = Flutterwave::transfers()->fetch($transferId);

if($transfer['data']['status'] === 'SUCCESSFUL') {
    // update transfer status to successful in your db
} else if ($transfer['data']['status'] === 'FAILED') {
    // update transfer status to failed in your db
    // revert customer balance back
} else if ($transfer['data']['status'] === 'PENDING') {
    // update transfer status to pending in your db
}

```

## Parameters

| Parameter               | Required | Description                                                                                                                                                                                                                                         |
| ----------------------- | -------- | --------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- |
| account_bank            | True     | This is the recipient bank code. You can see a list of all the available banks and their codes [here](/banks/list-banks).                                                                                                                           |
| account_number          | True     | This is the recipient account number. When testing on staging, you can find a list of test bank accounts [here](https://developer.flutterwave.com/docs/test-bank-accounts)..                                                                        |
| amount                  | True     | This is the amount to transfer to the recipient.                                                                                                                                                                                                    |
| currency                | True     | This can be `NGN`, `GHS`, `KES`, `UGX`, `TZS`, `USD` or `ZAR`.                                                                                                                                                                                      |
| narration               | False    | This is the narration for the transfer e.g. payments for x services provided                                                                                                                                                                        |
| beneficiary_name        | False    | This is the name of the beneficiary..                                                                                                                                                                                                               |
| destination_branch_code | False    | This code uniquely identifies bank branches for disbursements into Ghana, Uganda and Tanzania. It is returned in the call to fetch bank branches here: [Click Here](/banks/bank-branches). It is only REQUIRED for GHS, UGX and TZS bank transfers. |
| beneficiary             | False    | This is the beneficiary's id. It allows you to initiate a transfer to an existing beneficiary. You can pass this in place of `account_bank` & `account_number`. It is returned in the call to fetch a beneficiary as `data['id']`. [Click here to create a beneficiary](/beneficiaries/create-beneficiary)                   |
| reference               | False    | This is a merchant's unique reference for the transfer, it can be used to query for the status of the transfer.                                                                                                                                     |
| debit_currency          | False    | You can pass this when you want to debit a currency balance and send money in another currency.                                                                                                                                                     |
| meta                    | False    | This is an object that helps you include additional payment information to your request e.g ['consumer_id'=>23, 'consumer_mac'=>'92a3-912ba-1192a']                                                                                                 |



## Transfer to Nigerian bank accounts

```php

<?php

$reference = Flutterwave::generateReference();

$data = [
    "account_bank"=>"044",
    "account_number"=>"0690000040",
    "amount"=>5500,
    "narration"=>"Akhlm Pstmn Trnsfr xx007",
    "currency"=>"NGN",
    "debit_currency"=>"NGN"
    'reference' => $reference
];

$transfer = Flutterwave::transfers()->initiate($data);

dd($transfer);

// {
//   "status": "success",
//   "message": "Transfer Queued Successfully",
//   "data": {
//     "id": 26251,
//     "account_number": "0690000040",
//     "bank_code": "044",
//     "full_name": "Ekene Eze",
//     "created_at": "2020-01-20T16:09:34.000Z",
//     "currency": "NGN",
//     "debit_currency": "NGN",
//     "amount": 5500,
//     "fee": 45,
//     "status": "NEW",
//     "reference": "akhlm-pstmnpyt-rfxx007_PMCKDU_1",
//     "meta": null,
//     "narration": "Akhlm Pstmn Trnsfr xx007",
//     "complete_message": "",
//     "requires_approval": 0,
//     "is_approved": 1,
//     "bank_name": "ACCESS BANK NIGERIA"
//   }
// }
```

## International Transfers (Other countries)

```php

<?php

$data = [
    "amount" => 50,
    "narration" => "Test Int'l bank transfers",
    "currency" => "USD",
    "reference" => "new-intl-test-transfer",
    "beneficiary_name" => "Mark Cuban ",
    "meta" => [
        [
            "AccountNumber" => "091820932BH",
            "RoutingNumber" => "0000002993",
            "SwiftCode" => "ABJG190",
            "BankName" => "BARCLAYS BANK (U) LIMITED",
            "BeneficiaryName" => "Mark Cuban",
            "BeneficiaryAddress" => "HANNINGTON ROAD, KAMPALA UGANDA",
            "BeneficiaryCountry" => "OT"
        ]
    ]
];

$transfer = Flutterwave::transfers()->initiate($data);

dd($transfer);


```

## International Transfers (EUR & GBP)

```php

<?php

$data = [
    "amount" => 50,
    "narration" => "Test EU Int'l bank transfers",
    "currency" => "EUR",
    "reference" => "new-intl-eu-test-transfer",
    "beneficiary_name" => "John Twain",
    "meta" => [
        [
            "AccountNumber" => "DA091983888373BGH",
            "RoutingNumber" => "BECFDE7HKKX",
            "SwiftCode" => "BECFDE7HKKX",
            "BankName" => "LLOYDS BANK",
            "BeneficiaryName" => "John Twain",
            "BeneficiaryCountry" => "DE",
            "PostalCode" => "80489",
            "StreetNumber" => "31",
            "StreetName" => "Handelsbank Elsenheimer Str.",
            "City" => "München"
        ]
    ]
];

$transfer = Flutterwave::transfers()->initiate($data);

dd($transfer);
```

## Transfer to Ghana bank account

```php

<?php

$data = [
    "account_bank" => "GH280100",
    "account_number" => "0031625807099",
    "amount" => 50,
    "narration" => "Test GHS bank transfers",
    "currency" => "GHS",
    "reference" => "new-GHS-test-transfer1",
    "callback_url" => "https://webhook.site/b3e505b0-fe02-430e-a538-22bbbce8ce0d",
    "destination_branch_code" => "GH280103",
    "beneficiary_name" => "Kwame Adew"
];

$transfer = Flutterwave::transfers()->initiate($data);

dd($transfer);
```

## International transfers (US)

```php

<?php

$data = [
    "amount" => 50,
    "narration" => "Test Int'l bank transfers",
    "currency" => "USD",
    "reference" => "new-intl-test-transfer1",
    "beneficiary_name" => "Mark Cuban ",
    "meta" => [
        [
            "AccountNumber" => "09182972BH",
            "RoutingNumber" => "0000000002993",
            "SwiftCode" => "ABJG190",
            "BankName" => "BANK OF AMERICA, N.A., SAN FRANCISCO, CA",
            "BeneficiaryName" => "Mark Cuban",
            "BeneficiaryAddress" => "San Francisco, 4 Newton",
            "BeneficiaryCountry" => "US"
        ]
  ]
];

$transfer = Flutterwave::transfers()->initiate($data);

dd($transfer);
```

## Mpesa Mobile Money Transfer

```php

<?php

$data = [
  "account_bank" => "MPS",
  "account_number" => "2540782773934",
  "amount" => 50,
  "narration" => "New transfer",
  "currency" => "KES",
  "reference" => "mk-902837-jk",
  "beneficiary_name" => "Kwame Adew"
];

$transfer = Flutterwave::transfers()->initiate($data);

dd($transfer);
```

## Ghana Mobile Money Transfer

```php

<?php

$data = [
  "account_bank" => "MTN",
  "account_number" => "233542773934",
  "amount" => 50,
  "narration" => "New GHS momo transfer",
  "currency" => "GHS",
  "reference" => "mk-902837-jk",
  "beneficiary_name" => "Kwame Adew"
];

$transfer = Flutterwave::transfers()->initiate($data);

dd($transfer);
```

## Uganda Mobile Money Transfer

```php

<?php

$data = [
  "account_bank" => "MPS",
  "account_number" => "233542773934",
  "amount" => 50,
  "narration" => "New UGX momo transfer",
  "currency" => "UGX",
  "reference" => "mk-902837-jk",
  "beneficiary_name" => "Kwame Adew"
];

$transfer = Flutterwave::transfers()->initiate($data);

dd($transfer);
```

## Rwanda Mobile Money Transfer

```php

<?php

$data = [
  "account_bank" => "MPS",
  "account_number" => "233542773934",
  "amount" => 50,
  "narration" => "New RWF momo transfer",
  "currency" => "RWF",
  "reference" => "mk-902837-jk",
  "beneficiary_name" => "Kwame Adew"
];

$transfer = Flutterwave::transfers()->initiate($data);

dd($transfer);
```

## Francophone Mobile Money Transfer

```php

<?php

$data = [
  "account_bank" => "FMM",
  "account_number" => "233542773934",
  "amount" => 50,
  "narration" => "New franco momo transfer",
  "currency" => "XAF",
  "reference" => "mk-902837-jk",
  "beneficiary_name" => "Kwame Adew"
];

$transfer = Flutterwave::transfers()->initiate($data);

dd($transfer);
```

## Transfer to a FLW account

```php

<?php

$data = [
  "account_bank" => "flutterwave",
  "account_number" => "00118468",
  "amount" => 5500,
  "narration" => "payment for x service provided",
  "currency" => "NGN",
  "reference" => "mk-902837-jk",
  "debit_currency" => "NGN"
];

$transfer = Flutterwave::transfers()->initiate($data);

dd($transfer);
```

## Transfer USD to Nigerian DOM Accounts

```php

<?php

$data = [
  "account_number" =>  "0690000036",
  "account_bank" =>  "044",
  "narration" =>  "Nada",
  "amount" =>  50,
  "reference" =>  "khlm-dom-065",
  "currency" =>  "USD",
  "debit_currency" =>  "USD",
  "beneficiary_name" =>  "Michale Lester",
  "meta" => [
    [
      "first_name" => "Michale",
      "last_name" => "Lester",
      "email" => "dump@kizito",
      "beneficiary_country" => "NG",
      "mobile_number" => "+2348131133933",
      "sender" => "Statik Selektah",
      "merchant_name" => "Spotify"
    ]
  ]
];

$transfer = Flutterwave::transfers()->initiate($data);

dd($transfer);
```

## Transfer to SA Bank Account

```php

<?php

$data = [
    "account_bank" => "FNB",
    "account_number" => "0031625807099",
    "amount" => 500,
    "narration" => "Withdraw Fiat",
    "currency" => "ZAR",
    "reference" => "496_PMCKDU_1",
    "debit_currency":"USD",
    "callback_url" => "http://localhost:3000/deposits/banks/flutterwave_callback",
    "meta" => [
        [
            "first_name" => "Michale",
            "last_name" => "Lester",
            "email" => "dump@kizito",
            "mobile_number" => "+2348131133933"
        ]
    ]
];

$transfer = Flutterwave::transfers()->initiate($data);

dd($transfer);
```

## Transfer to Barter account

```php

<?php

$data = [
    "account_number" => "+2348xxxxxxxx8",
    "account_bank" => "barter",
    "narration" => "Test",
    "amount" => 20,
    "reference" => "barter-transfer-2",
    "currency" => "NGN",
    "beneficiary_name" => "Ifunanya Ikemma"
];

$transfer = Flutterwave::transfers()->initiate($data);

dd($transfer);
```
