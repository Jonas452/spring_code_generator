<?php

/*
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
*/

include_once('util/LoadConfig.class.php');

class Connection
{
    private static $conn;

    public function connect()
    {
        $config = LoadConfig::get();
        $type = $config->getType();
        $host = $config->getHost();
        $port = $config->getPort();
        $name = $config->getName();
        $user = $config->getUser();
        $pass = $config->getPass();

        switch ( $type ) {

            case "pgsql":

                $port = $port ? $port : "5432";
                $pdo = new PDO(
                    "{$type}:host={$host};port={$port};dbname={$name};", $user, $pass
                );

                break;

            case "mysql":

                $port = $port ? $port : "3306";
                $pdo = new PDO(
                    "{$type}:host={$host};port={$port};dbname={$name};", $user, $pass,
                    [ PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8" ]
                );

                break;
        }

        $pdo->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );

        return $pdo;
    }

    public static function get()
    {
        if (null === static::$conn) {
            static::$conn = new static();
        }

        return static::$conn;
    }

    protected function __construct() { }

    private function __clone() { }

    private function __wakeup() { }
}
