# How Transfers work

## Introduction
When a transfer is initiated, it comes with a status NEW this means the transfer has been queued for processing, and you would need to use the reference you passed to call the Fetch a Transfer endpoint to retrieve the updated status of the transfer.

## What happens when a transfer is completed ?
When a transfer is completed we would push a notification to you via your [Webhook](https://developer.flutterwave.com/reference#webhooks) You can use this to confirm the status of the transfer.

If a transfer is already being processed and it fails during processing, we would also push a hook notification to you on your specified hook URL.

```php
 // {
 //   "event.type": "Transfer",
 //   "transfer": {
 //   "id": 570,
 //   "account_number": "0690000040",
 //   "bank_code": "044",
 //   "fullname": "Alexis Sanchez",
 //   "date_created": "2018-06-11T14:07:49.000Z",
 //   "currency": "NGN",
 //   "amount": 9000,
 //   "fee": 45,
 //   "status": "SUCCESSFUL",
 //   "reference": "rave-transfer-152812343460966",
 //   "narration": "New transfer",
 //   "approver": null,
 //   "complete_message": "Approved Or Completed Successfully",
 //   "requires_approval": 0,
 //   "is_approved": 1,
 //   "bank_name": "ACCESS BANK NIGERIA"
 // }
 // }
 ```

 ### Topping up your balance on test environment

 > To use the Initiate Transfer ] endpoint you need to top up your balance, navigate to transfers and click on the Top up balance notification, use this access bank test account to fund your balance 0690000031.

 ### Test accounts to use as Destination accounts

 > Kindly make use of test account numbers between the range of 0690000031 - 0690000041.

You can use the accounts to test funding and also test transfers into an account.

### International Transfers (US, EU, Asia)

> This transfer method is only available in beta for selected merchants, once it becomes available to all merchants it would be announced. This means that transfers you test in the sandbox and live environments wouldn't be completed they would only be left in pending.

## Available countries you can transfer to

| Country | Currency |
| ------------- |:-------------:|
| NG (Nigeria) | NGN |
| GH (Ghana) | GHS |
| KE (Kenya) | KES |
| UG (Ugandan) | UGX |
| US (United States) | 	USD |
| OT (Other countries) | GBP, EUR, AUD etc.|

## Transfer method

# 1. Rave::initiateTransfer()

## Body Parameters

| name        | required           | description  |
| ------------- |:-------------:| -----:|
| account_bank | false | This is the recipient bank code, you can get a list of bank codes by calling the List of Banks for Transfer ] |
| account_number | false | This is the recipient account number |
| recipient_id | false | This allows you transfer to an existing beneficiary, you can pass this in place of account_bank & account_number. The recipient_id can be gotten from the Fetch Recipients ] endpoint. |
| amount | true | This is the amount to transfer to the recipient. Amount is in Naira | 
| narration | false | This is the narration for the transfer e.g. payments for x services | 
| currency | true | This can be NGN |
| seckey | true | This is your merchant secret key, see how to get your API Request ] | 
| reference | true | This is a merchant's unique reference for the transfer, it can be used in querying the status of the transfer | 

## MPesa Transfer

For Mpesa transfers always pass the account_bank value as MPS, the account_number value is the recipients Mpesa number, it should always come with the prefix 254

### Sample Mpesa Callback
```php
{
  "event.type": "Transfer",
  "transfer": {
    "id": 3455,
    "account_number": "25472509382427",
    "bank_code": "MPS",
    "fullname": "FA",
    "date_created": "2018-10-03T14:20:25.000Z",
    "currency": "KES",
    "debit_currency": null,
    "amount": 49.99,
    "fee": 250,
    "status": "SUCCESSFUL",
    "reference": "rave-transfer-15028609",
    "meta": null,
    "narration": "Rave Mpesa transfer",
    "approver": null,
    "complete_message": "Approved Or Completed Successfully",
    "requires_approval": 0,
    "is_approved": 1,
    "bank_name": "FA-BANK"
  }
}
```

## Testing Mpesa Successfully
To run a successful Mpesa disbursement test you would need to be on the [live](https://rave.flutterwave.com/) environment.

Your Mpesa wallet balance also needs to be funded, see how to fund your wallet balance [here](https://support.flutterwave.com/article/112-funding-your-rave-wallet).

### Route

```php
Route::get('/transfer/initiate', 'RaveController@initiateTransfer')->name('initiatetransfer);
```

### Controller

```php
public function initiateTransfer($arrdata) 
  {
//    {
//   "account_bank": "044",
//   "account_number": "0690000044",
//   "amount": 500,
//   "seckey": "FLWSECK-e6db11d1f8a6208de8cb2f94e293450e-X",
//   "narration": "New transfer",
//   "currency": "NGN",
//   "reference": "mk-902837-jk"
// }
        $data = Rave::initiateTransfer($arrdata);
        dd($data);
  }
  ```

# 2. Rave::bulkTransfer($arrdata)

### Route
```php
Route::get('/tranfer/bulk', 'RaveController@bulkTransfer')->name('bulktransfer);
```

### Controller
```php
  public function bulkTransfer($arrdata)
  {
//     {
//   "seckey":"FLWSECK-0b1d6669cf375a6208db541a1d59adbb-X",
//   "title":"May Staff Salary",
//   "bulk_data":[
//   	{
//         "Bank":"044",
//         "Account Number": "0690000032",
//         "Amount":500,
//         "Currency":"NGN",
//         "Narration":"Bulk transfer 1",
//         "reference": "mk-82973029"
//     },
//     {
//         "Bank":"044",
//         "Account Number": "0690000034",
//         "Amount":500,
//         "Currency":"NGN",
//         "Narration":"Bulk transfer 2",
//         "reference": "mk-283874750"
//     }
//   ]
// }
        $data = Rave::bulkTransfer($arrdata);
        dd($data);     
  }
  ```

# 3. Rave::fetchTransfer($id, $q, $reference)

### Route
```php
Route::get('/transfer/fetchtransfer', 'RaveController@fetchTransfer')->name('fetchtransfer);
```

### Controller
```php
public function fetchTransfer($id, $q, $reference) {
    $data = Rave::fetchTransfer($id, $q, $reference);
    dd($data);
  }
  ```

# 4. Rave::listTransfers()

### Route
```php
Route::get('/transfer/listtransfer', 'RaveController@listTransfers')->name('listtransfers');
```

### Controller 
```php
public function listTransfers() {
    $data = Rave::listTransfers();
    dd($data)
}
```

# 5. Rave::retrieveStatusofBulkTransfers($patch_id)

### Route
```php
Route::get('/transfer/statusofbulktransfer', 'RaveController@retrieveStatusofBulkTransfers')->name('retrievestatusofbulktransfers');
```

### Controller
```php
public function retrieveStatusofBulkTransfers($patch_id) {
    $data = Rave::retrieveStatusofBulkTransfers($patch_id);
    dd($data);
}
```

# 6. Rave::getApplicableTransferFee($currency)

### Route
```php 
Route::get('/transfer/getapplicabletransferfee', 'RaveController@getApplicableTransferFee')->name('getapplicabletransferfee');
```
### Controller 
```php 
public function getApplicableTransferFee($currency) {
    $data = Rave::getApplicableTransferFee($currency);
    dd($data);
}
```

# 7. Rave::getTransferBalance($currency)

### Route
```php
Route::post('/paysetup/gettransferbalance', 'RaveController@getTransferBalance')->name('gettransferbalance');
```
### Controller
```php
  public function getTransferBalance($currency){
//       {
// 	"currency": "NGN",
// 	"seckey": "FLWSECK-e6db11d1f8a6208de8cb2f94e293450e-X"
// }
    $data = Rave::getTransferBalance($currency);
    dd($data);
  }
  ```

# 8. Rave::accountVerification($arrdata) 
### Route
```php
Route::post('/transfer/accountVerification', 'RaveController@accountVerification')->name('accountverification');
```
### Controller
```php
public function accountVerification($arrdata) {


// {
//   "recipientaccount": "0690000034",
//   "destbankcode": "044",
//   "PBFPubKey": "FLWPUBK-4e9d4e37974a61157ce8ca4f43c84936-X"
// } 
    $data = Rave::accountVerification($arrdata);
    dd($data); 
}
```

