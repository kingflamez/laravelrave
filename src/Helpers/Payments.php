<?php

namespace KingFlamez\Rave\Helpers;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

/**
 * Flutterwave's Rave payment laravel package
 * @author Oluwole Adebiyi - Flamez <flamekeed@gmail.com>
 * @version 3
 **/
class Payments
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
     * Charge via ACH Payment
     * @param $data
     * @return object
     */
    public function ACH(array $data)
    {
        $payment = Http::withToken($this->secretKey)->post(
            $this->baseUrl . '/charges?type=ach_payment',
            $data
        )->json();

        return $payment;
    }



    /**
     * Charge via NGN Bank Transfer
     * @param $data
     * @return object
     */
    public function nigeriaBankTransfer(array $data)
    {
        $data['is_permanent'] = false;

        $payment = Http::withToken($this->secretKey)->post(
            $this->baseUrl . '/virtual-account-numbers',
            $data
        )->json();
        return $payment;
    }



    /**
     * Charge via Mobile Money Ghana
     * @param $data
     * @return object
     */
    public function momoGH(array $data)
    {
        // add currency
        $data['currency'] = 'GHS';
        $payment = Http::withToken($this->secretKey)->post(
            $this->baseUrl . '/charges?type=mobile_money_ghana',
            $data
        )->json();

        if ($payment['status'] === 'success') {
            return  [
                'status' => $payment['status'],
                'message' => $payment['message'],
                'data' => $payment['meta']['authorization'],
            ];
        }

        return $payment;
    }


    /**
     * Charge via Mobile Money Rwanda
     * @param $data
     * @return object
     */
    public function momoRW(array $data)
    {
        // add currency
        $data['currency'] = 'RWF';
        $payment = Http::withToken($this->secretKey)->post(
            $this->baseUrl . '/charges?type=mobile_money_rwanda',
            $data
        )->json();

        if ($payment['status'] === 'success') {
            return  [
                'status' => $payment['status'],
                'message' => $payment['message'],
                'data' => $payment['meta']['authorization'],
            ];
        }

        return $payment;
    }


    /**
     * Charge via Mobile Money Uganda
     * @param $data
     * @return object
     */
    public function momoUG(array $data)
    {
        // add currency
        $data['currency'] = 'UGX';
        $payment = Http::withToken($this->secretKey)->post(
            $this->baseUrl . '/charges?type=mobile_money_uganda',
            $data
        )->json();

        if ($payment['status'] === 'success') {
            return  [
                'status' => $payment['status'],
                'message' => $payment['message'],
                'data' => $payment['meta']['authorization'],
            ];
        }

        return $payment;
    }


    /**
     * Charge via Mobile Money Zambia
     * @param $data
     * @return object
     */
    public function momoZambia(array $data)
    {
        // add currency
        $data['currency'] = 'ZMW';
        $payment = Http::withToken($this->secretKey)->post(
            $this->baseUrl . '/charges?type=mobile_money_zambia',
            $data
        )->json();

        if ($payment['status'] === 'success') {
            return  [
                'status' => $payment['status'],
                'message' => $payment['message'],
                'data' => $payment['meta']['authorization'],
            ];
        }

        return $payment;
    }


    /**
     * Charge via Mpesa
     * @param $data
     * @return object
     */
    public function mpesa(array $data)
    {
        // add currency
        $data['currency'] = 'KES';
        $payment = Http::withToken($this->secretKey)->post(
            $this->baseUrl . '/charges?type=mpesa',
            $data
        )->json();

        return $payment;
    }


    /**
     * Charge via Mpesa
     * @param $data
     * @return object
     */
    public function voucher(array $data)
    {
        // add currency
        $data['currency'] = 'ZAR';
        $payment = Http::withToken($this->secretKey)->post(
            $this->baseUrl . '/charges?type=voucher_payment',
            $data
        )->json();

        return $payment;
    }


    /**
     * Charge via Mpesa
     * @param $data
     * @return object
     */
    public function momoFranc(array $data)
    {
        $payment = Http::withToken($this->secretKey)->post(
            $this->baseUrl . '/charges?type=mobile_money_franco',
            $data
        )->json();

        return $payment;
    }
}
