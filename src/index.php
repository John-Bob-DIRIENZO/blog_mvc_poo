<?php
session_start();

// Getting the Autoload
require 'Vendor/Core/SplClassLoader.php';
require_once 'Config/autoload.php';

$router = new \Vendor\Core\Router();
$router->getController();
/*
var_dump($_SERVER['PHP_AUTH_USER']);
var_dump($_SERVER['PHP_AUTH_PW']);
var_dump($_SERVER['PHP_AUTH_DIGEST']);
*/
