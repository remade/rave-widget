# Rave Widget

A light-weight library to manage rave payment requests 
and response for Laravel apps and PHP projects in General

## Getting Started

These instructions will get you a copy of the project up and running on your local machine for development and testing purposes. See deployment for notes on how to deploy the project on a live system.

### Prerequisites

```
PHP >= 7.1
PHP PDO Extension
```
For Laravel:
```
Laravel >= 5.5.0
```

### Installing

```
composer require remade/rave-widget
```

### Usage
Define your Configuration values:
```
$configuration_values = [
    'database' => [
        'database_type' => 'mysql',
        'database_name' => 'my_db_name',
        'server' => 'my_db_host_address',
        'username' => 'my_db_username',
        'password' => 'my_db_password',
    ],

    'widget' => [
        'payment_request_table_name' => 'payment_request_table', //default
    ],

    'rave' => [
        'public_key' => '*******************************************', //get from rave
        'secret_key' => '*******************************************', //get from rave
        'environment' => 'test|live',
    ]
];
```

Instantiate the Widget Object. It can be done in one of two ways. 
There is no drawback irrespective of which way you choose.

You can create a new instance of the Configuration class and use it to instantiate
the Widget:

```
$config = new \Remade\RaveWidget\Core\Configuration($configuration_values);
$widget = new \Remade\RaveWidget\Widget($config);
```
or you can just initialize straight away with the configuration values:

```
$widget = new \Remade\RaveWidget\Widget($configuration_values);

```

For Laravel all these has already been taken care of. Just update your
`rave.widget.php` file in Laravel's app config file. The widget instance 
will be available as

```
$widget = new RaveWidget();
```

## Running the tests

```
phpunit
```

## Contributing

Please read [CONTRIBUTING.md](CONTRIBUTING.md) for details on our code of conduct, and the process for submitting pull requests to us.

## Versioning

We use [SemVer](http://semver.org/) for versioning. For the versions available, see the [tags on this repository](https://github.com/remade/rave-widget/tags). 

## License

This project is licensed under the MIT License - see the [LICENSE.md](LICENSE.md) file for details


