<?php


namespace Mladenov;


use App\Exceptions\AccessException;

interface IResponse
{
    /**
     * @param string $data
     * @throws AccessException
     */
    function parseData(string $data) : void;

    /**
     * @return array
     */
    public function getValues() : array;
}