<?php
use core\Run;
defined('ENV') or define('ENV','dev');
defined('BASE_DIR') or define('BASE_DIR',__DIR__);
define("DS", DIRECTORY_SEPARATOR);
require_once(BASE_DIR.'/vendor/autoload.php');
require_once(BASE_DIR.'/config/web.php');

$actual_link = (isset($_SERVER['HTTPS']) 
	? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
$inst = new Run();
$inst->getUrl($actual_link)->route($config['urlManager']);
