# Installation

This will guide you in installing the package

## Prerequisite
[PHP](https://php.net) 7.2+, [Laravel](https://laravel.com) and [Composer](https://getcomposer.org) are required.

To get the latest version of Flutterwave, simply use composer

```bash
composer require kingflamez/laravelrave
```
For **`Laravel => 5.5`**, skip this step and go to [**`configuration`**](#configuration)

Once Flutterwave is installed, you need to register the service provider. Open up `config/app.php` and add the following to the `providers` key.

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

A configuration-file named **`flutterwave.php`** will be placed in your **`config`** directory

## Usage

Open your .env file and add your public key, secret key, environment variable and logo url like so:

Get your keys form [here](https://dashboard.flutterwave.com/dashboard/settings/apis)

```php
FLW_PUBLIC_KEY=FLWPUBK-xxxxxxxxxxxxxxxxxxxxx-X
FLW_SECRET_KEY=FLWSECK-xxxxxxxxxxxxxxxxxxxxx-X
FLW_SECRET_HASH='My_lovelysite123'
```

* **FLW_PUBLIC_KEY -** This is the api public key gotten from your dashboard (compulsory)

* **FLW_SECRET_KEY -** This is the api secret key gotten from your dashboard (compulsory)

* **FLW_SECRET_HASH -** This is the secret hash for your webhook
