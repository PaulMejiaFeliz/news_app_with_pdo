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
     * @param string $host Name or ip of the host
     * @param string $dbName Name of the database
     * @param string $user User that will e used to connect to the database
     * @param string $password Password for the given user
     * @return \PDO The istance of the PDO object
     * @throws PDOException if connection couldn't be established
     */
    final public static function getConnection(string $host, string $dbName, string $user, string $password) : \PDO
    {
        try {
            self::$pdo = self::$pdo ?? new \PDO(
                "mysql:host={$host};dbname={$dbName};",
                $user,
                $password
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
