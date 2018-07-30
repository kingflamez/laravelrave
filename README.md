# Flutterwave Rave (Laravel 5 Package)

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Software License][ico-license]](LICENSE.md)
[![Total Downloads][ico-downloads]][link-downloads]
<!-- [![Build Status][ico-travis]][link-travis]
[![Scrutinizer Code Quality][ico-code-quality]][link-code-quality]
[![Code Coverage][ico-coverage]][link-coverage]
[![Code Intelligence Status][ico-code-intelligence]][link-code-intelligence] -->

> Implement Flutterwave Rave payment gateway easily with Laravel

- Go to [Flutterwave Rave Live](https://rave.flutterwave.com/) to get your **`LIVE`** public and private key
- Go to [Flutterwave Rave Sandbox](https://ravesandbox.flutterwave.com/) to get your **`TEST`** public and private key



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

A configuration-file named **`rave.php`** will be placed in your **`config`** directory

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
@php
$array = array(array('metaname' => 'color', 'metavalue' => 'blue'),
                array('metaname' => 'size', 'metavalue' => 'big'));
@endphp
<h3>Buy Movie Tickets N500.00</h3>
<form method="POST" action="{{ route('pay') }}" id="paymentForm">
    {{ csrf_field() }}
    <input type="hidden" name="amount" value="500" /> <!-- Replace the value with your transaction amount -->
    <input type="hidden" name="payment_method" value="both" /> <!-- Can be card, account, both -->
    <input type="hidden" name="description" value="Beats by Dre. 2017" /> <!-- Replace the value with your transaction description -->
    <input type="hidden" name="country" value="NG" /> <!-- Replace the value with your transaction country -->
    <input type="hidden" name="currency" value="NGN" /> <!-- Replace the value with your transaction currency -->
    <input type="hidden" name="email" value="test@test.com" /> <!-- Replace the value with your customer email -->
    <input type="hidden" name="firstname" value="Oluwole" /> <!-- Replace the value with your customer firstname -->
    <input type="hidden" name="lastname" value="Adebiyi" /> <!-- Replace the value with your customer lastname -->
    <input type="hidden" name="metadata" value="{{ json_encode($array) }}" > <!-- Meta data that might be needed to be passed to the Rave Payment Gateway -->
    <input type="hidden" name="phonenumber" value="090929992892" /> <!-- Replace the value with your customer phonenumber -->
    <input type="hidden" name="paymentplan" value="362" /> <!-- Ucomment and Replace the value with the payment plan id -->
    {{-- <input type="hidden" name="ref" value="MY_NAME_5uwh2a2a7f270ac98" /> <!-- Ucomment and  Replace the value with your transaction reference. It must be unique per transaction. You can delete this line if you want one to be generated for you. --> --}}
    {{-- <input type="hidden" name="logo" value="https://pbs.twimg.com/profile_images/915859962554929153/jnVxGxVj.jpg" /> <!-- Replace the value with your logo url (Optional, present in .env)--> --}}
    {{-- <input type="hidden" name="title" value="Flamez Co" /> <!-- Replace the value with your transaction title (Optional, present in .env) --> --}}
    <input type="submit" value="Buy"  />
</form>
```

#### 3. Setup your Controller
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
  }

  /**
   * Obtain Rave callback information
   * @return void
   */
  public function callback()
  {

    $data = Rave::verifyTransaction(request()->txref);

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

<!-- ### Payment -->
<!-- <p align="center">
 <img src="https://raw.githubusercontent.com/kingflamez/laravelrave/master/resources/img/rave.jpg" alt="Pay page"/>
</p> -->

## Documentation
1. [Recurring Payment ](readmes/recurringPayment.md)


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

 - Support Direct Charges
 - Support Tokenized payment
 - Misellaneous
 - Pre Auth
 - Sub Accounts
 - Transfers


## Credits

- [Oluwole Adebiyi (Flamez)][link-author]
- [Emmanuel Okeke](https://github.com/emmanix2002)
- [Adebayo Mustafa](https://github.com/AdebsAlert)
- [Tunde Aromire](https://github.com/toondaey)

## Contributing
Please feel free to fork this package and contribute by submitting a pull request to enhance the functionalities. I will appreciate that a lot. Also please add your name to the credits.

Kindly [follow me on twitter](https://twitter.com/mrflamez_)!

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

[ico-version]: https://img.shields.io/packagist/v/kingflamez/laravelrave.svg?style=flat-square
[ico-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square
<!-- [ico-travis]: https://travis-ci.org/toondaey/laravelrave.svg?branch=master -->
<!-- [ico-scrutinizer]: https://img.shields.io/scrutinizer/coverage/g/kingflamez/laravelrave.svg?style=flat-square -->
<!-- [ico-code-quality]: https://scrutinizer-ci.com/g/toondaey/laravelrave/badges/quality-score.png?b=master -->
<!-- [ico-code-intelligence]: https://scrutinizer-ci.com/g/toondaey/laravelrave/badges/code-intelligence.svg?b=master -->
<!-- [ico-coverage]: https://scrutinizer-ci.com/g/toondaey/laravelrave/badges/coverage.png?b=master -->
[ico-downloads]: https://img.shields.io/packagist/dt/kingflamez/laravelrave.svg?style=flat-square

[link-packagist]: https://packagist.org/packages/kingflamez/laravelrave
[link-travis]: https://travis-ci.org/toondaey/laravelrave
[link-scrutinizer]: https://scrutinizer-ci.com/g/kingflamez/laravelrave/code-structure
[link-code-quality]: https://scrutinizer-ci.com/g/toondaey/laravelrave/?branch=master
[link-downloads]: https://packagist.org/packages/kingflamez/laravelrave
[link-author]: https://github.com/kingflamez
[link-contributors]: ../../contributors
[link-coverage]: https://scrutinizer-ci.com/g/toondaey/laravelrave/?branch=master
[link-code-intelligence]: https://scrutinizer-ci.com/code-intelligence
