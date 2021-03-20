<?php

/*
 * This file is part of the Laravel Rave package.
 *
 * (c) Oluwole Adebiyi - Flamez <flamekeed@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

return [

    /**
     * Public Key: Your Rave publicKey. Sign up on https://dashboard.flutterwave.com/ to get one from your settings page
     *
     */
    'publicKey' => env('FLW_PUBLIC_KEY'),

    /**
     * Secret Key: Your Rave secretKey. Sign up on https://dashboard.flutterwave.com/ to get one from your settings page
     *
     */
    'secretKey' => env('FLW_SECRET_KEY'),

    /**
     * Prefix: Secret hash for webhook
     *
     */
    'secretHash' => env('FLW_SECRET_HASH', ''),
];
