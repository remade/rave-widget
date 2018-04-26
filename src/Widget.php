<?php

namespace Remade\RaveWidget;

use Remade\RaveWidget\Core\Configuration;
use Remade\RaveWidget\Core\Events\EventHandler;
use Remade\RaveWidget\Core\Payment;
use Remade\RaveWidget\Core\Persitence\Database;
use Remade\RaveWidget\Core\Requests;
use Cake\Event\Event;
use Cake\Event\EventDispatcherTrait;

class Widget
{
    /**
     * Widget Configuration property
     *
     * @var $configuration
     */
    protected $configuration;

    /**
     * Widget Payment Object
     *
     * @var $payment
     */
    protected $payment;

    /**
     * Number of time to check transaction
     *
     * @var $requeryCount
     */
    protected $requeryCount;



    use EventDispatcherTrait;

    /**
     * Widget constructor.
     *
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

        $this->registerEventHandlers();
    }

    /**
     * Return the Widget instance.
     *
     * @return $this
     */
    public function instance()
    {
        return $this;
    }

    /**
     * Set Payment parameters for widget;
     *
     * @param Payment $payment
     * @return $this
     */
    public function setPayment(Payment $payment)
    {
        $this->payment = $payment;
        return $this;
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
     * Return instance of Core Requests
     *
     * @return Requests
     */
    public function request()
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
    public function persistence()
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


    /**
     * Make a payment request
     *
     * @param string $render
     *
     * @return array|string
     *
     * @throws \Exception
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function makePaymentRequest($render = 'rave_host')
    {
        $payment = $this->payment();

        //Create payload
        $payment_properties = [
            "PBFPubKey" => $this->configuration->get('rave.public_key'),
            "customer_email" => $payment->getEmail(),
            "amount" => $payment->getAmount(),
            "customer_phone" => $payment->getPhoneNumber(),
            "currency" => $payment->getCurrency(),
            "payment_method" => $payment->getPaymentMethod(),
            "txref" => $payment->getTransactionReference(),
        ];

        if(!empty($payment->getMetaData()))
        {
            $meta = [];
            foreach ($payment->getMetaData() as $key=>$value){
                $meta[] = (object) ['metaname' => $key, 'metavalue' =>$value];
            }
            $payment_properties['meta'] = json_encode($meta);
        }

        $payment_properties = array_filter($payment_properties, function ($value){
            return !empty($value);
        });

        $payload = [
            'data' => $payment_properties,
            'meta' => [
                'rave_script' => $this->request()->getBaseUrl().'/flwv3-pug/getpaidx/api/flwpbf-inline.js',
                'environment' => $this->configuration->get('rave.environment')
            ]
        ];

        //Fire Initialize event
        $event = new Event('widget.initialize', $this, [
            'payload_data' => $payload['data'], 'payment' => $payment
        ]);
        $this->getEventManager()->dispatch($event);

        //Return
        if($render == 'self_host'){
            $html_string = $this->render([
                'payload' => $payload
            ]);

            $doc = new \DOMDocument();
            $doc->loadHTML($html_string);
            echo $doc->saveHTML();

        }
        else{
            $response = $this->request()->hostedPay($payment_properties);

            if(!isset($response->data) || !isset($response->data->link)){
                throw new \Exception('An error occurred while making a request to Rave');
            }

            header('Location: ' . $response->data->link);
        }

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

        $this->requeryCount++;

        //Event
        $event = null;

        if ($response && $response->status == "success")
        {
            if($response && $response->data && $response->data->status == "successful")
            {
                //Create Success event
                $event = new Event('widget.success', $this, [
                    'reference' => $reference,
                    'response' => $response,

                ]);
            }
            elseif($response && $response->data && $response->data->status == "failed")
            {
                //Create Cancel event
                $event = new Event('widget.success', $this, [
                    'response' => $response,
                    'reference' => $reference,
                ]);
            }
            else
            {
                // Handled an undecisive transaction. Probably timed out.
                // I will requery again here. Just incase we have some devs that cannot setup a queue for requery. I don't like this.
                if($this->requeryCount > 4)
                {
                    // Now you have to setup a queue by force. We couldn't get a status in 5 requeries.
                    //Create Cancel event
                    $event = new Event('widget.timeout', $this, [
                        'reference' => $reference,
                    ]);
                }
                else
                {
                    sleep(3);
                    $this->verifyTransaction($reference);
                }
            }
        }
        else
        {
            // Handle Requery Error
            $event = new Event('widget.failure', $this, [
                'response' => $response,
                'reference' => $reference,
            ]);
        }

        //Dispatch event
        $this->getEventManager()->dispatch($event);

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
     * Render payment page
     * @param array $data
     * @return string
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    protected function render($data = [])
    {
        $loader = new \Twig_Loader_Filesystem(__DIR__.'/templates');
        $twig = new \Twig_Environment($loader, array());

        return $twig->render('payment_page.html', $data);
    }

    /**
     * Register Event Listeners
     */
    protected function registerEventHandlers()
    {
        $this->getEventManager()->on(new EventHandler());
    }

}
