# Transfer Fees

Get applicable transfer fee.


```php
<?php

$data = [
    'amount' => '500',
    'currency' => 'USD'
];

$transfer = Flutterwave::transfers()->fees($data);

dd($transfer);
```


## Parameters

| Parameter | Required | Description                                                                                                                                                                                                 |
| --------- | -------- | ----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- |
| amount | True     | This is the transfer amount to be fetched. |
| currency     | False     | Pass this to specify the exact currency you want to fetch the fees for. Example: USD, NGN, etc       |
| type     | False     | This is the type of transfer you want to get the fee for. Usual values are `mobilemoney` or `account`       |
