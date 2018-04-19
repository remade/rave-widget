<?php

namespace Remade\RaveWidget\Core\Events;

use Carbon\Carbon;

class EventHandler extends EventAbstract
{
    public function onInit($event)
    {
        $subject = $event->getSubject();
        $payment = $event->getData('payment');
        $payload = $event->getData('payload');

        $insert = [
            'environment' => $subject->configuration()->get('rave.environment'),
            'currency' => $payment->getCurrency(),
            'reference' => $payment->getTransactionReference(),
            'amount' => $payment->getAmount(),
            'status_text' => 'waiting',
            'request_data' => json_encode($payload),
            'created_at' => Carbon::now()->toDateTimeString(),
            'updated_at' => Carbon::now()->toDateTimeString(),
        ];

        //Persist Request
        $subject->persistence()->saveRavePaymentRequest($subject->configuration()->get('widget.payment_request_table_name'), $insert);
    }

    public function onSuccessful($event)
    {

    }

    public function onFailure($event)
    {

    }

    public function onTimeOut($event)
    {

    }

    public function onCancel($event)
    {

    }
}