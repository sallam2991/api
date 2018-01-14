<?php
/**
* 
*/
class Helper
{
	public static function params()
	{
		require (BASE_DIR.'/config/web.php');
		if(isset($config['params']))
			return $config['params'];
		return null;
	}
}