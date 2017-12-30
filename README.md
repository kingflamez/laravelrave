# Flutterwave Rave (Laravel 5 Package)

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Software License][ico-license]](LICENSE.md)
[![Total Downloads][ico-downloads]][link-downloads]

> Implement Flutterwave Rave payment gateway easily with Laravel

Go to [Flutterwave Rave](https://ravepay.co) to get your public and private key

## Installation

[PHP](https://php.net) 5.4+ or [HHVM](http://hhvm.com) 3.3+, [Laravel](https://laravel.com) and [Composer](https://getcomposer.org) are required.

To get the latest version of Flutterwave Rave for Laravel, simply use composer

```bash
composer require kingflamez/laravelrave
```
For **`Laravel => 5.5`**, skip this step and go to [**`configuration`**](https://github.com/kingflamez/laravelrave#configuration)

Once Flutterwave Rave for Laravel is installed, you need to register the service provider. Open up `config/app.php` and add the following to the `providers` key.

```php
'providers' => [
    /*
     * Package Service Providers...
     */
    ...
    KingFlamez\Rave\RaveServiceProvider::class,
    ...
]
```

Also add this to the `aliases`

```php
'aliases' => [
    ...
    'Rave' => KingFlamez\Rave\Facades\Rave::class,
    ...
]
```

## Configuration

Publish the configuration file using this command:

```bash
php artisan vendor:publish --provider="KingFlamez\Rave\RaveServiceProvider"
```

A configuration-file named **`rave.php`** will be placed in your **`config`** directory:

```php
<?php
return [

    /**
     * Public Key: Your Rave publicKey. Sign up on https://ravepay.co to get one from your settings page
     *
     */
    'publicKey' => getenv('RAVE_PUBLIC_KEY'),

    /**
     * Secret Key: Your Rave secretKey. Sign up on https://ravepay.co to get one from your settings page
     *
     */
    'secretKey' => getenv('RAVE_SECRET_KEY'),

    /**
     * Company/Business/Store Name: The name of your store
     *
     */
    'title' => env('RAVE_TITLE', 'Rave Payment Gateway'),

    /**
     * Environment: This can either be 'staging' or 'live'
     *
     */
    'env' => env('RAVE_ENVIRONMENT', 'staging'),

    /**
     * Logo: Enter the URL of your company/business logo
     *
     */
    'logo' => env('RAVE_LOGO', ''),

];
```
## Usage

Open your .env file and add your public key, secret key, environment variable and logo url like so:

```php
RAVE_PUBLIC_KEY=FLWPUBK-xxxxxxxxxxxxxxxxxxxxx-X
RAVE_SECRET_KEY=FLWSECK-xxxxxxxxxxxxxxxxxxxxx-X
RAVE_TITLE="ABC Company"
RAVE_ENVIRONMENT="staging"
RAVE_LOGO="https://pbs.twimg.com/profile_images/915859962554929153/jnVxGxVj.jpg"
RAVE_PREFIX="rave"
```
RAVE_ENVIRONMENT can be staging or live 


## Sample implementation

In this implementation, we are expecting a form encoded POST request to this script.
The request will contain the following parameters.

- payment_method `Can be card, account, both`
- description `Your transaction description`
- logo `Your logo url`
- title `Your transaction title`
- country `Your transaction country`
- currency `Your transaction currency`
- email `Your customer's email`
- firstname `Your customer's firstname`
- lastname `Your customer's lastname`
- phonenumber `Your customer's phonenumber`
- pay_button_text `The payment button text you prefer`
- ref `Your transaction reference. It must be unique per transaction.  By default, the Rave class generates a unique transaction reference for each transaction. Pass this parameter only if you uncommented the related section in the script below.`


#### 1. Setup Routes

```php
Route::post('/pay', 'RaveController@initialize')->name('pay');
Route::post('/rave/callback', 'RaveController@callback')->name('callback');
```

#### 2. Grant CSRF Access to Rave Callback
Go to `app/Http/Middleware/VerifyCsrfToken.php` and add your callback url to the `$except` array

```php
protected $except = [
    'rave/callback'
];
```

A sample form will look like so:
```html
 <?php
$array = array(array('metaname' => 'color', 'metavalue' => 'blue'),
                array('metaname' => 'size', 'metavalue' => 'big'));
?>
<h3>Buy Beats By Dre N30000.75</h3>
<form method="POST" action="{{ route('pay') }}" id="paymentForm">
    {{ csrf_field() }}
    <input type="hidden" name="amount" value="30000.75" /> <!-- Replace the value with your transaction amount -->
    <input type="hidden" name="payment_method" value="both" /> <!-- Can be card, account, both -->
    <input type="hidden" name="description" value="Beats by Dre. 2017" /> <!-- Replace the value with your transaction description -->
    <input type="hidden" name="logo" value="https://pbs.twimg.com/profile_images/915859962554929153/jnVxGxVj.jpg" /> <!-- Replace the value with your logo url (Optional, present in .env)-->
    <input type="hidden" name="title" value="Flamez Co" /> <!-- Replace the value with your transaction title (Optional, present in .env) -->
    <input type="hidden" name="country" value="NG" /> <!-- Replace the value with your transaction country -->
    <input type="hidden" name="currency" value="NGN" /> <!-- Replace the value with your transaction currency -->
    <input type="hidden" name="email" value="flamekeed@gmail.com" /> <!-- Replace the value with your customer email -->
    <input type="hidden" name="firstname" value="Oluwole" /> <!-- Replace the value with your customer firstname -->
    <input type="hidden" name="lastname" value="Adebiyi" /> <!-- Replace the value with your customer lastname -->
    <input type="hidden" name="metadata" value="{{ json_encode($array) }}" > <!-- Meta data that might be needed to be passed to the Rave Payment Gateway -->
    <input type="hidden" name="phonenumber" value="07036940769" /> <!-- Replace the value with your customer phonenumber -->
    <input type="hidden" name="pay_button_text" value="Complete Payment" /> <!-- Replace the value with the payment button text you prefer -->
    <input type="hidden" name="ref" value="MY_NAME_5a2a7f270ac98" /> <!-- Replace the value with your transaction reference. It must be unique per transaction. You can delete this line if you want one to be generated for you. -->
    <input type="submit" value="Buy"  />
</form>
```

#### 3.1 Setup your Controller
> Setup your controller to handle the routes. I created the `RaveController`. Use the `Rave`
facade. 

#### Example

```php
<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;

//use the Rave Facade
use Rave;

class RaveController extends Controller
{

    /**
     * Initialize Rave payment process
     * @return void
     */
    public function initialize()
    {
        //This initializes payment and redirects to the payment gateway
        //The initialize method takes the parameter of the redirect URL
        Rave::initialize(route('callback'));

        /***
        *For more functionality you can use more methods like the one below
        *setKeys($publicKey, $secretKey) - This is used to set the puvlic and secret key incase you wat to use another one different from your .env
        *setEnvironment($env) - This is used to set to either staging or live incase you want to use something different from your .env
        *
        *setPrefix($prefix, $overrideRefWithPrefix=false) - 
        ***$prefix - To add prefix to your transaction reference eg. KC will lead to KC_hjdjghddhgd737
        ***$overrideRefWithPrefix - either true/false. True will override the autogenerate reference with $prefix/request()->ref while false will use the $prefix as your prefix
        **/

        //Rave::setKeys($publicKey, $secretKey)->setEnvironment($env)->setPrefix($prefix, $overrideRefWithPrefix=false)->initialize(route('callback'));

        //eg: Rave::setEnvironment('live')->setPrefix("flamez")->initialize(route('callback'));
        //eg: Rave::setKeys("PWHNNJ992838uhzjhjshud", "PWHNNJ992838uhzjhjshud")->setPrefix(request()->ref, true)->initialize(route('callback'));
        //eg: Rave::setKeys("PWHNNJ992838uhzjhjshud, "PWHNNJ992838uhzjhjshud")->setEnvironment('staging')->setPrefix("rave", false)->initialize(route('callback'));
    }

    /**
     * Obtain Rave callback information
     * @return void
     */
    public function callback()
    {
        $data = Rave::requeryTransaction(request()->txref);

        dd($data);
        // Get the transaction from your DB using the transaction reference (txref)
        // Check if you have previously given value for the transaction. If you have, redirect to your successpage else, continue
        // Comfirm that the transaction is successful
        // Confirm that the chargecode is 00 or 0
        // Confirm that the currency on your db transaction is equal to the returned currency
        // Confirm that the db transaction amount is equal to the returned amount
        // Update the db transaction record (includeing parameters that didn't exist before the transaction is completed. for audit purpose)
        // Give value for the transaction
        // Update the transaction to note that you have given value for the transaction
        // You can also redirect to your success page from here
        
    }
}
```

#### 3.2 Setup your Controller with event handling
>For more functionality, you are adviced to use the Event Handler Interface, it enables more flexibility in handling transactions events.

```php
<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;

//use the Rave Facade
use Rave;
//use the Class that implemented the RaveEventHandlerInterface, we will create this next
use App\Events\PaymentEventHandler;

class RaveController extends Controller
{

    /**
     * Initialize Rave payment process
     * @return void
     */
    public function initialize()
    {
        //This initializes payment and redirects to the payment gateway
        //The initialize method takes the parameter of the redirect URL
        //Use the eventHandler method and pass a new instance of your class implementing RaveEventHandlerInterface in the parameter
        Rave::eventHandler(new PaymentEventHandler)->initialize(route('callback'));

        /***
        *For more functionality you can use more methods like the one below
        *setKeys($publicKey, $secretKey) - This is used to set the puvlic and secret key incase you wat to use another one different from your .env
        *setEnvironment($env) - This is used to set to either staging or live incase you want to use something different from your .env
        *
        *setPrefix($prefix, $overrideRefWithPrefix=false) - 
        ***$prefix - To add prefix to your transaction reference eg. KC will lead to KC_hjdjghddhgd737
        ***$overrideRefWithPrefix - either true/false. True will override the autogenerate reference with $prefix/request()->ref while false will use the $prefix as your prefix
        **/

        //Rave::eventHandler(new PaymentEventHandler)->setKeys($publicKey, $secretKey)->setEnvironment($env)->setPrefix($prefix, $overrideRefWithPrefix=false)->initialize(route('callback'));

        //eg: Rave::eventHandler(new PaymentEventHandler)->setEnvironment('live')->setPrefix("flamez")->initialize(route('callback'));
        //eg: Rave::eventHandler(new PaymentEventHandler)->setKeys("PWHNNJ992838uhzjhjshud", "PWHNNJ992838uhzjhjshud")->setPrefix(request()->ref, true)->initialize(route('callback'));
        //eg: Rave::eventHandler(new PaymentEventHandler)->setKeys("PWHNNJ992838uhzjhjshud, "PWHNNJ992838uhzjhjshud")->setEnvironment('staging')->setPrefix("rave", false)->initialize(route('callback'));
    }

    /**
     * Obtain Rave callback information
     * @return void
     */
    public function callback()
    {
        //Use the eventHandler method and pass a new instance of your class implementing RaveEventHandlerInterface in the parameter
        if(request()->cancelled && request()->txref){
            // Handle canceled payments
            Rave::eventHandler(new PaymentEventHandler)
            ->requeryTransaction(request()->txref)
            ->paymentCanceled(request()->txref);
        }elseif(request()->txref){
            // Handle completed payments  
            Rave::eventHandler(new PaymentEventHandler)
            ->requeryTransaction(request()->txref);
        }else{
            echo 'Stop!!! Please pass the txref parameter!';
        }
    }
}

```

#### 4. Create the Event Handler Class
>This is where you set how you want to handle the transaction at different stages. You can store this anywhere. As for me, I created an `Events` folder in the `app/` directory.  Location: `app/Events/PaymentEventHandler.php`. You can have different event handlers for different type of payments. You can even store it in your controller `App\Http\Controllers`. Anyone that suits you

<p align="center">
 <img src="https://raw.githubusercontent.com/kingflamez/laravelrave/master/resources/img/RaveInterface.jpg" style="height: 100px" alt="Events Directory with different classes implementing the RaveEventHandlerInterface"/>
</p>

<p align="center">Events Directory with different event handler classes implementing the RaveEventHandlerInterface</p>

>Copy and paste the methods and replace with your actions for each event

```php
<?php

namespace App\Events;

//use the Rave Event Handler Interface
use KingFlamez\Rave\RaveEventHandlerInterface;

// This is where you set how you want to handle the transaction at different stages
// You can have multiple Event Handler for different purposes of payments

//This class should implement  the RaveEventHandlerInterface and take all the methods
class PaymentEventHandler implements RaveEventHandlerInterface{
    /**
     * This is called when the Rave class is initialized
     * */
    function onInit($initializationData){
        // Save the transaction to your DB.
        echo 'Payment started......'.json_encode($initializationData).'<br />'; //Remember to delete this line
    }
    
    /**
     * This is called only when a transaction is successful
     * */
    function onSuccessful($transactionData){
        // Get the transaction from your DB using the transaction reference (txref)
        // Check if you have previously given value for the transaction. If you have, redirect to your successpage else, continue
        // Comfirm that the transaction is successful
        // Confirm that the chargecode is 00 or 0
        // Confirm that the currency on your db transaction is equal to the returned currency
        // Confirm that the db transaction amount is equal to the returned amount
        // Update the db transaction record (includeing parameters that didn't exist before the transaction is completed. for audit purpose)
        // Give value for the transaction
        // Update the transaction to note that you have given value for the transaction
        // You can also redirect to your success page from here
        echo 'Payment Successful!'.json_encode($transactionData).'<br />'; //Remember to delete this line
    }
    
    /**
     * This is called only when a transaction failed
     * */
    function onFailure($transactionData){
        // Get the transaction from your DB using the transaction reference (txref)
        // Update the db transaction record (includeing parameters that didn't exist before the transaction is completed. for audit purpose)
        // You can also redirect to your failure page from here
        echo 'Payment Failed!'.json_encode($transactionData).'<br />'; //Remember to delete this line
    }
    
    /**
     * This is called when a transaction is requeryed from the payment gateway
     * */
    function onRequery($transactionReference){
        // Do something, anything!
        echo 'Payment requeried......'.$transactionReference.'<br />'; //Remember to delete this line
    }
    
    /**
     * This is called a transaction requery returns with an error
     * */
    function onRequeryError($requeryResponse){
        // Do something, anything!
        echo 'An error occured while requeying the transaction...'.json_encode($requeryResponse).'<br />'; //Remember to delete this line
    }
    
    /**
     * This is called when a transaction is canceled by the user
     * */
    function onCancel($transactionReference){
        // Do something, anything!
        // Note: Somethings a payment can be successful, before a user clicks the cancel button so proceed with caution
        echo 'Payment canceled by user......'.$transactionReference.'<br />'; //Remember to delete this line
    }
    
    /**
     * This is called when a transaction doesn't return with a success or a failure response. This can be a timedout transaction on the Rave server or an abandoned transaction by the customer.
     * */
    function onTimeout($transactionReference, $data){
        // Get the transaction from your DB using the transaction reference (txref)
        // Queue it for requery. Preferably using a queue system. The requery should be about 15 minutes after.
        // Ask the customer to contact your support and you should escalate this issue to the flutterwave support team. Send this as an email and as a notification on the page. just incase the page timesout or disconnects
        echo 'Payment timeout......'.$transactionReference.' - '.json_encode($data).'<br />'; //Remember to delete this line
    }
}
```

You can also find the class documentation in the docs folder. There you will find documentation for the [`Rave`](https://github.com/Flutterwave/Flutterwave-Rave-PHP-SDK/tree/master/docs) class and the `EventHandlerInterface`.

### Payment
<p align="center">
 <img src="https://raw.githubusercontent.com/kingflamez/laravelrave/master/resources/img/pay.jpg" alt="Pay page"/>
</p>

<p align="center">
 <img src="https://raw.githubusercontent.com/kingflamez/laravelrave/master/resources/img/rave.jpg" alt="Pay page"/>
</p>

>Test Card

```bash
5438898014560229
cvv 789
Expiry Month 09
Expiry Year 19
Pin 3310
otp 12345
```

>Test Bank Account

```bash
Access Bank
Account number: 0690000004
otp: 12345
```

```bash
Providus Bank
Account number: 5900102340, 5900002567
otp: 12345
```

For [More Test Cards](https://flutterwavedevelopers.readme.io/docs/test-cards)
For [More Test Bank Accounts](https://flutterwavedevelopers.readme.io/docs/test-bank-accounts)

## ToDo

 - Write Unit Test
 - Support Direct Charges
 - Support Tokenized payment
 - Recurring Payment


## Credits

- [Oluwole Adebiyi (Flamez)][link-author]

## Contributing
Please feel free to fork this package and contribute by submitting a pull request to enhance the functionalities. I will appreciate that a lot. I'm a newbie. I will appreciate a lot of stars.

This package is based on [Flutterwave-Rave-PHP-SDK](https://github.com/kingflamez/Flutterwave-Rave-PHP-SDK)


Kindly star the GitHub repo and share ❤️.  I ❤️ Flutterwave

Kindly [follow me on twitter](https://twitter.com/mrflamez_)!


## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

[ico-version]: https://img.shields.io/packagist/v/kingflamez/laravelrave.svg?style=flat-square
[ico-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square
[ico-travis]: https://img.shields.io/travis/kingflamez/laravelrave/master.svg?style=flat-square
[ico-scrutinizer]: https://img.shields.io/scrutinizer/coverage/g/kingflamez/laravelrave.svg?style=flat-square
[ico-code-quality]: https://img.shields.io/scrutinizer/g/kingflamez/laravelrave.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/kingflamez/laravelrave.svg?style=flat-square

[link-packagist]: https://packagist.org/packages/kingflamez/laravelrave
[link-travis]: https://travis-ci.org/kingflamez/laravelrave
[link-scrutinizer]: https://scrutinizer-ci.com/g/kingflamez/laravelrave/code-structure
[link-code-quality]: https://scrutinizer-ci.com/g/kingflamez/laravelrave
[link-downloads]: https://packagist.org/packages/kingflamez/laravelrave
[link-author]: https://github.com/kingflamez
[link-contributors]: ../../contributors
