<?php
require_once 'constants.php';
require_once '../vendor/Mladenov/Autoload.php';
require_once '../vendor/Mladenov/Config.php';

\Mladenov\Config::create(include '../configs/config.php');

\Mladenov\Autoload::register(\Mladenov\Config::getProperty('loaderPath'));