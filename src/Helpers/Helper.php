<?php

namespace KingFlamez\Rave\Helpers;

/**
 * Flutterwave's Rave payment laravel package
 * @author Oluwole Adebiyi - Flamez <flamekeed@gmail.com>
 * @version 3
 **/
class Helper
{

    // public static function encryption($key, $data)
    // {
    //     //encode the data and the
    //     return self::encrypt3Des($data, $key);
    // }

    public static function encrypt3Des(array $data, $key)
    {
        $encData = openssl_encrypt(json_encode($data), 'DES-EDE3', $key, OPENSSL_RAW_DATA);
        return base64_encode($encData);
    }

}