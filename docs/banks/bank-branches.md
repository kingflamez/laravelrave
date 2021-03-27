# Get Bank Branches

This helps you get the bank branches, pass the id of the bank. Click [here](/banks/list-banks) to get bank ids

## Sample

```php
<?php

$bankId = 191;
$branches = Flutterwave::banks()->branches($bankId);

dd($branches);

```
