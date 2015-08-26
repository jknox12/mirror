<?php

// Hacks
$_SERVER['LARAVEL_ENV'] = 'testing';
$tiendanube = realpath('../../../tiendanube/');
$services = realpath("$tiendanube/services/");
define('MWP_ROOT', "$tiendanube/");
define('MWP_SERVICE_PATH', "$services/");
require $services . '/ls-general-config.php';
require $services . '/eloquent/loader.php';
require "TiendaNubeTestCase.php";
require "LaravelTestCase.php";
define('MODEL_PATH', realpath(MWP_CORE_PATH . 'models') . '/');
unset($tiendanube, $services);

// Setup Laravel
$application = realpath('../application');
$laravel = realpath('../laravel');
$public = realpath('../public');
require  $laravel . '/core.php';
