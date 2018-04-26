<?php

namespace Remade\RaveWidget\Core;

class Payment{

    protected $amount;
    protected $paymentMethod = "both";
    protected $customDescription;
    protected $customLogo;
    protected $customTitle;
    protected $country;
    protected $currency = "NGN";
    protected $customerEmail;
    protected $customerFirstname;
    protected $customerLastname;
    protected $customerPhone;
    protected $transactionReference;
    protected $integrityHash;
    protected $payButtonText = "Make Payment";
    protected $redirectUrl;
    protected $meta = array();

    /**
     * Payment constructor.
     *
     * @param $data
     * @throws \Exception
     */
    public function __construct($data = [])
    {
        $this->set($data);
        $this->transactionReference = uniqid();
    }

    /**
     * Set Payment Data
     *
     * @param $data
     * @return $this
     * @throws \Exception
     */
    public function set($data)
    {
        if(!is_array($data)){
            throw new \Exception('Parameter provided must be an array of payment properties');
        }

        foreach ($data as $key => $value)
        {
            if(property_exists($this, $key))
            {
                $this->{$key} = $value;
            }
        }
        return $this;
    }

    /**
     * Get transaction reference
     *
     * @return string
     */
    public function getTransactionReference()
    {
        return $this->transactionReference;
    }

    /**
     * Sets the transaction amount
     * @param integer $amount Transaction amount
     * @return object
     * */
    function setAmount($amount){
        $this->amount = $amount;
        return $this;
    }

    /**
     * gets the transaction amount
     * @return string
     * */
    function getAmount(){
        return $this->amount;
    }

    /**
     * Sets the allowed payment methods
     * @param string $paymentMethod The allowed payment methods. Can be card, account or both
     * @return object
     * */
    function setPaymentMethod($paymentMethod){
        $this->paymentMethod = $paymentMethod;
        return $this;
    }

    /**
     * gets the allowed payment methods
     * @return string
     * */
    function getPaymentMethod(){
        return $this->paymentMethod;
    }

    /**
     * Sets the transaction description
     * @param string $customDescription The description of the transaction
     * @return object
     * */
    function setDescription($customDescription){
        $this->customDescription = $customDescription;
        return $this;
    }

    /**
     * gets the transaction description
     * @return string
     * */
    function getDescription(){
        return $this->customDescription;
    }

    /**
     * Sets the payment page logo
     * @param string $customLogo Your Logo
     * @return object
     * */
    function setLogo($customLogo){
        $this->customLogo = $customLogo;
        return $this;
    }

    /**
     * gets the payment page logo
     * @return string
     * */
    function getLogo(){
        return $this->customLogo;
    }

    /**
     * Sets the payment page title
     * @param string $customTitle A title for the payment. It can be the product name, your business name or anything short and descriptive
     * @return object
     * */
    function setTitle($customTitle){
        $this->customTitle = $customTitle;
        return $this;
    }

    /**
     * gets the payment page title
     * @return string
     * */
    function getTitle(){
        return $this->customTitle;
    }

    /**
     * Sets transaction country
     * @param string $country The transaction country. Can be NG, US, KE, GH and ZA
     * @return object
     * */
    function setCountry($country){
        $this->country = $country;
        return $this;
    }

    /**
     * gets the transaction country
     * @return string
     * */
    function getCountry(){
        return $this->country;
    }

    /**
     * Sets the transaction currency
     * @param string $currency The transaction currency. Can be NGN, GHS, KES, ZAR, USD, EUR and GBP
     * @return object
     * */
    function setCurrency($currency){
        $this->currency = $currency;
        return $this;
    }

    /**
     * gets the transaction currency
     * @return string
     * */
    function getCurrency(){
        return $this->currency;
    }

    /**
     * Sets the customer email
     * @param string $customerEmail This is the paying customer's email
     * @return object
     * */
    function setEmail($customerEmail){
        $this->customerEmail = $customerEmail;
        return $this;
    }

    /**
     * gets the customer email
     * @return string
     * */
    function getEmail(){
        return $this->customerEmail;
    }

    /**
     * Sets the customer firstname
     * @param string $customerFirstname This is the paying customer's firstname
     * @return object
     * */
    function setFirstname($customerFirstname){
        $this->customerFirstname = $customerFirstname;
        return $this;
    }

    /**
     * gets the customer firstname
     *
     * @return string
     * */
    function getFirstname(){
        return $this->customerFirstname;
    }

    /**
     * Sets the customer lastname
     *
     * @param string $customerLastname This is the paying customer's lastname
     * @return object
     * */
    function setLastname($customerLastname){
        $this->customerLastname = $customerLastname;
        return $this;
    }

    /**
     * gets the customer lastname
     *
     * @return string
     * */
    function getLastname(){
        return $this->customerLastname;
    }

    /**
     * Sets the customer phonenumber
     *
     * @param string $customerPhone This is the paying customer's phonenumber
     * @return object
     * */
    function setPhoneNumber($customerPhone){
        $this->customerPhone = $customerPhone;
        return $this;
    }

    /**
     * gets the customer phonenumber
     *
     * @return string
     * */
    function getPhoneNumber(){
        return $this->customerPhone;
    }

    /**
     * Sets the payment page button text
     *
     * @param string $payButtonText This is the text that should appear on the payment button on the Rave payment gateway.
     * @return object
     * */
    function setPayButtonText($payButtonText){
        $this->payButtonText = $payButtonText;
        return $this;
    }

    /**
     * gets payment page button text
     *
     * @return string
     * */
    function getPayButtonText(){
        return $this->payButtonText;
    }

    /**
     * Sets the transaction redirect url
     * @param string $redirectUrl This is where the Rave payment gateway will redirect to after completing a payment
     * @return object
     * */
    function setRedirectUrl($redirectUrl){
        $this->redirectUrl = $redirectUrl;
        return $this;
    }

    /**
     * gets the transaction redirect url
     * @return string
     * */
    function getRedirectUrl(){
        return $this->redirectUrl;
    }

    /**
     * Sets the transaction meta data. Can be called multiple time to set multiple meta data
     *
     * @param array $meta This are the other information you will like to store with the transaction. It is a key => value array. eg. PNR for
     * airlines, product colour or attributes. Example. array('name' => 'femi')
     * @return object
     * */
    function setMetaData($meta){
        $this->meta = array_merge($this->meta, $meta);
        return $this;
    }

    /**
     * gets the transaction meta data
     *
     * @return array
     * */
    function getMetaData(){
        return $this->meta;
    }

    /**
     * Generates a checksum value for the information to be sent to the payment gateway
     *
     * @param $publicKey
     * @param $secretKey
     * @return $this
     */
    function createCheckSum($publicKey, $secretKey){
        $options = array(
            "PBFPubKey" => $publicKey,
            "amount" => $this->amount,
            "customer_email" => $this->customerEmail,
            "customer_firstname" => $this->customerFirstname,
            "txref" => $this->transactionReference,
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

        foreach($options as $key => $value){
            $hashedPayload .= $value;
        }
        $completeHash = $hashedPayload.$secretKey;
        $hash = hash('sha256', $completeHash);

        $this->integrityHash = $hash;
        return $this;
    }


}