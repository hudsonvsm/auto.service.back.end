<?php

namespace Mladenov;

class Config
{
    static private $params = [];

    public static function create($config)
    {
        self::$params = $config;
    }

    public static function getProperty($propertyName)
    {
        return (array_key_exists($propertyName, self::$params))
            ? self::$params[$propertyName]
            : null;
    }

    public static function setProperty($propertyName, $value) 
    {
        return self::$params[$propertyName] = $value;
    }
}

