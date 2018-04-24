<?php
$configuration_values = require('config.php');

$widget = new \Remade\RaveWidget\Widget($configuration_values);

$widget->payment()->setEmail('mail4remi@yahoo.com')->setMetaData(['test' => 'test meta'])->setAmount(100)->setCurrency('NGN');
return $widget->makePaymentRequest('rave_host');
