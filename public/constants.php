<?php

define('DS', DIRECTORY_SEPARATOR);

define('ROOT_DIR', __DIR__ . DS . '..');
define('VIEW_DIR', ROOT_DIR . DS . 'App' . DS . 'View' . DS);
define('IS_DEBUG_INSTANCE', false);

define('OPERATOR_URL_NOPROTOCOL', '//' . rtrim($_SERVER['HTTP_HOST'] . dirname(str_replace("public", "", $_SERVER['PHP_SELF'])), '\\'));

define('DB_TABLE_AUTOMOBILE', 'automobile');
define('DB_TABLE_AUTOMOBILE_BRAND', 'automobile_brand');
define('DB_TABLE_AUTOMOBILE_BRAND_MODEL', 'automobile_brand_model');
define('DB_TABLE_AUTOMOBILE_DATA', 'automobile_data');
define('DB_TABLE_AUTOMOBILE_PART', 'automobile_part');
define('DB_TABLE_AUTOMOBILE_PART_REPAIR_CARD', 'automobile_part__repair_card');
define('DB_TABLE_CLIENT', 'client');
define('DB_TABLE_COLOR', 'color');
define('DB_TABLE_REPAIR_CARD', 'repair_card');
define('DB_TABLE_REPAIR_CARD_DATA', 'repair_card_data');
define('DB_TABLE_WORKER', 'worker');

define('DB_EXPRESSION_NOW', 'NOW()');
define('DB_NULL_VALUE', 'NULL');
define('DB_IS_VALUE', 'IS');
define('DB_ORDER_BY_VALUE', 'ORDER BY');
define('DB_LIMIT_VALUE', 'LIMIT');
define('DB_WHERE_VALUE', 'WHERE');