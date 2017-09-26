<?php

namespace App\Controller;


trait ReflectionShortName
{
    static function getClassShortName(string $className)
    {
        $reflection = new \ReflectionClass($className);

        return $reflection->getShortName();
    }
}