<?php

namespace Mladenov;

interface IController
{
    function addItem($post);

    function getCollection(array $params);

    function getItem($id);

    function deleteItem($id);

    function updateItem($id, $params);

    function getModel();
}
