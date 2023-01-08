<?php

namespace KingFlamez\Rave\Helpers;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

/**
 * Flutterwave's Rave payment laravel package
 * @author Emmanuel A Towoju - Towoju5 <info@towoju.com.ng>
 * @version 3
 **/
class Bills
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
     * Initiate a Bill payment
     * @param $data
     * @return object
     */
    public function initiate(array $data)
    {
        return $data;
        $bill = Http::withToken($this->secretKey)->post(
            $this->baseUrl . '/bills',
            $data
        )->json();

        return $bill;
    }


    /**
     * Initiate a bulk transfer
     * @param $data
     * @return object
     */
    public function get_categories(array $data=[])
    {
        $bills_categories = Http::withToken($this->secretKey)->get(
            $this->baseUrl . '/bill-categories',
            $data
        )->json();

        return $bills_categories;
    }


    /**
     * Validate bill payment
     * @param $item_code, array $data
     * @return object
     */
    public function validate(string $item_code, array $data)
    {
        $validate = Http::withToken($this->secretKey)->get(
            $this->baseUrl . "/bill-items/$item_code/validate",
            $data
        )->json();

        return $validate;
    }


    /**
     * Get All bills payment history
     * @param $data
     * @return object
     */
    public function fetchAll(array $data = [])
    {
        $bills = Http::withToken($this->secretKey)->get(
            $this->baseUrl . '/bills',
            $data
        )->json();

        return $bills;
    }


    /**
     * Get A Bill payment status
     * @param $reference
     * @return object
     */
    public function fetch_status($reference)
    {
        $status = Http::withToken($this->secretKey)->get(
            $this->baseUrl . '/bills/'.$reference
        )->json();

        return $status;
    }

}
