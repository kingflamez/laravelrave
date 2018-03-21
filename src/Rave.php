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
 * @version 1.0
 **/

class Rave {
    protected $publicKey;
    protected $secretKey;
    protected $amount;
    protected $paymentMethod = 'both';
    protected $customDescription;
    protected $customLogo;
    protected $customTitle;
    protected $country;
    protected $currency;
    protected $customerEmail;
    protected $customerFirstname;
    protected $customerLastname;
    protected $customerPhone;
    protected $txref;
    protected $integrityHash;
    protected $payButtonText = 'Make Payment';
    protected $redirectUrl;
    protected $meta = array();
    protected $env = 'staging';
    protected $transactionPrefix;
    protected $handler;
    protected $urls = [
        "live" => "https://api.ravepay.co",
        "others" => "https://rave-api-v2.herokuapp.com",
    ];
    protected $baseUrl;
    protected $transactionData;
    protected $overrideTransactionReference;
    protected $requeryCount = 0;
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
        $this->transactionPrefix = $prefix.'_';
        $this->overrideTransactionReference = $overrideRefWithPrefix;

        $this->createReferenceNumber();

        $this->baseUrl = $this->urls[($this->env === "live" ? "$this->env" : "others")];

        Log::notice('Rave Class Initializes....');
    }

    /**
     * Generates a checksum value for the information to be sent to the payment gateway
     * @return object
     * */
    public function createCheckSum(){
        Log::notice('Generating Checksum....');
        $options = array(
            "PBFPubKey" => $this->publicKey,
            "amount" => $this->amount,
            "customer_email" => $this->customerEmail,
            "customer_firstname" => $this->customerFirstname,
            "txref" => $this->txref,
            "payment_method" => $this->paymentMethod,
            "customer_lastname" => $this->customerLastname,
            "country" => $this->country,
            "currency" => $this->currency,
            "custom_description" => $this->customDescription,
            "custom_logo" => $this->customLogo,
            "custom_title" => $this->customTitle,
            "customer_phone" => $this->customerPhone,
            "pay_button_text" => $this->payButtonText,
            "redirect_url" => $this->redirectUrl,
            "hosted_payment" => 1
        );

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
     * Generates a transaction reference number for the transactions
     * @return object
     * */
    public function createReferenceNumber(){
        Log::notice('Generating Reference Number....');
        if($this->overrideTransactionReference){
            $this->txref = $this->transactionPrefix;
        }else{
            $this->txref = uniqid($this->transactionPrefix);
        }
        Log::notice('Generated Reference Number....'.$this->txref);
        return $this;
    }

    /**
     * gets the current transaction reference number for the transaction
     * @return string
     * */
    public function getReferenceNumber(){
        return $this->txref;
    }

    /**
     * Sets the public and secret key
     * @param string $publicKey Your Rave publicKey. Sign up on https://rave.flutterwave.com to get one from your settings page
     * @param string $secretKey Your Rave secretKey. Sign up on https://rave.flutterwave.com to get one from your settings page
     * @return object
     * */
    public function setKeys(string $publicKey, string $secretKey)
    {
        $this->publicKey = $publicKey;
        $this->secretKey = $secretKey;
        return $this;
    }

    /**
     * Set the environment
     * @param string $env This can either be 'staging' or 'live'
     * @return object
     * */
    public function setEnvironment($env)
    {
        $this->env = $env;
        return $this;
    }

    /**
     * Set the environment
     * @param string $prefix This is added to the front of your transaction reference numbers
     * @param boolean $overrideRefWithPrefix Set this parameter to true to use your prefix as the transaction reference
     * @return object
     * */
    public function setPrefix($prefix, $overrideRefWithPrefix = false)
    {
        $this->transactionPrefix = $overrideRefWithPrefix ? $prefix : $prefix.'_';
        $this->overrideTransactionReference = $overrideRefWithPrefix;
        $this->createReferenceNumber();
        return $this;
    }

    /**
     * Sets the transaction amount
     *
     * @param mixed $amount Transaction amount (could be integer or double)
     * @return object
     * */
    public function setAmount($amount){
        $this->amount = $amount;
        return $this;
    }

    /**
     * gets the transaction amount
     * @return string
     * */
    public function getAmount(){
        return $this->amount;
    }

    /**
     * Sets the allowed payment methods
     * @param string $paymentMethod The allowed payment methods. Can be card, account or both
     * @return object
     * */
    public function setPaymentMethod($paymentMethod){
        $this->paymentMethod = $paymentMethod;
        return $this;
    }

    /**
     * gets the allowed payment methods
     * @return string
     * */
    public function getPaymentMethod(){
        return $this->paymentMethod;
    }

    /**
     * Sets the transaction description
     * @param string $customDescription The description of the transaction
     * @return object
     * */
    public function setDescription ($customDescription) {
        $this->customDescription = $customDescription;
        return $this;
    }

    /**
     * gets the transaction description
     * @return string
     * */
    public function getDescription(){
        return $this->customDescription;
    }

    /**
     * Sets the payment page logo
     * @param string $customLogo Your Logo
     * @return object
     * */
    public function setLogo($customLogo){
        $this->customLogo = $customLogo;
        return $this;
    }

    /**
     * gets the payment page logo
     * @return string
     * */
    public function getLogo(){
        return $this->customLogo;
    }

    /**
     * Sets the payment page title
     * @param string $customTitle A title for the payment. It can be the product name, your business name or anything short and descriptive
     * @return object
     * */
    public function setTitle($customTitle){
        $this->customTitle = $customTitle;
        return $this;
    }

    /**
     * gets the payment page title
     * @return string
     * */
    public function getTitle(){
        return $this->customTitle;
    }

    /**
     * Sets transaction country
     * @param string $country The transaction country. Can be NG, US, KE, GH and ZA
     * @return object
     * */
    public function setCountry($country){
        $this->country = $country;
        return $this;
    }

    /**
     * gets the transaction country
     * @return string
     * */
    public function getCountry(){
        return $this->country;
    }

    /**
     * Sets the transaction currency
     * @param string $currency The transaction currency. Can be NGN, GHS, KES, ZAR, USD, EUR and GBP
     * @return object
     * */
    public function setCurrency($currency){
        $this->currency = $currency;
        return $this;
    }

    /**
     * gets the transaction currency
     * @return string
     * */
    public function getCurrency(){
        return $this->currency;
    }

    /**
     * Sets the customer email
     * @param string $customerEmail This is the paying customer's email
     * @return object
     * */
    public function setEmail($customerEmail){
        $this->customerEmail = $customerEmail;
        return $this;
    }

    /**
     * gets the customer email
     * @return string
     * */
    public function getEmail(){
        return $this->customerEmail;
    }

    /**
     * Sets the customer firstname
     * @param string $customerFirstname This is the paying customer's firstname
     * @return object
     * */
    public function setFirstname($customerFirstname){
        $this->customerFirstname = $customerFirstname;
        return $this;
    }

    /**
     * gets the customer firstname
     * @return string
     * */
    public function getFirstname(){
        return $this->customerFirstname;
    }

    /**
     * Sets the customer lastname
     * @param string $customerLastname This is the paying customer's lastname
     * @return object
     * */
    public function setLastname($customerLastname){
        $this->customerLastname = $customerLastname;
        return $this;
    }

    /**
     * gets the customer lastname
     * @return string
     * */
    public function getLastname(){
        return $this->customerLastname;
    }

    /**
     * Sets the customer phonenumber
     * @param string $customerPhone This is the paying customer's phonenumber
     * @return object
     * */
    public function setPhoneNumber($customerPhone){
        $this->customerPhone = $customerPhone;
        return $this;
    }

    /**
     * gets the customer phonenumber
     * @return string
     * */
    public function getPhoneNumber(){
        return $this->customerPhone;
    }

    /**
     * Sets the payment page button text
     * @param string $payButtonText This is the text that should appear on the payment button on the Rave payment gateway.
     * @return object
     * */
    public function setPayButtonText($payButtonText){
        $this->payButtonText = $payButtonText;
        return $this;
    }

    /**
     * gets payment page button text
     * @return string
     * */
    public function getPayButtonText(){
        return $this->payButtonText;
    }

    /**
     * Sets the transaction redirect url
     * @param string $redirectUrl This is where the Rave payment gateway will redirect to after completing a payment
     * @return object
     * */
    public function setRedirectUrl($redirectUrl){
        $this->redirectUrl = $redirectUrl;
        return $this;
    }

    /**
     * gets the transaction redirect url
     * @return string
     * */
    public function getRedirectUrl(){
        return $this->redirectUrl;
    }

    /**
     * Sets the transaction meta data. Can be called multiple time to set multiple meta data
     * @param array $meta This are the other information you will like to store with the transaction. It is a key => value array. eg. PNR for airlines, product colour or attributes. Example. array('name' => 'femi')
     * @return object
     * */
    public function setMetaData($meta){
        array_push($this->meta, $meta);
        return $this;
    }

    /**
     * gets the transaction meta data
     * @return string
     * */
    public function getMetaData(){
        return $this->meta;
    }

    /**
     * Sets the data from the form
     * @param string $redirectUrl The URL it redirects too after a transaction
     * @return object
     * */
    public function setData($redirectURL)
    {
        $this->setAmount($this->request->amount)
            ->setDescription($this->request->description)
            ->setCountry($this->request->country)
            ->setCurrency($this->request->currency)
            ->setEmail($this->request->email)
            ->setFirstname($this->request->firstname)
            ->setLastname($this->request->lastname)
            ->setPhoneNumber($this->request->phonenumber)
            ->setPayButtonText($this->request->pay_button_text)
            ->setRedirectUrl($redirectURL);

        if ($this->request->payment_method) {
            $this->setPaymentMethod($this->request->payment_method); // value can be card, account or both
        }

        if ($this->request->logo) {
            $this->setLogo($this->request->logo); // This might not be included if you have it set in your .env file
        }

        if ($this->request->title) {
            $this->setTitle($this->request->title); // This can be left blank if you have it set in your .env file
        }

        return $this;
    }

    /**
     * Sets the event hooks for all available triggers
     * @param object $handler This is a class that implements the Event Handler Interface
     * @return object
     * */
    public function eventHandler(RaveEventHandlerInterface $handler){
        $this->handler = $handler;
        return $this;
    }

    /**
     * Requerys a previous transaction from the Rave payment gateway
     * @param string $referenceNumber This should be the reference number of the transaction you want to requery
     * @return mixed
     * */
    public function requeryTransaction($referenceNumber){
        $this->txref = $referenceNumber;
        $this->requeryCount++;
        Log::notice('Requerying Transaction....'.$this->txref);
        if(isset($this->handler)){
            $this->handler->onRequery($this->txref);
        }

        $data = array(
            'txref' => $this->txref,
            'SECKEY' => $this->secretKey,
            'last_attempt' => '1'
            // 'only_successful' => '1'
        );

        // make request to endpoint using unirest.
        $headers = array('Content-Type' => 'application/json');
        $body = $this->body->json($data);
        $url = $this->baseUrl.'/flwv3-pug/getpaidx/api/xrequery';

        // Make `POST` request and handle response with unirest
        $response = $this->unirestRequest->post($url, $headers, $body);

        //check the status is success
        if ($response->body && $response->body->status === "success") {
            if ($response->body && property_exists($response->body, "data")) {
                return $this->feedbackFromSource($response->body->data, $response->body->data->status);

            } else {
                // Handled an undecisive transaction. Probably timed out.
                Log::warning('Requeryed an undecisive transaction....'.json_encode($response->body));
                // I will requery again here. Just incase we have some devs that cannot setup a queue for requery. I don't like this.
                if ($this->requeryCount > 4) {
                    // Now you have to setup a queue by force. We couldn't get a status in 5 requeries.
                    if(isset($this->handler)){
                        $this->handler->onTimeout($this->txref, $response->body);
                    }else{
                        return $response->body;
                    }
                } else {
                    Log::notice('delaying next requery for 3 seconds');
                    Log::notice('Now retrying requery...');
                    $this->requeryTransaction($this->txref);
                }
            }
        }else{
            Log::warning('Requery call returned error for transaction reference.....'.json_encode($response->body).'Transaction Reference: '. $this->txref);
            // Handle Requery Error
            if(isset($this->handler)){
                $this->handler->onRequeryError($response->body);
            }else{
                return $response->body;
            }
        }
        return $this;
    }

    /**
     * Generates the final json to be used in configuring the payment call to the rave payment gateway
     * @return string
     * */
    public function initialize($redirectURL){
        $this->setData($redirectURL);

        if (!empty($this->request->metadata)) {
           $this->meta = json_decode($this->request->metadata, true);
        }

        $this->createCheckSum();
        $this->transactionData = array_merge($this->transactionData, array('integrity_hash' => $this->integrityHash), array('meta' => $this->meta));

        if(isset($this->handler)){
            $this->handler->onInit($this->transactionData);
        }

        $json = json_encode($this->transactionData);
        echo '<html>';
        echo '<body>';
        echo '<center>Proccessing...<br /><img style="height: 50px;" src="https://media.giphy.com/media/swhRkVYLJDrCE/giphy.gif" /></center>';
        echo '<script type="text/javascript" src="'.$this->baseUrl.'/flwv3-pug/getpaidx/api/flwpbf-inline.js"></script>';
        echo '<script>';
        echo 'document.addEventListener("DOMContentLoaded", function(event) {';
        echo 'var data = JSON.parse(\''.$json.'\');';
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
    public function paymentCanceled($referenceNumber) {
        $this->txref = $referenceNumber;
        Log::notice('Payment was canceled by user..'.$this->txref);
        if(isset($this->handler)){
            $this->handler->onCancel($this->txref);
        } else {
            $collection = collect(['status' => "canceled",
                                'txref' => $this->txref ]);
            return $collection->toJson();
        }
        return $this;
    }

    /**
     * Requery action function .
     *
     * @param  stdClass $data
     * @param  string   $handler Handler to call.
     * @param  string   $log     Log message.
     * @param  string   $logType Log type.
     * @return stdClass | \KingFlamez\Rave\Rave
     */
    protected function requeryAction (stdClass $data, $handler, $log, $logType = "notice") {

        Log::{$logType}($log.json_encode($data));

        if(isset($this->handler)) {
            $this->handler->{"on".ucfirst($handler)}($data);
        }else{
            return $data;
        }

        return $this;
    }

    /**
     * Feedback for requery.
     *
     * @param  stdClass $response
     * @return stdClass | \KingFlamez\Rave\Rave
     */
    protected function feedbackFromSource (stdClass $data, $status) {
        $feedback = [
            "successful" => ["successful", "Requeryed a successful transaction..."],
            "failed" => ["failure", "Requeryed a failed transaction..."],
            "error" => ["requeryError", "Requery call returned error for transaction reference..."],
            "timeout" => ["timeout", "Requeryed an undecisive transaction..."],
        ];

        return $this->requeryAction(
            $data,
            $feedback[$status][0],
            $feedback[$status][1]
        );
    }
}

// silencio es dorado
