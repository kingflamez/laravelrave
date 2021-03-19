# Exchange Rates

Rave allows your convert currencies real time via api's to charge your customers in alternate currencies. See table below show possible exchange rates combination via the API

## Pre-requisites for using the BVN validation service.

| origin currency        | destination currency  |
| ------------- |:-------------:|
| NGN | KES |
| GHS | USD |
| USD | KES |
| USD | GHS |
| USD | NGN |
| GBP | EUR |
| GBP | NGN |
| EUR | NGN |

## Sample implementation

```html
<form method="POST" action="{{ route('exchangerates') }}" id="paymentForm">
    {{ csrf_field() }}
    <input type="text" name="origin_currency" placeholder="Origin Currency" required />
    <input type="text" name="destination_currency" placeholder="Destination Currency" required />
    {{-- <input type="text" name="amount" placeholder="Amount" /> <!-- Uncomment if you want to add a duration --> --}}
    <input type="submit" value="List Exchange Rates"  />
</form>
```

### Controller

```php
public function exchangeRates()
  {

    $data = Rave::exchangeRates();

    dd($data);

    // {
    //     "status": "success",
    //     "message": "Rate Fetched",
    //     "data": {
    //         "rate": 385,
    //         "origincurrency": "USD",
    //         "destinationcurrency": "NGN",
    //         "lastupdated": "2017-05-29 13:03:35",
    //         "converted_amount": 7707700,
    //         "original_amount": "20020"
    //     }
    // }
  }
```