<?php

ini_set('include_path', '.' . PATH_SEPARATOR . dirname(__FILE__));

require_once 'Zend/Loader/Autoloader.php';

$autoloader = Zend_Loader_Autoloader::getInstance();
$autoloader->registerNamespace('MescClient_');