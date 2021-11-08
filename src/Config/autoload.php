<?php
$controllerLoader = new SplClassLoader('Controller', __DIR__ . './../');
$controllerLoader->register();

$entityLoader = new SplClassLoader('Entity', __DIR__ . './../');
$entityLoader->register();

$modelLoader = new SplClassLoader('Model', __DIR__ . './../');
$modelLoader->register();

$vendorLoader = new SplClassLoader('Vendor', __DIR__ . './../');
$vendorLoader->register();