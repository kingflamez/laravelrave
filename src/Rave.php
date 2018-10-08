<?php

namespace KingFlamez\Rave;

use stdClass;
use Unirest\Request;
use Unirest\Request\Body;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Config;
use Illuminate\Http\Request as LaravelRequest;

/**
 * Flutterwave's Rave payment laravel package
 * @author Oluwole Adebiyi - Flamez <flamekeed@gmail.com>
 * @version 1.2
 **/

class Rave {

    protected $publicKey;
    protected $secretKey;
    protected $paymentMethod = 'both';
    protected $customLogo;
    protected $customTitle;
    protected $secretHash;
    protected $txref;
    protected $integrityHash;
    protected $env = 'staging';
    protected $transactionPrefix;
    protected $urls = [
        "live" => "https://api.ravepay.co",
        "others" => "https://ravesandboxapi.flutterwave.com",
    ];
    protected $baseUrl;
    protected $transactionData;
    protected $overrideTransactionReference;
    protected $verifyCount = 0;
    protected $request;
    protected $unirestRequest;
    protected $body;

    /**
     * Construct
     * @return object
     * */
    function __construct (LaravelRequest $request, Request $unirestRequest, Body $body) {
        $this->request = $request;
        $this->body = $body;
        $this->unirestRequest= $unirestRequest;
        $prefix = Config::get('rave.prefix');
        $overrideRefWithPrefix = false;

        $this->publicKey = Config::get('rave.publicKey');
        $this->secretKey = Config::get('rave.secretKey');
        $this->env = Config::get('rave.env');
        $this->customLogo = Config::get('rave.logo');
        $this->customTitle = Config::get('rave.title');
        $this->secretHash = Config::get('rave.secretHash');
        $this->transactionPrefix = $prefix.'_';
        $this->overrideTransactionReference = $overrideRefWithPrefix;


        Log::notice('Generating Reference Number....');
        if ($this->overrideTransactionReference) {
            $this->txref = $this->transactionPrefix;
        } else {
            $this->txref = uniqid($this->transactionPrefix);
        }
        Log::notice('Generated Reference Number....' . $this->txref);

        $this->baseUrl = $this->urls[($this->env === "live" ? "$this->env" : "others")];

        Log::notice('Rave Class Initializes....');
    }


    /********************************************************************
     ********************************************************************
     * Modal Payment Start
     ********************************************************************
     ********************************************************************/

    /**
     * Generates a checksum value for the information to be sent to the payment gateway
     * @return object
     * */
    public function createCheckSum($redirectURL){
        if ($this->request->payment_method) {
            $this->paymentMethod = $this->request->payment_method; // value can be card, account or both
        }

        if ($this->request->logo) {
            $this->customLogo = $this->request->logo; // This might not be included if you have it set in your .env file
        }

        if ($this->request->title) {
            $this->customTitle = $this->request->title; // This can be left blank if you have it set in your .env file
        }

        if ($this->request->ref) {
            $this->txref = $this->request->ref;
        }

        Log::notice('Generating Checksum....');
        $options = array(
            "PBFPubKey" => $this->publicKey,
            "amount" => $this->request->amount,
            "customer_email" => $this->request->email,
            "customer_firstname" => $this->request->firstname,
            "txref" => $this->txref,
            "payment_method" => $this->paymentMethod,
            "customer_lastname" => $this->request->lastname,
            "country" => $this->request->country,
            "currency" => $this->request->currency,
            "custom_description" => $this->request->description,
            "custom_logo" => $this->customLogo,
            "custom_title" => $this->customTitle,
            "customer_phone" => $this->request->phonenumber,
            "redirect_url" => $redirectURL,
            "hosted_payment" => 1
        );

        if (!empty($this->request->paymentplan)) {
            $options["payment_plan"] = $this->request->paymentplan;
        }

        ksort($options);

        $this->transactionData = $options;

        $hashedPayload = '';

        foreach($options as $value){
            $hashedPayload .= $value;
        }

        $completeHash = $hashedPayload.$this->secretKey;

        $this->integrityHash = hash('sha256', $completeHash);
        return $this;
    }



    /**
     * Generates the final json to be used in configuring the payment call to the rave payment gateway
     * @return string
     * */
    public function initialize($redirectURL)
    {
        $meta = array();
        if (!empty($this->request->metadata)) {
            $meta = json_decode($this->request->metadata, true);
        }

        $this->createCheckSum($redirectURL);
        $this->transactionData = array_merge($this->transactionData, array('data-integrity_hash' => $this->integrityHash), array('meta' => $meta));

        $json = json_encode($this->transactionData);
        echo '<html>';
        echo '<body>';
        echo '<center>Proccessing...<br /><img style="height: 50px;" src="https://media.giphy.com/media/swhRkVYLJDrCE/giphy.gif" /></center>';
        echo '<script type="text/javascript" src="' . $this->baseUrl . '/flwv3-pug/getpaidx/api/flwpbf-inline.js"></script>';
        echo '<script>';
        echo 'document.addEventListener("DOMContentLoaded", function(event) {';
        echo 'var data = JSON.parse(\'' . $json . '\');';
        echo 'getpaidSetup(data);';
        echo '});';
        echo '</script>';
        echo '</body>';
        echo '</html>';

        return $json;
    }

    /**
     * Handle canceled payments with this method
     * @param string $referenceNumber This should be the reference number of the transaction that was canceled
     * @return mixed
     * */
    public function paymentCanceled($referenceNumber, $data)
    {
        $this->txref = $referenceNumber;
        if (request()->cancelled) {
            $cancelledResponse = '{"status": "cancelled" , "message": "Customer cancelled the transaction", "data":{ "status": "cancelled", "txRef" :"' . $this->txref . '"}}';
            $resp = json_decode($cancelledResponse);
            return $resp;
        } else {
            return $data;
        }
    }

    /********************************************************************
     ********************************************************************
     * Modal Payment Ends
     ********************************************************************
     ********************************************************************/


    /********************************************************************
     ********************************************************************
     * Miscs Start
     ********************************************************************
     ********************************************************************/


    /**
     * Refunds
     * @return object
     * */
    public function refund()
    {
        $data = array(
            'ref' => $this->request->ref,
            'seckey' => $this->secretKey
        );
        
        if (!empty($this->request->amount)) {
            $data = array(
                'amount' => $this->request->amount,
                'ref' => $this->request->ref,
                'seckey' => $this->secretKey
            );
        }
        
        // make request to endpoint using unirest.
        $headers = array('Content-Type' => 'application/json');
        $body = $this->body->json($data);
        $url = $this->baseUrl . '/gpx/merchant/transactions/refund';

        // Make `POST` request and handle response with unirest
        $response = $this->unirestRequest->post($url, $headers, $body);

        //check the status is success
        if ($response->body && $response->body->status === "success") {
            return $response->body;
        }

        return $response->body;
    }


    /**
     * Exchange Rates
     * @return object
     * */
    public function exchangeRates()
    {
        $data = array(
            'origin_currency' => $this->request->origin_currency,
            'destination_currency' => $this->request->destination_currency,
            'seckey' => $this->secretKey
        );
        
        if (!empty($this->request->amount)) {
            $data = array(
                'origin_currency' => $this->request->origin_currency,
                'destination_currency' => $this->request->destination_currency,
                'amount' => $this->request->amount,
                'seckey' => $this->secretKey
            );
        }
        
        // make request to endpoint using unirest.
        $headers = array('Content-Type' => 'application/json');
        $body = $this->body->json($data);
        $url = $this->baseUrl . '/gpx/merchant/transactions/refund';

        // Make `POST` request and handle response with unirest
        $response = $this->unirestRequest->post($url, $headers, $body);

        //check the status is success
        if ($response->body && $response->body->status === "success") {
            return $response->body;
        }

        return $response->body;
    }


    /**
     * Receive Webhook
     * @param $secrethash 
     * @return object
     * */
    public function receiveWebhook()
    {
        // Retrieve the request's body
        $body = @file_get_contents("php://input");

        // retrieve the signature sent in the reques header's.
        $signature = (isset($_SERVER['verif-hash']) ? $_SERVER['verif-hash'] : '');

        /* It is a good idea to log all events received. Add code *
        * here to log the signature and body to db or file       */

        if (!$signature) {
            // only a post with rave signature header gets our attention
            exit();
        }

        // Store the same signature on your server as an env variable and check against what was sent in the headers
        $local_signature = $this->secretHash;

        // confirm the event's signature
        if( $signature !== $local_signature ){
        // silently forget this ever happened
        exit();
        }

        http_response_code(200); // PHP 5.4 or greater
        // parse event (which is json string) as object
        // Give value to your customer but don't give any output
        // Remember that this is a call from rave's servers and 
        // Your customer is not seeing the response here at all
        $response = json_decode($body);
        return $response;
    }


    /**
     * Used for KYC to validate bvn
     * @param string $bvn the customers bvn
     * @return object
     * */
    public function validateBVN($bvn)
    {
        $url = $this->baseUrl . '/kyc/bvn/'.$bvn.'?seckey=' . $this->secretKey;
        $headers = array('Content-Type' => 'application/json');

        // Make `GET` request and handle response with unirest
        $response = $this->unirestRequest->get($url, $headers);

        //check the status is success
        if ($response->body) {
            return $response->body;
        }

        return $response;
    }


    /********************************************************************
     ********************************************************************
     * Miscs End
     ********************************************************************
     ********************************************************************/



    /********************************************************************
     ********************************************************************
     * Charges Start
     ********************************************************************
     ********************************************************************/

    /**
     * Verifies a transaction with the transaction reference
     * @param string $referenceNumber This should be the reference number of the transaction you want to verify
     * @return object
     * */
    public function verifyTransaction($referenceNumber)
    {
        $this->txref = $referenceNumber;
        $this->verifyCount++;
        Log::notice('Verifying Transaction....' . $this->txref);

        $data = array(
            'txref' => $this->txref,
            'SECKEY' => $this->secretKey,
            'last_attempt' => '1'
            // 'only_successful' => '1'
        );
        
        // make request to endpoint using unirest.
        $headers = array('Content-Type' => 'application/json');
        $body = $this->body->json($data);
        $url = $this->baseUrl . '/flwv3-pug/getpaidx/api/v2/verify';

        // Make `POST` request and handle response with unirest
        $response = $this->unirestRequest->post($url, $headers, $body);

        //check the status is success
        if ($response->body && $response->body->status === "success") {
            return $response->body;
        } else {
            if ($this->verifyCount > 4) {
                $this->paymentCanceled($this->txref, $response->body);
            } else {
                sleep(3);
                $this->verifyTransaction($this->txref);
            }
        }
    }

    /********************************************************************
     ********************************************************************
     * Charges End
     ********************************************************************
     ********************************************************************/
    

    /********************************************************************
     ********************************************************************
     * Payment Plans Start
     ********************************************************************
     ********************************************************************/

    /**
     * Creates a payment plan
     * @return object
     * */
    public function createPaymentPlan()
    {

        $data = array(
            'amount' => $this->request->amount,
            'interval' => $this->request->interval,
            'name' => $this->request->name,
            'duration' => $this->request->duration,
            'seckey' => $this->secretKey
        );
        
        // make request to endpoint using unirest.
        $headers = array('Content-Type' => 'application/json');
        $body = $this->body->json($data);
        $url = $this->baseUrl . '/v2/gpx/paymentplans/create';

        // Make `POST` request and handle response with unirest
        $response = $this->unirestRequest->post($url, $headers, $body);

        //check the status is success
        if ($response->body && $response->body->status === "success") {
            return $response->body;
        }

        return $response->body;
    }

    /**
     * Edits a payment plan
     * @param id $id This is the payment plan id
     * @return object
     * */
    public function editPaymentPlan($id)
    {

        $data = array(
            'name' => $this->request->name,
            'status' => $this->request->status,
            'seckey' => $this->secretKey
        );
        
        // make request to endpoint using unirest.
        $headers = array('Content-Type' => 'application/json');
        $body = $this->body->json($data);
        $url = $this->baseUrl . '/v2/gpx/paymentplans/'.$id.'/edit';

        // Make `POST` request and handle response with unirest
        $response = $this->unirestRequest->post($url, $headers, $body);

        //check the status is success
        if ($response->body && $response->body->status === "success") {
            return $response->body;
        }

        return $response->body;
    }

    /**
     * Cancels a payment plan
     * @param id $id This is the payment plan id
     * @return object
     * */
    public function cancelPaymentPlan($id)
    {

        $data = array(
            'seckey' => $this->secretKey
        );
        
        // make request to endpoint using unirest.
        $headers = array('Content-Type' => 'application/json');
        $body = $this->body->json($data);
        $url = $this->baseUrl . '/v2/gpx/paymentplans/'.$id.'/cancel';

        // Make `POST` request and handle response with unirest
        $response = $this->unirestRequest->post($url, $headers, $body);

        //check the status is success
        if ($response->body && $response->body->status === "success") {
            return $response->body;
        }

        return $response->body;
    }


    /**
     * List all the payment plans
     * @return object
     * */
    public function listPaymentPlans()
    {
        $url = $this->baseUrl . '/v2/gpx/paymentplans/query?seckey=' . $this->secretKey;
        $headers = array('Content-Type' => 'application/json');

        // Make `GET` request and handle response with unirest
        $response = $this->unirestRequest->get($url, $headers);

        //check the status is success
        if ($response->body) {
            return $response->body;
        }

        return $response;
    }

    /**
     * Fetches a payment plan
     * @return object
     * */
    public function fetchPaymentPlan($id='', $q='')
    {
        $url = $this->baseUrl . '/v2/gpx/paymentplans/query?seckey=' . $this->secretKey . '&q='.$q.'&id='.$id;
        $headers = array('Content-Type' => 'application/json');

        // Make `GET` request and handle response with unirest
        $response = $this->unirestRequest->get($url, $headers);

        //check the status is success
        if ($response->body) {
            return $response->body;
        }

        return $response;
    }


    /********************************************************************
     ********************************************************************
     * Payment Plans End
     ********************************************************************
     ********************************************************************/

    /********************************************************************
     ********************************************************************
     * Subscriptions Plans Start
     ********************************************************************
     ********************************************************************/

    /**
     * List all the subscriptions
     * @return object
     * */
    public function listSubscriptions()
    {
        $url = $this->baseUrl . '/v2/gpx/subscriptions/query?seckey=' . $this->secretKey;
        $headers = array('Content-Type' => 'application/json');

        // Make `GET` request and handle response with unirest
        $response = $this->unirestRequest->get($url, $headers);

        //check the status is success
        if ($response->body) {
            return $response->body;
        }

        return $response;
    }


    /**
     * Fetches a subscription
     * @return object
     * */
    public function fetchSubscription($id = '', $email = '')
    {
        $url = $this->baseUrl . '/v2/gpx/subscriptions/query?seckey=' . $this->secretKey . '&email=' . $email . '&id=' . $id;
        $headers = array('Content-Type' => 'application/json');

        // Make `GET` request and handle response with unirest
        $response = $this->unirestRequest->get($url, $headers);

        //check the status is success
        if ($response->body) {
            return $response->body;
        }

        return $response;
    }

    /**
     * Cancels a subscription
     * @param id $id This is the subscription id
     * @return object
     * */
    public function cancelSubscription($id)
    {

        $data = array(
            'seckey' => $this->secretKey
        );
        
        // make request to endpoint using unirest.
        $headers = array('Content-Type' => 'application/json');
        $body = $this->body->json($data);
        $url = $this->baseUrl . '/v2/gpx/subscriptions/' . $id . '/cancel';

        // Make `POST` request and handle response with unirest
        $response = $this->unirestRequest->post($url, $headers, $body);

        //check the status is success
        if ($response->body && $response->body->status === "success") {
            return $response->body;
        }

        return $response->body;
    }

    /**
     * Activates a subscription
     * @param id $id This is the subscription id
     * @return object
     * */
    public function activateSubscription($id)
    {

        $data = array(
            'seckey' => $this->secretKey
        );
        
        // make request to endpoint using unirest.
        $headers = array('Content-Type' => 'application/json');
        $body = $this->body->json($data);
        $url = $this->baseUrl . '/v2/gpx/subscriptions/' . $id . '/activate';

        // Make `POST` request and handle response with unirest
        $response = $this->unirestRequest->post($url, $headers, $body);

        //check the status is success
        if ($response->body && $response->body->status === "success") {
            return $response->body;
        }

        return $response->body;
    }

    /********************************************************************
     ********************************************************************
     * Subscriptions Plans End
     ********************************************************************
     ********************************************************************/



    /********************************************************************
     ********************************************************************
     * Sub acccount Begin
     ********************************************************************
     ********************************************************************/
      /**

     * Create Sub Account
     * @return object
     * */

     public function createSubAccount()
      {
        $data = array(
            'account_bank' => $this->request->account_bank,
            'account_number' => $this->request->account_number,
            'business_name' => $this->request->business_name,
            'business_email' => $this->request->business_email,
            'business_contact' => $this->request->business_contact,
            'business_contact_mobile' => $this->request->business_contact_mobile,
            'business_mobile' => $this->request->business_mobile,
            'meta' => $this->meta,
            'seckey' => $this->seckey,
            'split_type' => $this->request->slit_type,
            'split_value' => $this->request->split_value
        );

         // make request to endpoint using unirest.
         $headers = array('Content-Type' => 'application/json');
         $body = $this->body->json($data);
         $url = $this->baseUrl . '/v2/gpx/subaccounts/create';
         // Make `POST` request and handle response with unirest
        $response = $this->unirestRequest->post($url, $headers, $body);

        //check the status is success
        if ($response->body && $response->body->status === "success") {
            return $response->body;
        }

        return $response->body;

     }

     /* List all the sub accounts
     * @return object
     * */
    public function listSubAccount()
    {
        $url = $this->baseUrl . '/v2/gpx/subaccounts/?seckey='. $this->secretKey;
        $headers = array('Content-Type' => 'application/json');

        // Make `GET` request and handle response with unirest
        $response = $this->unirestRequest->get($url, $headers);

        //check the status is success
        if ($response->body) {
            return $response->body;
        }

        return $response;
    }
        /**
     * Fetches a sub account
     * @return object
     * */
    public function fetchSubAccount()
    {
        $id = $this->request->id;
        $url = $this->baseUrl . '/v2/gpx/subaccounts/get/'.$id.'?seckey=' . $this->secretKey;
        $headers = array('Content-Type' => 'application/json');

        // Make `GET` request and handle response with unirest
        $response = $this->unirestRequest->get($url, $headers);

        //check the status is success
        if ($response->body) {
            return $response->body;
        }

        return $response;
    }


     /********************************************************************
     ********************************************************************
     * Sub acccount Ends
     ********************************************************************
     ********************************************************************/


      /********************************************************************
     ********************************************************************
     * Transfer Begin
     ********************************************************************
     ********************************************************************/
      /**

     * Initialize a transfer
     * @return object
     * */

}