<?php

namespace KingFlamez\Rave\Helpers;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

/**
 * Flutterwave's Rave payment laravel package
 * @author Oluwole Adebiyi - Flamez <flamekeed@gmail.com>
 * @version 3
 **/
class Transfers
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
     * Initiate a transfer
     * @param $data
     * @return object
     */
    public function initiate(array $data)
    {
        $transfer = Http::withToken($this->secretKey)->post(
            $this->baseUrl . '/transfers',
            $data
        )->json();

        return $transfer;
    }


    /**
     * Initiate a bulk transfer
     * @param $data
     * @return object
     */
    public function bulk(array $data)
    {
        $transfer = Http::withToken($this->secretKey)->post(
            $this->baseUrl . '/bulk-transfers',
            $data
        )->json();

        return $transfer;
    }


    /**
     * Retry a transfer
     * @param $transferId
     * @return object
     */
    public function retry($transferId)
    {
        $transfer = Http::withToken($this->secretKey)->post(
            $this->baseUrl . '/transfers/' . $transferId . '/retries'
        )->json();

        return $transfer;
    }


    /**
     * Get Fees
     * @param $data
     * @return object
     */
    public function fees(array $data)
    {
        $transfer = Http::withToken($this->secretKey)->get(
            $this->baseUrl . '/transfers/fee',
            $data
        )->json();

        $transfer['data'] = $transfer['data'][0];
        return $transfer;
    }
}
