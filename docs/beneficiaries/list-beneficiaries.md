# List all beneficiaries

> Fetch all beneficiaries on your account.

```php
<?php

    $data = [
        'page' => 1
    ];

    // $data is optional
    $beneficiaries = Flutterwave::beneficiaries()->fetchAll($data);

    dd($beneficiaries);
```


## Parameters

| Parameter | Required | Description                                                                                                                                                                                                 |
| --------- | -------- | ----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- |
| page | False     | This allows you fetch from a specific page e.g. setting page to 1 fetches the first page. |
| status     | False     | This allows you fetch only transfers with a specific status e.g. fetch all successful transactions. Possible values are failed, successful      |
