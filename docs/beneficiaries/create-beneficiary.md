# Create a beneficiary

> Create a transfer beneficiary

```php
<?php
$data = [
    'account_number' => '0690000034',
    'account_bank' => '044'
];

$beneficiary = Flutterwave::beneficiaries()->create($data);

dd($beneficiary);
```

## Parameters

| Parameter | Required | Description                                                                                                                                                                                                 |
| --------- | -------- | ----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- |
| account_number | True     | The beneficiary's bank account number. When testing on staging, you can find a list of all the available test bank accounts [here](https://developer.flutterwave.com/docs/test-bank-accounts). |
| account_bank     | True     | This is the beneficiary’s bank code, you can use the [List of Banks](/banks/list-banks) to retrieve a bank code.      |
