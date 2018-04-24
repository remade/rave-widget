<?php
$configuration_values = require('config.php');

$widget = new \Remade\RaveWidget\Widget($configuration_values);

$transaction = $widget->verifyTransaction('1522088434y3Efa0p81R');
return $transaction;
