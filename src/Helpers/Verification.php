<?php

namespace KingFlamez\Rave\Helpers;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

/**
 * Flutterwave's Rave payment laravel package
 * @author Oluwole Adebiyi - Flamez <flamekeed@gmail.com>
 * @version 3
 **/
class Verification
{

    protected $publicKey;
    protected $secretKey;
    protected $baseUrl;

    /**
     * Construct
     */
    function __construct(String $publicKey, String $secretKey, String $baseUrl)
    {

        $this->publicKey = $publicKey;
        $this->secretKey = $secretKey;
        $this->baseUrl = $baseUrl;
    }


    /**
     * Confirm bank account
     * @param $data
     * @return object
     */
    public function account(array $data)
    {

        $account = Http::withToken($this->secretKey)->post(
            $this->baseUrl . '/accounts/resolve',
            $data
        )->json();

        return $account;
    }


    /**
     * Verify BVN
     * @param $bvn
     * @return object
     */
    public function bvn($bvn)
    {
        $bvn = Http::withToken($this->secretKey)->get(
            $this->baseUrl . '/kyc/bvns/' . $bvn
        )->json();

        return $bvn;
    }

    /**
     * Verify reference
     * @param $reference
     * @return object
     */
    public function transaction($reference)
    {
        $transaction = Http::withToken($this->secretKey)->get(
            $this->baseUrl . '/transactions/' . $reference . 'verify'
        )->json();

        return $transaction;
    }

    /**
     * Verify Card bin
     * @param $bin
     * @return object
     */
    public function CardBin($bin)
    {
        $card = Http::withToken($this->secretKey)->get(
            $this->baseUrl . '/card-bins/' . $bin
        )->json();

        return $card;
    }



}
