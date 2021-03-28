<?php

namespace KingFlamez\Rave\Helpers;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

/**
 * Flutterwave's Rave payment laravel package
 * @author Oluwole Adebiyi - Flamez <flamekeed@gmail.com>
 * @version 3
 **/
class Beneficiary
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
     * Create a beneficiary
     * @param $data
     * @return object
     */
    public function create(array $data)
    {
        $beneficiary = Http::withToken($this->secretKey)->post(
            $this->baseUrl . '/beneficiaries',
            $data
        )->json();

        return $beneficiary;
    }




    /**
     * Get All Beneficiaries
     * @param $data
     * @return object
     */
    public function fetchAll(array $data = [])
    {
        $beneficiaries = Http::withToken($this->secretKey)->get(
            $this->baseUrl . '/beneficiaries',
            $data
        )->json();

        return $beneficiaries;
    }




    /**
     * Get A Beneficiary
     * @param $id
     * @return object
     */
    public function fetch($id)
    {
        $beneficiary = Http::withToken($this->secretKey)->get(
            $this->baseUrl . '/beneficiaries/' . $id
        )->json();

        return $beneficiary;
    }




    /**
     * Delete A Beneficiary
     * @param $id
     * @return object
     */
    public function destroy($id)
    {
        $beneficiary = Http::withToken($this->secretKey)->delete(
            $this->baseUrl . '/beneficiaries/' . $id
        )->json();

        return $beneficiary;
    }
}
