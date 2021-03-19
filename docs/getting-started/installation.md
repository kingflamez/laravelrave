# Installation

This will guide you in installing the package

## Prerequisite
[PHP](https://php.net) 5.4+ or [HHVM](http://hhvm.com) 3.3+, [Laravel](https://laravel.com) and [Composer](https://getcomposer.org) are required.

To get the latest version of Flutterwave Rave for Laravel, simply use composer

```bash
composer require kingflamez/laravelrave
```
For **`Laravel => 5.5`**, skip this step and go to [**`configuration`**](#configuration)

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
RAVE_SECRET_HASH='My_lovelysite123'
```

* **RAVE_PUBLIC_KEY -** This is the api public key gotten from your dashboard (compulsory)

* **RAVE_SECRET_KEY -** This is the api secret key gotten from your dashboard (compulsory)

* **RAVE_TITLE -** This is the title of the modal (optional)

* **RAVE_ENVIRONMENT -** This can be `staging` or `live`. Staging API keys can be gotten [here](https://ravesandbox.flutterwave.com/dashboard/settings/apis) while live API keys can be gotten [here](https://rave.flutterwave.com/dashboard/settings/apis)   (compulsory)

* **RAVE_LOGO -** This is a custom logo that will be displayed on the modal  (optional)

* **RAVE_PREFIX -** This is a the prefix added to your transaction reference generated for you  (optional)

* **SECRET_HASH -** This is the secret hash for your webhook, this is necessary if you are setting up a recurrent payment
