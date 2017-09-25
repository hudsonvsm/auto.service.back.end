<?php
return [
    'loaderPath' => [
        ROOT_DIR . DS . 'vendor',
        ROOT_DIR
    ],
    'templatePath' => VIEW_DIR,
    'masterLayout' => VIEW_DIR . 'layout' . DS . 'master.phtml',
    'db' => include 'db.config.php',
    'basicGet' => include 'get.config.php',
    'picThumbnailWidth' => 150,
    'picThumbnailHeight' => 150,
    'tokenLength' => 50,
    'hashPassOptions' => [
        'cost' => 12,
    ],
    'controllerPrefix' => 'App\\Controller\\',
    'tables' => include 'table.config.php',
];