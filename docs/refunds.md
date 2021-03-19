# Refunds

Rave allows you initiate refunds for Successful transactions, there are two ways this can be acheived using the rave dashboard by:

Navigating to Transaction History > Click on the refund Action button.
We also allow you refund via an API, so you can send a request to refund a transaction or extend the ability to refund outside the rave dashboard

> On rave, only successful transactions can be refunded.

## Form Values

| name        | required           | description  |
| ------------- |:-------------:| -----:|
| txref      |  true | This is the transaction reference of the successful transaction.

## Sample implementation

```html
<form method="POST" action="{{ route('refund') }}" id="paymentForm">
    {{ csrf_field() }}
    <input type="text" name="txref" placeholder="Transaction Reference" />
    <input type="submit" value="Refund"  />
</form>
```

### Route

```php
Route::post('/refund', 'RaveController@refund')->name('refund');
```

### Controller

```php
public function refund()
  {
    $data = Rave::refund();

    dd($data);

    // {
    //     "data": {
    //         "AmountRefunded": 15,
    //         "walletId": 976,
    //         "createdAt": "2017-12-18T11:19:15.000Z",
    //         "AccountId": 832,
    //         "id": 76,
    //         "FlwRef": "FLW-MOCK-f129ce9ac1fe993091795ce08c43fb9b",
    //         "TransactionId": 57898,
    //         "status": "completed",
    //         "updatedAt": "2017-12-18T11:19:15.000Z"
    //     },
    //     "message": "Refunded",
    //     "status": "success"
    // }
  }
```