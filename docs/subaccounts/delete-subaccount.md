# Delete a subaccount

> Delete a collection subaccount.

```php
<?php

    $subaccountId = 12596;
    
    $subaccount = Flutterwave::subaccounts()->destroy($subaccountId);

    dd($subaccount);
```
