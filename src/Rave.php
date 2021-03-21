<?php

namespace KingFlamez\Rave;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

/**
 * Flutterwave's Rave payment laravel package
 * @author Oluwole Adebiyi - Flamez <flamekeed@gmail.com>
 * @version 3
 **/
class Rave
{

    protected $publicKey;
    protected $secretKey;
    protected $baseUrl;

    /**
     * Construct
     */
    function __construct()
    {

        $this->publicKey = config('flutterwave.publicKey');
        $this->secretKey = config('flutterwave.secretKey');
        $this->secretHash = config('flutterwave.secretHash');
        $this->baseUrl = 'https://api.flutterwave.com/v3';
    }


    /**
     * Generates a unique reference
     * @param $transactionPrefix
     * @return string
     */

    public function generateReference(String $transactionPrefix = NULL)
    {
        if ($transactionPrefix) {
            return $transactionPrefix . '_' . uniqid(time());
        }
        return 'flw_' . uniqid(time());
    }


    /**
     * Reaches out to Flutterwave to initialize a payment
     * @param $data
     * @return object
     */
    public function initializePayment(array $data)
    {

        $payment = Http::withToken($this->secretKey)->post(
            $this->baseUrl . '/payments',
            $data
        )->json();

        if (array_key_exists('data', $payment)) {
            return $payment['data'];
        }
        return null;
    }



    /**
     * Reaches out to Flutterwave
     * @param $id
     * @return object
     */
    public function verifyTransaction($id)
    {
        $data =  Http::withToken($this->secretKey)->get($this->baseUrl . "/transactions/" . $id . '/verify')->json();
        return $data;
    }



    /**
     * Confirms webhook `verifi-hash` is the same as the environment variable
     * @param $data
     * @return boolean
     */
    public function verifyWebhook()
    {
        // Process Paystack Webhook. https://developer.flutterwave.com/reference#webhook
        if (request()->header('verif-hash')) {
            // get input and verify paystack signature
            $flutterwaveSignature = request()->header('verif-hash');

            // confirm the signature is right
            if ($flutterwaveSignature == $this->secretHash) {
                return true;
            }
        }
        return false;
    }



    /**
     * Charge via ACH Payment
     * @param $data
     * @return object
     */
    public function chargeACHPayment(array $data)
    {
        $payment = Http::withToken($this->secretKey)->post(
            $this->baseUrl . '/charges?type=ach_payment',
            $data
        )->json();

        if ($payment['status'] === 'success') {
            return  $payment['data'];
        }

        return null;
    }



    /**
     * Charge via NGN Bank Transfer
     * @param $data
     * @return object
     */
    public function chargeNGNTransfer(array $data)
    {
        // add currency
        $data['currency'] = 'NGN';
        $payment = Http::withToken($this->secretKey)->post(
            $this->baseUrl . '/charges?type=bank_transfer',
            $data
        )->json();

        if ($payment['status'] === 'success') {
            return  $payment['meta']['authorization'];
        }

        return null;
    }



    /**
     * Charge via NGN Bank Transfer
     * @param $data
     * @return object
     */
    public function chargeGHMomo(array $data)
    {
        // add currency
        $data['currency'] = 'GHS';
        $payment = Http::withToken($this->secretKey)->post(
            $this->baseUrl . '/charges?type=mobile_money_ghana',
            $data
        )->json();

        if ($payment['status'] === 'success') {
            return  $payment['meta']['authorization']['redirect'];
        }

        return null;
    }


    /**
     * Charge via NGN Bank Transfer
     * @param $data
     * @return object
     */
    public function chargeRWMomo(array $data)
    {
        // add currency
        $data['currency'] = 'RWF';
        $payment = Http::withToken($this->secretKey)->post(
            $this->baseUrl . '/charges?type=mobile_money_rwanda',
            $data
        )->json();

        if ($payment['status'] === 'success') {
            return  $payment['meta']['authorization']['redirect'];
        }

        return null;
    }


}
