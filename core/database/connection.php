<?php

class Connection
{
    private static $_pdo;
    
    final private function __construct() {}
    final private function __clone() {}
    final private function __wakeup() {}
    
    final public static function getConnection(array $config) : PDO
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

    final public static function closeConnection() : void
    {
        try
        {
            if (!is_null(self::$_pdo)) {
                self::$_pdo = null;
            }    
        }
        catch(PDOException $e)
        {
            throw $e;
        }
    }
}