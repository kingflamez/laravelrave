# Delete a beneficiary

> Delete a transfer beneficiary.

```php
<?php

    $beneficiaryId = 12596;
    
    $beneficiary = Flutterwave::beneficiaries()->destroy($beneficiaryId);

    dd($beneficiary);
```
