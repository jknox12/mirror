<?php

// Services setup
define('MWP_SERVICE_PATH', realpath('../../../tiendanube/services/') . '/');
require MWP_SERVICE_PATH . 'ls-general-config.php';

define('LOG_PATH', realpath('../../../logs') . '/');

// TODO After writing tests, remove this line and check if everything still works. It might be unnecessary.
require_once(MWP_SERVICE_PATH . 'eloquent/loader.php');
if(! defined('MODEL_PATH')) {
    define('MODEL_PATH', realpath(MWP_CORE_PATH . 'models/') . '/');
}