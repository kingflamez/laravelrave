<?php

namespace KingFlamez\Rave\Helpers;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

/**
 * Flutterwave's Rave payment laravel package
 * @author Oluwole Adebiyi - Flamez <flamekeed@gmail.com>
 * @version 3
 **/
class Banks
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
     * Get Nigerian Banks
     * @return object
     */
    public function nigeria()
    {
        $banks = Http::withToken($this->secretKey)->get(
            $this->baseUrl . '/banks/NG'
        )->json();


        // sort banks by name
        usort($banks['data'], function ($a, $b) {
            return strcmp($a['name'], $b['name']);
        });

        return $banks;
    }


    /**
     * Get Ghanaian Banks
     * @return object
     */
    public function ghana()
    {
        $banks = Http::withToken($this->secretKey)->get(
            $this->baseUrl . '/banks/GH'
        )->json();


        // sort banks by name
        usort($banks['data'], function ($a, $b) {
            return strcmp($a['name'], $b['name']);
        });

        return $banks;
    }


    /**
     * Get Kenyan Banks
     * @return object
     */
    public function kenya()
    {
        $banks = Http::withToken($this->secretKey)->get(
            $this->baseUrl . '/banks/KE'
        )->json();


        // sort banks by name
        usort($banks['data'], function ($a, $b) {
            return strcmp($a['name'], $b['name']);
        });

        return $banks;
    }


    /**
     * Get Ugandan Banks
     * @return object
     */
    public function uganda()
    {
        $banks = Http::withToken($this->secretKey)->get(
            $this->baseUrl . '/banks/UG'
        )->json();


        // sort banks by name
        usort($banks['data'], function ($a, $b) {
            return strcmp($a['name'], $b['name']);
        });

        return $banks;
    }


    /**
     * Get South African Banks
     * @return object
     */
    public function southAfrica()
    {
        $banks = Http::withToken($this->secretKey)->get(
            $this->baseUrl . '/banks/ZA'
        )->json();


        // sort banks by name
        usort($banks['data'], function ($a, $b) {
            return strcmp($a['name'], $b['name']);
        });

        return $banks;
    }


    /**
     * Get Tanzanian Banks
     * @return object
     */
    public function tanzania()
    {
        $banks = Http::withToken($this->secretKey)->get(
            $this->baseUrl . '/banks/TZ'
        )->json();


        // sort banks by name
        usort($banks['data'], function ($a, $b) {
            return strcmp($a['name'], $b['name']);
        });

        return $banks;
    }


    /**
     * Get Tanzanian Banks
     * @param bankId
     * @return object
     */
    public function branches($bankId)
    {
        $branches = Http::withToken($this->secretKey)->get(
            $this->baseUrl . '/banks/'.$bankId.'/branches'
        )->json();


        // sort banks by name
        // usort($banks['data'], function ($a, $b) {
        //     return strcmp($a['name'], $b['name']);
        // });

        return $branches;
    }
}
