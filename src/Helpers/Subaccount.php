<?php

namespace KingFlamez\Rave\Helpers;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

/**
 * Flutterwave's Rave payment laravel package
 * @author Oluwole Adebiyi - Flamez <flamekeed@gmail.com>
 * @version 3
 **/
class Subaccount
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
     * Create a subaccount
     * @param $data
     * @return object
     */
    public function create(array $data)
    {
        $subaccount = Http::withToken($this->secretKey)->post(
            $this->baseUrl . '/subaccounts',
            $data
        )->json();

        return $subaccount;
    }


    /**
     * Update a subaccount
     * @param $id, $data
     * @return object
     */
    public function update($id, array $data)
    {
        $subaccount = Http::withToken($this->secretKey)->put(
            $this->baseUrl . '/subaccounts/'.$id,
            $data
        )->json();

        return $subaccount;
    }




    /**
     * Get All Subaccounts
     * @param $data
     * @return object
     */
    public function fetchAll(array $data = [])
    {
        $subaccounts = Http::withToken($this->secretKey)->get(
            $this->baseUrl . '/subaccounts',
            $data
        )->json();

        return $subaccounts;
    }




    /**
     * Get A subaccount
     * @param $id
     * @return object
     */
    public function fetch($id)
    {
        $subaccount = Http::withToken($this->secretKey)->get(
            $this->baseUrl . '/subaccounts/' . $id
        )->json();

        return $subaccount;
    }




    /**
     * Delete A subaccount
     * @param $id
     * @return object
     */
    public function destroy($id)
    {
        $subaccount = Http::withToken($this->secretKey)->delete(
            $this->baseUrl . '/subaccounts/' . $id
        )->json();

        return $subaccount;
    }
}
