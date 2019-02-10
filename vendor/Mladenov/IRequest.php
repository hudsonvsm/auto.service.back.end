<?php


namespace Mladenov;


interface IRequest
{
    function getHeaders() : array ;

    function getOptions() : array ;
}