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
        'live_base_url' => 'https://ravesandboxapi.flutterwave.com',
        'test_base_url' => 'https://ravesandboxapi.flutterwave.com',

        'transaction_verification' => 'flwv3-pug/getpaidx/api/xrequery',
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
                'tx_ref' => $reference
            ],
            'headers' => [
                'Content-Type' => 'application/json'
            ]
        ]);

        return $response->getBody()->getContents();
    }
}