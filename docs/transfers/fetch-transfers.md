# Get all transfers

Fetch all transfers on your account.

```php
<?php

    $data = [
        'page' => 1,
        'status' => 'SUCCESSFUL'
    ];

    // $data is optional
    $transfers = Flutterwave::transfers()->fetchAll($data);

    dd($transfers);
```


## Parameters

| Parameter | Required | Description                                                                                                                                                                                                 |
| --------- | -------- | ----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- |
| page | False     | This allows you fetch from a specific page e.g. setting page to 1 fetches the first page. |
| status     | False     | This allows you fetch only transfers with a specific status e.g. fetch all successful transactions. Possible values are failed, successful      |
