CREATE TABLE `rave_payment_request` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `currency` varchar(50) DEFAULT NULL,
  `status_text` varchar(10) DEFAULT NULL,
  `environment` varchar(10) DEFAULT NULL,
  `processed` tinyint(1) NOT NULL DEFAULT '0',
  `amount` decimal(12,2) DEFAULT NULL,
  `reference` varchar(255) DEFAULT NULL,
  `response_data` text,
  `request_data` text,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;