<?php

namespace Remade\RaveWidget;

use Carbon\Carbon;
use Remade\RaveWidget\Core\Configuration;
use Remade\RaveWidget\Core\Persitence\Database;
use Remade\RaveWidget\Core\Requests;

class Widget
{
    /**
     * Widget Configuration property
     *
     * @var Configuration
     */
    protected $configuration;

    /**
     * Widget constructor.
     * @param $configuration
     */
    public function __construct($configuration)
    {
        if($configuration instanceof Configuration)
        {
            $this->configuration = $configuration;
        }
        else if (is_array($configuration))
        {
            $this->configuration = new Configuration($configuration);
        }
    }

    /**
     * Retrieve present instance of widget's configuration
     *
     * @return Configuration
     */
    public function configuration()
    {
        return $this->configuration;
    }

    /**
     * Provide Data fo start a Rave transaction
     *
     * @param $amount
     * @param null $reference
     * @param array $meta
     * @return array
     */
    public function makePaymentRequest($amount, $reference = null, $meta = [])
    {
        $reference = empty($reference)? "TXREF-".time() : $reference;
        $currency = "NGN";

        //Create payload
        $payload = [
            "PBFPubKey" => $this->configuration->get('rave.public_key'),
            "customer_email" => "user@example.com",
            "amount" => $amount,
            "customer_phone" => "234099940409",
            "currency" => $currency,
            "payment_method" => "both",
            "txref" => $reference,
            "meta" => [$meta],
        ];

        //Persist Request
        $this->persistence()->saveRavePaymentRequest($this->configuration->get('widget.payment_request_table_name'), [
            'environment' => $this->configuration->get('rave.environment'),
            'currency' => $currency,
            'reference' => $reference,
            'amount' => $amount,
            'status_text' => 'waiting',
            'request_data' => json_encode($payload),
            'created_at' => Carbon::now()->toDateTimeString(),
            'updated_at' => Carbon::now()->toDateTimeString(),
        ]);


        //Return
        return [
            'data' => $payload,
            'meta' => [
                'rave_script' => $this->request()->getBaseUrl().'/flwv3-pug/getpaidx/api/flwpbf-inline.js',
                'environment' => $this->configuration->get('rave.environment')
            ]
        ];
    }

    /**
     * Re-query Transaction after response from front end or callback
     *
     * @param $reference
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function verifyTransaction($reference)
    {
        //Get transaction
        $response = $this->request()->requeryTransaction($reference);

        //save transaction response and update status
        $this->persistence()->updateRavePaymentRequest($this->configuration->get('widget.payment_request_table_name'), [
            'response_data' => $response
        ],  $reference);

        //return transaction
        return $response;
    }

    /**
     * Get a Transaction using the transaction's reference
     *
     * @param $reference
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function getTransaction($reference)
    {
        //get request
        return $this->request()->requeryTransaction($reference);
    }

    /**
     * Return instance of Core Requests
     *
     * @return Requests
     */
    protected function request()
    {
        $configuration = $this->configuration;

        return new Requests(
            $configuration->get('rave.environment'),
            $configuration->get('rave.public_key'),
            $configuration->get('rave.secret_key')
        );
    }

    /**
     * Return persistence instance
     *
     * @return Database
     */
    protected function persistence()
    {
        $configuration = $this->configuration;

        return new Database(
            $configuration->get('database.database_type'),
            $configuration->get('database.database_name'),
            $configuration->get('database.server'),
            $configuration->get('database.username'),
            $configuration->get('database.password')
        );
    }

}
