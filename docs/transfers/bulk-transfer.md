# Bulk Transfer

This document shows you how to initiate a bulk transfer.

```php
<?php

$data = [
    "title" => "akhlm pstmn blktrnfr xx03",
    "bulk_data" => [
        [
            "bank_code" => "044",
            "account_number" => "0690000032",
            "amount" => 45000,
            "currency" => "NGN",
            "narration" => "akhlm blktrnsfr",
            "reference" => "akhlm-blktrnsfr-xx03"
        ],
        [
            "bank_code" => "044",
            "account_number" => "0690000034",
            "amount" => 5000,
            "currency" => "NGN",
            "narration" => "akhlm blktrnsfr",
            "reference" => "akhlm-blktrnsfr-xy03"
        ]
    ]
];

$transfer = Flutterwave::transfers()->bulk($data);

dd($transfer);
```

Please setup a [webhook](/verification/webhook) to get status on your transfers. When you initiate a transfer you get a queuing status, once the transfer is successful or failed, we hit your webhook to alert you, you can update the status of the transfer from there

## Parameters

| Parameter | Required | Description                                                                                                                                                                                                 |
| --------- | -------- | ----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- |
| bulk_data | True     | An array of objects containing the transfer charge data. This array contains the same payload you would passed to create [a single transfer](/transfers/initiate-transfers) with multiple different values. |
| title     | False     | Title of the bulk transfer                                                                                                                                                                                  |
