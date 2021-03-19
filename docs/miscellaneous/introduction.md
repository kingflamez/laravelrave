# Miscellaneous method

# 1. Rave::getFees($arrdata)

### Route
```php
Route::post('/miscellaneous/getfees', 'RaveController@getFees')->name('getfees');
```

### Controller
```php
public function getFees($arrdata) {
    $data = Rave::getFees($arrdata);
    dd($data); 
}
```

# 2. Rave::listofDirectBankCharge()

### Route
```php
Route::get('/miscellaneous/listofdirectbankcharge', 'RaveController@listofDirectBankCharge')->name('listofdirectbankcharge');
```
### Controller
```php
public function listofDirectBankCharge() {
    $data = Rave::listofDirectBankCharge();
    dd($data);
  }
  ```

# 3. Rave::listTransactions()
### Route
```php
Route::post('/miscellaneous/listtransactions', 'RaveController@listTransactions')->name('listtransactions');
```

### Controller
```php
public function listTransactions($arrdata) {
//     {
//   "seckey": "Merchant secret key",
//   "from": "2018-01-01",
//   "to": "2018-03-30",
//   "currency": "NGN",
//   "status": "successful"
// }
$data = rave::listTransactions($arrdata);
dd($data);
}
```

# 4. Rave::listofBankForTransfer($country)
### Route
```php
Route::get('/miscellaneous/listofbankfortransfer', 'RaveController@listofBankForTransfer')->name('listofbankfortransfer');
```

### Controller
```php
  public function listofBankforTransfer($country) {

   $data = Rave::listofBankforTransfer($country);
   dd($data);
  }
  ```