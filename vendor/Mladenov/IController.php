<?php

namespace Mladenov;

interface IController extends IGetController
{
    function addItem($post);

    function deleteItem($id);

    function updateItem($id, $params);
}
