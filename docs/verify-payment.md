# Verify Payment

## Verify the payment with rave.

After integrating Rave checkout button and a user has successfully paid, you need to verify that the payment was successful with Rave before giving value to your customer on your website.

Although the Rave inline already verifies the payment from the client side, we strongly recommend you still do a server side verification to be double sure no foul play occurred during the payment flow.

Below are the important things to check for when validating the payment:

* Verify the transaction reference.

* Verify the `data.status` of the transaction to be `successful`.

* Verify the `chargecode` returned in the response to be `00`.

* Verify the `currency` to be the expected `currency`

* Most importantly validate the `amount paid` to be equal to or at least greater than the amount of the value to be given.


## Sample Implementation

Below is sample code of how to implement validation

### Setup your Controller
> Setup your controller to handle the routes. I created the `RaveController`. Use the `Rave`
facade. 

```php
<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;

//use the Rave Facade
use Rave;

class RaveController extends Controller
{

  /**
   * Receives Rave webhook
   * @return void
   */
  public function verify()
  {
    $txref = "rave_12345";
    
    $data = Rave::verifyTransaction($txref);

    $chargeResponsecode = $data->data->chargecode;
    $chargeAmount = $data->data->amount;
    $chargeCurrency = $data->data->currency;
    
    $amount = 4500;
    $currency = "NGN";
    if (($chargeResponsecode == "00" || $chargeResponsecode == "0") && ($chargeAmount == $amount)  && ($chargeCurrency == $currency)) {
    // transaction was successful...
    // please check other things like whether you already gave value for this ref
    // if the email matches the customer who owns the product etc
    //Give Value and return to Success page
    
        return redirect('/success');
    
    } else {
        //Dont Give Value and return to Failure page
    
        return redirect('/failed');
    }
  }

}

```