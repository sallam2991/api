<?php
$urlManager = require('urlManager.php');
$params = require('params.php');

$config = [
	'urlManager' =>[
		'rules' => $urlManager,
		'showIndex' => true
	],
	'params' => $params,
];

if(ENV == 'dev')
{
	error_reporting(E_ALL);
	ini_set('display_errors', 1);
}


return $config;