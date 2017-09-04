<?php namespace newsapp\core\database;

/**
 * class for creating a connection with the database
 */
class Connection
{
    /**
     * Instance the PDO object tha will be used the connect to the database
     *
     * @var PDO
     */
    private static $pdo;
    
    final private function __construct()
    {

    }

    final private function __clone()
    {

    }

    final private function __wakeup()
    {

    }
    
    /**
     * If exists retieves an instance of a PDO object, oterwise creates a new one
     *
     * @param array $config infomation tha will beb used to connect to the database (host, dbname, user, password)
     * @return PDO The istance of the PDO object
     * @throws PDOException if connection couldn't be established
     */
    final public static function getConnection(array $config) : \PDO
    {
        try {
            self::$pdo = self::$pdo ?? new \PDO(
                "mysql:host={$config['host']};dbname={$config['dbName']};",
                $config['user'],
                $config['password']
            );
            return self::$pdo;
        } catch (PDOException $e) {
            throw $e;
        }
    }

    /**
     * Closes the connection with the database
     *
     * @return void
     */
    final public static function closeConnection() : void
    {
        try {
            if (!is_null(self::$pdo)) {
                self::$pdo = null;
            }
        } catch (PDOException $e) {
            throw $e;
        }
    }
}
