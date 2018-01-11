<?php
namespace app;
use app\base\Base;
define('BASE',__DIR__);
define('BASE_URL','http://api.com');
// require 'vendor/autolaod.php';
require '../config/web.php';
require('../vendor/autoload.php');

// require 'core/Base.php';
// spl_autoload_register(function($className)
// {
//     $namespace=str_replace("\\","/",__NAMESPACE__);
//     $className=str_replace("\\","/",$className);
//     $class=CORE_PATH."/classes/".(empty($namespace)?"":$namespace."/")."{$className}.class.php";
//     include_once($class);
// });


$request = $_SERVER['REQUEST_URL'];
$q_string = $_SERVER['QUERY_STRING'];
$req = substr($req, 0, strpos($req, '?'));
$path = $urlManager[$req];
$base = new Base();
$base->route($path);