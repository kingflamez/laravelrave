# Flutterwave Rave (Laravel 5 Package)

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Software License][ico-license]](LICENSE.md)
[![Total Downloads][ico-downloads]][link-downloads]
<!-- [![Build Status][ico-travis]][link-travis]
[![Scrutinizer Code Quality][ico-code-quality]][link-code-quality]
[![Code Coverage][ico-coverage]][link-coverage]
[![Code Intelligence Status][ico-code-intelligence]][link-code-intelligence] -->

> Implement Flutterwave Rave payment gateway easily with Laravel

- Go to [Flutterwave Rave Live](https://rave.flutterwave.com) to get your **`LIVE`** public and private key
- Go to [Flutterwave Rave Test](https://raveappv2.herokuapp.com) to get your **`TEST`** public and private key



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

## Documentation
1. [Sample Implementaion](readmes/ravemodal.md)
2. [Recurring Payment ](readmes/recurringPayment.md)

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
