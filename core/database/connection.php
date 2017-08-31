<?php

class Connection
{
    private static $_pdo;
    
    final private function __construct() {}
    final private function __clone() {}
    final private function __wakeup() {}
    
    final public static function getConnection($config)
    {
        try
        {
            self::$_pdo = self::$_pdo ?? new PDO(
                "mysql:host={$config['host']};dbname={$config['dbName']};",
                $config['user'],
                $config['password']
            );
            return self::$_pdo;
        }
        catch(PDOException $e)
        {
            throw $e;
        }
    }
}