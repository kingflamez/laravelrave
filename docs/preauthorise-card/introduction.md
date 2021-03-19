# Preauthorise Card Method

# 1. Rave::preAuthouriseCard($arrdata)

### Route
```php
Route::post('/preAuthCard/preauthourisecard', 'RaveController@preAuthouriseCard')->name('preauthourisedcard');
```

### Controller
```php
  public function preAuthouriseCard() {
// {
//   "PBFPubKey": "FLWPUBK-7adb6177bd71dd43c2efa3f1229e3b7f-X",
//   "client" : ""
// }
  $data = Rave::preAuthouriseCard($arrdata);
  dd($data);
  }
```

```php
NB: Sample parameters to Encrypt
{
  "PBFPubKey": "FLWPUBK-7adb6177bd71dd43c2efa3f1229e3b7f-X",
  "cardno": "5438898014560229",
  "charge_type": "preauth",
  "cvv": "812",
  "expirymonth": "08",
  "expiryyear": "20",
  "currency": "NGN",
  "country": "NG",
  "amount": "100",
  "email": "user@example.com",
  "phonenumber": "08056552980",
  "firstname": "user",
  "lastname": "example",
  "IP": "40.198.14",
  "txRef": "MC-" + Date.now(),
  "redirect_url": "https://rave-web.herokuapp.com/receivepayment",
  "device_fingerprint": "69e6b7f0b72037aa8428b70fbe03986c"
}
```

# 2. Rave::Capture($arrdata)

### Route
```php
Route::post('/preAuthCard/capture', 'RaveController@Capture')->name('capture');
```

### Controller
```php
public function Capture($arrdata) {
//     {
//   "SECKEY": "FLWSECK-e6db11d1f8a6208de8cb2f94e293450e-X",
//   "flwRef": "FLW-PREAUTH-M03K-86617e45f53f0819aba82cd8dceefe6a",
//   "amount": 2000
// }
  $data = Rave::Capture($arrdata);
  dd($data);
}
```

# 3. Rave::refundPreAuthCard($arrdata)

### Route
```php
Route::post('/preAuthCard/refundpreauthcard', 'RaveController@refundPreAuthCard')->name('refundpreauthcard');
```

### Controller
```php
  public function refundPreAuthouriseCard($arrdata) {
// {
//     "ref": "", This is the flwRef returned in the capture response.
//     "action" This is the action to be taken i.e. refund or void
// }
  $data = Rave::refundPreAuthouriseCard($arrdata);
  dd($data);
  }
  ```