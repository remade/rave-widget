<?php

namespace Remade\RaveWidget\Core;

use GuzzleHttp\Client;

class Requests
{
    protected $client;

    protected $publicKey;

    protected $secretKey;

    protected $environment;

    protected $endpoints = [
        'live_base_url' => 'exix',
        'test_base_url' => 'https://ravesandboxapi.flutterwave.com',

        'transaction_verification' => 'flwv3-pug/getpaidx/api/xrequery',
        'hosted_pay' => 'flwv3-pug/getpaidx/api/v2/hosted/pay',
    ];

    /**
     * Requests constructor.
     *
     * @param $environment
     * @param $publicKey
     * @param $secretKey
     */
    public function __construct($environment, $publicKey, $secretKey)
    {
        $this->enviroment = $environment == 'live'? 'live' : 'test';
        $this->publicKey = $publicKey;
        $this->secretKey = $secretKey;

        $baseUri = $this->environment == 'live'?  $this->endpoints['live_base_url'] : $this->endpoints['test_base_url'];
        $this->client = new Client([
            'base_uri' => $baseUri
        ]);
    }

    /**
     * Get Base Url
     *
     * @return mixed
     */
    public function getBaseUrl()
    {
        return $this->environment == 'live'?  $this->endpoints['live_base_url'] : $this->endpoints['test_base_url'];
    }

    /**
     * @param $reference
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function requeryTransaction($reference)
    {
        $response = $this->client->post($this->endpoints['transaction_verification'], [
            'json' => [
                'SECKEY' => $this->secretKey,
                'txref' => $reference
            ],
            'headers' => [
                'Content-Type' => 'application/json'
            ]
        ]);

        return json_decode($response->getBody()->getContents());
    }

    /**
     * Get link for hosted payment
     *
     * @param $payload
     * @return string
     */
    public function hostedPay($payload)
    {
        $response = $this->client->post($this->endpoints['hosted_pay'], [
            'json' => $payload,
            'headers' => [
                'Content-Type' => 'application/json'
            ]
        ]);

        return json_decode($response->getBody()->getContents());
    }
}