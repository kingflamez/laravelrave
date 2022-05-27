# Fetch a subaccount

> Get a single collection subaccount details.

```php
<?php

    $subaccountId = 12596;
    
    $subaccount = Flutterwave::subaccounts()->fetch($subaccountId);

    dd($subaccount);
```
