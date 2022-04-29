<?php

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
            return static::getPdo()->query($sql);
        }
        else
        {
            $result = static::getPdo()->query($sql);
            return $result->fetchAll(PDO::FETCH_CLASS, $className);
        }
        
    }

    public static function getId($table_name,$id_name)
    {
        $result = static::getPdo()->query("SELECT id from " . $table_name . " WHERE name = '$id_name' LIMIT 1");
        $id = $result->fetch(PDO::FETCH_ASSOC);
        return $id['id'];
    }

    public static function prepare($sql)
    {
        return static::getPdo()->prepare($sql);
    }

    public static function exec($sql)
    {
        return static::getPdo()->exec($sql);
    }

}