<?php

namespace Remade\RaveWidget\Core\Events;

use Carbon\Carbon;

class EventHandler extends EventAbstract
{
    /**
     * On initialize event handler
     *
     * @param $event
     */
    public function onInit($event)
    {
        $subject = $event->getSubject();
        $payment = $event->getData('payment');
        $payload = $event->getData('payload_data');

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

    /**
     * On success event handler
     *
     * @param $event
     */
    public function onSuccessful($event)
    {
        $subject = $event->getSubject();
        $response = $event->getData('response');
        $reference = $event->getData('reference');

        $subject->persistence()->updateRavePaymentRequest($subject->configuration()->get('widget.payment_request_table_name'), $reference, [
            'status_text' => 'success',
            'response_data' => json_encode($response)
        ]);
    }

    /**
     * On failure event handler
     *
     * @param $event
     */
    public function onFailure($event)
    {
        $subject = $event->getSubject();
        $response = $event->getData('response');
        $reference = $event->getData('reference');

        $subject->persistence()->updateRavePaymentRequest($subject->configuration()->get('widget.payment_request_table_name'), $reference, [
            'status_text' => 'failed',
            'response_data' => json_encode($response)
        ]);
    }

    /**
     * On Time Out event handler
     *
     * @param $event
     */
    public function onTimeOut($event)
    {
        $subject = $event->getSubject();
        $reference = $event->getData('reference');

        $subject->persistence()->updateRavePaymentRequest($subject->configuration()->get('widget.payment_request_table_name'), $reference, [
            'status_text' => 'timeout'
        ]);
    }

    /**
     * On cancel event handler
     *
     * @param $event
     */
    public function onCancel($event)
    {
        $subject = $event->getSubject();
        $reference = $event->getData('reference');

        $subject->persistence()->updateRavePaymentRequest($subject->configuration()->get('widget.payment_request_table_name'), $reference, [
            'status_text' => 'cancelled'
        ]);
    }
}