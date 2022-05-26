# Fetch a beneficiary

> Get a single transfer beneficiary details.

```php
<?php

    $beneficiaryId = 12596;
    
    $beneficiary = Flutterwave::beneficiaries()->fetch($beneficiaryId);

    dd($beneficiary);
```
