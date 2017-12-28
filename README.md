# Flutterwave Rave (Laravel 5 Package)

<!-- [![Latest Version on Packagist][ico-version]][link-packagist]
[![Software License][ico-license]](LICENSE.md)
[![Total Downloads][ico-downloads]][link-downloads] -->

> Implement Flutterwave Rave payment gateway easily with Laravel

Go to [Flutterwave Rave](https://ravepay.co) to get your public and private key

## Installation

[PHP](https://php.net) 5.4+ or [HHVM](http://hhvm.com) 3.3+, [Laravel](https://laravel.com) and [Composer](https://getcomposer.org) are required.

To get the latest version of Flutterwave Rave for Laravel, simply use composer

```
composer require kingflamez/laravelrave
```
For Larvel => 5.5, skip this step and go to [`configuration`](https://github.com/kingflamez/laravelrave#configuration)

Once Flutterwave Rave for Laravel is installed, you need to register the service provider. Open up `config/app.php` and add the following to the `providers` key.

```php
'providers' => [
    /*
     * Package Service Providers...
     */
    ...
    `KingFlamez\Rave\RaveServiceProvider::class`
    ...
]
```

## Configuration

Publish the configuration file using this command:

```bash
php artisan vendor:publish --provider="KingFlamez\Rave\RaveServiceProvider"
```

A configuration-file named `rave.php` will be placed in your `config` directory:

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

Open your .env file and add your public key, secret key, merchant email and payment url like so:

```php
RAVE_PUBLIC_KEY=FLWPUBK-xxxxxxxxxxxxxxxxxxxxx-X
RAVE_SECRET_KEY=FLWSECK-xxxxxxxxxxxxxxxxxxxxx-X
RAVE_TITLE="ABC Company"
RAVE_ENVIRONMENT="staging"
RAVE_LOGO="https://pbs.twimg.com/profile_images/915859962554929153/jnVxGxVj.jpg"
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


#### Setup Routes
```php
Route::post('/pay', 'RaveController@initialize')->name('pay');
Route::post('/rave/callback', 'RaveController@callback')->name('callback');
```

#### Grant CSRF Access to Rave Callback
Go to `app/Http/Middleware/VerifyCsrfToken.php` and add your callback url to the `$except` array

```php
protected $except = [
    'rave/callback'
];
```

A sample form will look like so:
```html
<h3>Buy Beats By Dre N30000.75</h3>
<form method="POST" action="{{ route('pay') }}" id="paymentForm">
    {{ csrf_field() }}
    <input type="hidden" name="amount" value="30000.75" /> <!-- Replace the value with your transaction amount -->
    <input type="hidden" name="payment_method" value="both" /> <!-- Can be card, account, both -->
    <input type="hidden" name="description" value="Beats by Dre. 2017" /> <!-- Replace the value with your transaction description -->
    <input type="hidden" name="logo" value="https://pbs.twimg.com/profile_images/915859962554929153/jnVxGxVj.jpg" /> <!-- Replace the value with your logo url -->
    <input type="hidden" name="title" value="Flamez Co" /> <!-- Replace the value with your transaction title -->
    <input type="hidden" name="country" value="NG" /> <!-- Replace the value with your transaction country -->
    <input type="hidden" name="currency" value="NGN" /> <!-- Replace the value with your transaction currency -->
    <input type="hidden" name="email" value="flamekeed@gmail.com" /> <!-- Replace the value with your customer email -->
    <input type="hidden" name="firstname" value="Oluwole" /> <!-- Replace the value with your customer firstname -->
    <input type="hidden" name="lastname" value="Adebiyi" /> <!-- Replace the value with your customer lastname -->
    <input type="hidden" name="color" value="green" > <!-- This is a meta data that might be needed to be passed to the Rave Payment Gateway -->
    <input type="hidden" name="size" value="big" > <!-- This is a meta data that might be needed to be passed to the Rave Payment Gateway -->
    <input type="hidden" name="phonenumber" value="07036940769" /> <!-- Replace the value with your customer phonenumber -->
    <input type="hidden" name="pay_button_text" value="Complete Payment" /> <!-- Replace the value with the payment button text you prefer -->
    <input type="hidden" name="ref" value="MY_NAME_5a2a7f270ac98" /> <!-- Replace the value with your transaction reference. It must be unique per transaction. You can delete this line if you want one to be generated for you. -->
    <input type="submit" value="Buy"  />
</form>
```

#### Setup your Controller
> Class documentation can be found here [https://flutterwave.github.io/Flutterwave-Rave-PHP-SDK/packages/Default.html](https://flutterwave.github.io/Flutterwave-Rave-PHP-SDK/packages/Default.html)

```php
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

//import the Rave Class and the Rave Event Handler Interface
use KingFlamez\Rave\Rave;
use KingFlamez\Rave\RaveEventHandlerInterface;

class RaveController extends Controller
{
    public function initialize()
    {
        $prefix = 'MY_COMPANY_NAME'; // Change this to the name of your business or app
        $overrideRef = false;

        // Uncomment here to enforce the useage of your own ref else a ref will be generated for you automatically
        // if(request()->ref){
        //     $prefix = request()->ref;
        //     $overrideRef = true;
        // }

        //Initialize Rave class
        $rave = new Rave($prefix, $overrideRef);

        $rave
        ->eventHandler(new myEventHandler)
        ->setAmount(request()->amount)
        ->setPaymentMethod(request()->payment_method) // value can be card, account or both
        ->setDescription(request()->description)
        ->setLogo(request()->logo) // This might not be included if you have it set in your .env file
        ->setTitle(request()->title) // This can be left blank if you have it set in your .env file
        ->setCountry(request()->country)
        ->setCurrency(request()->currency)
        ->setEmail(request()->email)
        ->setFirstname(request()->firstname)
        ->setLastname(request()->lastname)
        ->setPhoneNumber(request()->phonenumber)
        ->setPayButtonText(request()->pay_button_text)
        ->setRedirectUrl(route('callback'))
        // ->setMetaData(array('metaname' => 'SomeDataName', 'metavalue' => 'SomeValue')) // can be called multiple times. Uncomment this to add meta datas
        // ->setMetaData(array('metaname' => 'SomeOtherDataName', 'metavalue' => 'SomeOtherValue')) // can be called multiple times. Uncomment this to add meta datas
        // ->setMetaData(array('metaname' => 'color', 'metavalue' => 'Blue')) // can be called multiple times. Example. Uncomment this to add meta datas
        ->initialize();
    }

    /**
     * Obtain Rave callback information
     * @return void
     */
    public function callback()
    {
        $prefix = 'MY_COMPANY_NAME';

        $rave = new Rave($prefix);
        if(request()->cancelled && request()->txref){
            // Handle canceled payments
            $rave
            ->eventHandler(new myEventHandler)
            ->requeryTransaction(request()->txref)
            ->paymentCanceled(request()->txref);
        }elseif(request()->txref && request()->flwref){
            // Handle completed payments   

            //$order = Order::where('referenceNumber', request()->txref)->first();
            //$amount = $order->amount
            //$currency = $order->currency

            $amount = 30000.75; //Fetch amount of the order or product from your server eg with eloquent
            $currency = "NGN"; //Fetch amount of the order or product from your server eg with eloquent

            $rave
            ->eventHandler(new myEventHandler)
            ->requeryTransaction(request()->txref)
            ->verifyTransfer($amount, $currency);
        }else{
            echo 'Stop!!! Please pass the txref parameter!';
        }
    }
}

// This is where you set how you want to handle the transaction at different stages
//This can be created in a seperate class and use it in this controller
//eg use myEventHandler

class myEventHandler implements RaveEventHandlerInterface{
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
     * This is called when a transaction is successfully verified
     * @param string $requeryResponse This is the success response gotten from the Rave payment gateway verification call
     * */
    function onVerificationSuccess($requeryResponse){
        //Successful payment verification, you can do anything here
         echo 'Verification Successfull...'.json_encode($requeryResponse).'<br />'; //Remember to delete this line
         dd($requeryResponse);
    }
    
    /**
     * This is called when a transaction failed verification
     * @param string $requeryResponse This is the failed response gotten from the Rave payment gateway verification call
     * */
    function onVerificationFailed($requeryResponse){
        //Failed payment verification, you can do anything here

        //Can fail for 3 reasons
        //1. When the amount charged is not the same as the original order
        // eg. You charged NGN5000 and the user got to pay NGN800. This helps to limit fraud
        //2. When the currency the user was charged in was not the same as the original order
        // eg. You charged GHC200 and the user got to pay NGN200. 
        //3. When Flutterwave confirms from their end that it is not a successfull verification
       
        echo 'Verification Failed...'.json_encode($requeryResponse).'<br />'; //Remember to delete this line
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
You can also find the class documentation in the docs folder. There you will find documentation for the `Rave` class and the `EventHandlerInterface`.

## ToDo

- Write Unit Test
- Support Direct Charges
- Support Tokenized payment


## Contributing
Please feel free to fork this package and contribute by submitting a pull request to enhance the functionalities. I will appreciate that a lot. I'm a newbie. I will appreciate a lot of stars.

This package is based on [Flutterwave-Rave-PHP-SDK](https://github.com/kingflamez/Flutterwave-Rave-PHP-SDK)


Kindly star the GitHub repo and share ❤️.  I ❤️ Flutterwave

Kindly [follow me on twitter](https://twitter.com/mrflamez_)!


## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
