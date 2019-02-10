<?php

namespace Mladenov;

use App\Exceptions\AccessException;

interface IService
{
    /**
     * @throws AccessException
     */
    function executeCurl() : IResponse;

    function setRequest(IRequest $request) : void;

    function setResponse(IResponse $response) : void;
}