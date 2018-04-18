<?php 

namespace Remade\RaveWidget\Core\Persitence;

use Medoo\Medoo;

class Database
{
    /**
     * Medoo Database Instance
     *
     * @var Medoo
     */
    protected $dbContextInstance;

    /**
     * Database constructor.
     *
     * @param $databaseType
     * @param $databaseName
     * @param $server
     * @param $username
     * @param $password
     */
    public function __construct($databaseType, $databaseName, $server, $username, $password)
    {
        $this->dbContextInstance = new Medoo([
            'database_type' => $databaseType,
            'database_name' => $databaseName,
            'server' => $server,
            'username' => $username,
            'password' => $password
        ]);
    }

    /**
     * Get payment request
     *
     * @param $table
     * @param $reference
     * @return array|bool
     */
    public function getPaymentRequest($table, $reference)
    {
        return $this->dbContextInstance->select($table, "*", [
            'reference' => $reference
        ]);
    }

    /**
     * Save payment request
     *
     * @param $table
     * @param $data
     */
    public function saveRavePaymentRequest($table, $data)
    {
        $this->dbContextInstance->insert($table, $data);
    }


    /**
     * Update payment request
     *
     * @param $table
     * @param $data
     * @param $reference
     */
    public function updateRavePaymentRequest($table, $data, $reference)
    {
        $this->dbContextInstance->update($table, $data, [
            'reference' => $reference
        ]);
    }

}
