<?php 

namespace Remade\RaveWidget\Core;


class Configuration
{
    /**
     * Default configuration options definition
     *
     * @var array
     */
    protected $defaultConfigurations = [
        'database' => [
            'database_type' => 'mysql',
            'database_name' => 'name',
            'server' => '',
            'username' => '',
            'password' => '',
        ],

        'widget' => [
            'payment_request_table_name' => 'payment_request_table',
            'hash_key' => '',
        ],

        'rave' => [
            'public_key' => '',
            'secret_key' => '',
            'environment' => 'test',
        ]
    ];

    /**
     * Configuration constructor.
     * @param $configurations
     */
    public function __construct($configurations)
    {
        $configurations = (array) $configurations;
        foreach ($configurations as $key => $value) {
            if(in_array($key, ['rave', 'widget', 'database'])){
                foreach ($value as $key1 => $value1) {
                    $this->set($key.".".$key1, $value1);
                }
            }
        }
    }


    /**
     * Validate provided  Configuration key name
     *
     * @param $config
     * @return bool
     */
    protected function validateKey($config)
    {
        if(empty($config))
        {
            return false;
        }

        $bits = explode('.', $config);

        if(!isset($this->defaultConfigurations[$bits[0]]) || !isset($this->defaultConfigurations[$bits[0]][$bits[1]]))
        {
            return false;
        }

        return true;
    }

    /**
     * Get a configuration value
     *
     * @param $config
     * @return null
     */
    public function get($config)
    {
        if(!$this->validateKey($config))
        {
            return null;
        }

        $bits = explode('.', $config);
        return $this->defaultConifgurations[$bits[0]][$bits[1]];
    }

    /**
     * Set a configuration value
     *
     * @param $config
     * @param null $value
     * @return $this
     */
    public function set($config, $value = null)
    {
        if(!$this->validateKey($config))
        {
            return $this;
        }      

        $bits = explode('.', $config);
        $this->defaultConifgurations[$bits[0]][$bits[1]] = $value;

        return $this;
    }
}