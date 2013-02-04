#!/usr/bin/php
<?php

$autoload = __DIR__ . '/../vendor/autoload.php';
$config = __DIR__ . '/../config/config.ini';
$cache = __DIR__ . '/../data/cache/lastrequest.dat';

if (!file_exists($autoload)) {
	echo "Autoload does not exist (vendor/autoload.php).\n";
	echo "\n";
	echo "Please run composer install...\n";
	exit (-1);
}

require $autoload;

if (!file_exists($config)) {
	echo "Config file does not exist (config/config.ini).\n";
	echo "\n";
	echo "Please create config file, you may use config/config.ini.dist as a template.\n";
	exit (-2);
}

$iniReader = new Zend_Config_Ini($config);
$config = $iniReader->toArray();

$httpClient = new Zend_Http_Client(null, array(
	'sslverifypeer' => $config['ssl_verify_peer']
));

$xmlRpc = new MescClient_Client($config['mesc_api'], $httpClient);
$xmlRpc->setToken('riehle-token'); //$config['mesc_token']);

try {
	echo "Requesting zone list...\n";
	
	$zones = $xmlRpc->getDnsZoneProxy()->listAll();
	sort($zones, SORT_STRING);
	
	$lastRequest = null;
	$currentRequest = serialize($zones);
	
	if (file_exists($cache)) {
		$lastRequest = file_get_contents($cache);
	}
	
	if ($lastRequest != $currentRequest) {
		echo "Generating new " . basename($config['named_file']) . " file...\n";
		
		$file = "// Zones which this server should handle as slave\n";
		$file .= "// This file was automatically generated by mesc-dns-slave at " . date('d.m.Y H:i:s') . "\n";
		$file .= "// DO NOT EDIT THIS FILE MANUALLY!\n";
		$file .= "\n";
		
		foreach ($zones as $zone) {
			$file .= "zone \"$zone\" {\n";
			$file .= "\ttype slave;\n";
			$file .= "\tfile \"/var/lib/bind/$zone.zone\";\n";
			$file .= "\tallow-query { any; };\n";
			$file .= "\tmasters { " . $config['dns_master'] . "; };\n";
			$file .= "};\n\n";
		}
		
		file_put_contents($config['named_file'], $file);
		echo "Done\n";
		
		echo "Restarting Bind...\n";
		passthru($config['bind_restart_cmd']);
		echo "Done\n";
	}
	else {
		echo "No zone updates since last run.\n";
	}
	
	file_put_contents($cache, $currentRequest);
}
catch (Zend_XmlRpc_Client_HttpException $e) {
	echo "HTTP error occured when talking to server:\n";
	echo $e->getMessage() . "\n";
}
catch (Zend_XmlRpc_Client_FaultException $e) {
	echo "Error on API-call occured:\n";
	echo $e->getMessage() . "\n";
}