<?php

class Connection
{
    private static $con;
    
    final private function __construct () {}
    final private function __clone() {}
    final private function __wakeup() {}
    
    final public static function getConnection($config)
    {
        self::$con = self::$con ?? mysqli_connect(
            $config["host"],
            $config["user"],
            $config["password"],
            $config["dbName"]
        );
        return self::$con;
    }
}