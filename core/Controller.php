<?php
// namespace core;

/**
* 
*/
class Controller
{
	const TEMPLATES_PATH = '/modules/views/',
			MAIN_PATH = '/modules/views/layouts/';

	public function render($template,$data = [])
	{
		$content = BASE_DIR.self::TEMPLATES_PATH.$template.'.php';
		if(empty($template) || !file_exists($content))
			die('View Not Found!');
		$header = BASE_DIR.self::MAIN_PATH.'header.php';
		$footer = BASE_DIR.self::MAIN_PATH.'footer.php';
		extract($data);
		if(file_exists($header))
			include($header);
		if(file_exists($content))
			include($content);
		if(file_exists($footer))
			include($footer);
	}
}