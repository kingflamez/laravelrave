# List all subaccounts

> Fetch all subaccounts on your account.

```php
<?php

    $data = [
        'page' => 1
    ];

    // $data is optional
    $subaccounts = Flutterwave::subaccounts()->fetchAll($data);

    dd($subaccounts);
```


## Parameters

| Parameter | Required | Description                                                                                                                                                                                                 |
| --------- | -------- | ----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- |
| page | False     | This allows you fetch from a specific page e.g. setting page to 1 fetches the first page. |
| account_bank     | False     | This is the sub-accounts bank ISO code      |
| account_number     | False     | This is the account number associated with the subaccount you want to fetch      |
| bank_name     | False     | This is the name of the bank associated with the ISO code provided in account_bankfield      |
