<?php

namespace App\Database;

use PDO;

class Database{

    protected static $pdo;

    public static function connect()
    {
        if(!static::$pdo)
        {
            $config = include 'config.php';
            static::$pdo = new PDO ('mysql:host=' . $config['db_host'] .';dbname=' . $config['db_name'] , $config['db_user'], $config['db_password']);
        }
    }

    public static function getPdo()
    {
        static::connect();
        return static::$pdo;
    }

    public static function query($sql, $className = null)
    {
        if(!$className)
        {
            $result = static::getPdo()->query($sql);
            return $result->fetchAll(PDO::FETCH_ASSOC);
        }
        else
        {
            $result = static::getPdo()->query($sql);
            return $result->fetchAll(PDO::FETCH_CLASS, $className);
        }
    }

    public static function queryOne($sql)
    {
        $result = static::getPdo()->query($sql)->fetch(PDO::FETCH_ASSOC);
        return $result;
    }

    public static function prepare($sql)
    {
        return static::getPdo()->prepare($sql);
    }
    
    public static function queryPrepare($result)
    {
        return $result->fetchColumn();
    }
    
    public static function exec($sql)
    {
        return static::getPdo()->exec($sql);
    }
}
