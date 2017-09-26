<?php
/**
 * @author V.Mladenov
 */
namespace Mladenov;

/**
 * Class JsonView
 *
 * @package Mladenov
 */
class JsonView
{
    public static function render(array $data)
    {
        header('Content-Type: application/json');

        return json_encode($data);
    }
}