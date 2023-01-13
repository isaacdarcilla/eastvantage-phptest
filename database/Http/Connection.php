<?php

namespace database\Http;

use PDO;

class Connection
{
    private static $pdo;

    /**
     * Create a new connection using the configuration.
     *
     * @param array $config
     * @param string $file
     * @return void
     */
    public static function makeConnection(array $config, string $file = '')
    {
        $driver = $config['driver'];
        $host = $config['host'];
        $schema = $config['schema'];
        $username = $config['username'];
        $password = $config['password'];

        $dns = $driver . ':host=' . $host . ';dbname=' . $schema;
        self::$pdo = new PDO($dns, $username, $password);
    }

    /**
     * Return the PDO connection instance.
     *
     * @return mixed
     */
    public static function getConnection()
    {
        return self::$pdo;
    }
}
