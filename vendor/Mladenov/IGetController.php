<?php

namespace Mladenov;

interface IGetController
{
    function getCollection(array $params);

    function getItem($id);
}
