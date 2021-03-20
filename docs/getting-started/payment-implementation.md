# Payment Implementation

Initiating a Payment is simple using this package:

## 1. Setup Routes

```php
// The page that displays the payment form
Route::get('/', function () {
    return view('welcome');
});
// The route that the button calls to initialize payment
Route::post('/pay', [FlutterwaveController::class, 'initialize'])->name('pay');
// The callback url after a payment
Route::get('/rave/callback', [FlutterwaveController::class, 'callback'])->name('callback');
```

## 2. Setup the Payment Page

A sample payment button will look like so:

> welcome.blade.php

```html
<h3>Buy Movie Tickets N500.00</h3>
<form method="POST" action="{{ route('pay') }}" id="paymentForm">
    {{ csrf_field() }}

    <input name="name" placeholder="Name" />
    <input name="email" type="email" placeholder="Your Email" />
    <input name="phone" type="tel" placeholder="Phone number" />

    <input type="submit" value="Buy" />
</form>
```

## 3. Setup your Controller

> Setup your controller to handle the routes. I created the `FlutterwaveController`. Use the `Rave` as `Flutterwave`
> facade.

### Example

```php
<?php

namespace App\Http\Controllers;

use KingFlamez\Rave\Facades\Rave as Flutterwave;

class FlutterwaveController extends Controller
{
    /**
     * Initialize Rave payment process
     * @return void
     */
    public function initialize()
    {
        //This generates a payment reference
        $reference = Flutterwave::generateReference();

        // Enter the details of the payment
        $data = [
            'payment_options' => 'card,banktransfer',
            'amount' => 500,
            'email' => request()->email,
            'tx_ref' => $reference,
            'currency' => "NGN",
            'redirect_url' => route('callback'),
            'customer' => [
                'email' => request()->email,
                "phonenumber" => request()->phone,
                "name" => request()->name
            ],

            "customizations" => [
                "title" => 'Movie Ticket',
                "description" => "20th October"
            ]
        ];

        $payment = Flutterwave::initializePayment($data);

        if (!$payment) {
            // notify something went wrong
            return;
        }

        return redirect($payment['link']);
    }

    /**
     * Obtain Rave callback information
     * @return void
     */
    public function callback()
    {

        
        $data = Flutterwave::verifyTransaction(request()->transaction_id);

        dd($data);
        // Get the transaction from your DB using the transaction reference (txref)
        // Check if you have previously given value for the transaction. If you have, redirect to your successpage else, continue
        // Confirm that the $data['data']['status'] is 'successful'
        // Confirm that the currency on your db transaction is equal to the returned currency
        // Confirm that the db transaction amount is equal to the returned amount
        // Update the db transaction record (including parameters that didn't exist before the transaction is completed. for audit purpose)
        // Give value for the transaction
        // Update the transaction to note that you have given value for the transaction
        // You can also redirect to your success page from here

    }
}
```
