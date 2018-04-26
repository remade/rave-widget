<?php
/**
 * This is a basic form of making a payment request. If no payment amount is created, the user would be presented a
 * payment form where they can enter any amount they want
 */
$configuration_values = require('config.php');

$widget = new \Remade\RaveWidget\Widget($configuration_values);

$widget->payment()->setEmail('mail4remi@yahoo.com')->setMetaData(['test' => 'test meta'])->setAmount(100)->setCurrency('NGN');
return $widget->makePaymentRequest('rave_host');
