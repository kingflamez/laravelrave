# Get a transfer

Fetch a single transfer on your account

```php
<?php
    $transferId = 187092;

    $transfer = Flutterwave::transfers()->fetch($transferId);

    dd($transfer);
```
