<?php
namespace core;

/**
* 
*/
class Run 
{
	const CONTROLLER_PATH = "/modules/controllers/",
			QUERY = "(query)";
	private $url,
			$api = false,
			$eUrl = [];

	public function getUrl($url)
	{
		$this->url = $url;
		return $this;
	}
	public function route($urlManager)
	{	
		if(empty($this->url))
			die("Request Not Found");
		
		$path = parse_url($this->url)['path'];
		$this->url = ($urlManager['showIndex']) ? str_replace('/index.php/','',$path) 
				: str_replace('/','',$path);
		$this->eUrl = explode('/', $this->url);
		if($this->eUrl[0] == 'api')
		{
			$this->api = true;
			$this->eUrl = array_slice($this->eUrl,1);
		}
		$this->urlMapping($urlManager['rules']);
	}

	private function urlMapping($rules)
	{
		if (empty($this->url))
			return null;
		$controller = $action = null;
		$arguments = [];
		$found = false;
		foreach ((array)$rules as $key => $value)
		{
			if($key === $this->url)
			{
				$_url = explode('/', $value);
				$_key = explode('/', $key);
				if(isset($_url[0]))
					$controller = $_url[0];
				if(isset($_url[1]))
					$action = $_url[1];
				$found = true;
				break;
			}
		}
		if(!$found)
		{
			foreach ((array)$rules as $key => $value)
			{
				
				if(preg_match('/'.self::QUERY.'/',$key))
				{
					$_url = explode('/', $value);
					$_key = explode('/', $key);
					@$part1 = $this->eUrl[0];
					$key = str_replace('api/', '', $key);
					list($_part1) = explode('/', $key);
					if($part1 === $_part1)
					{
						if(!$this->api && (count($_key) != count($this->eUrl)))
							$this->inst($controller,$action,$arguments);
						if(isset($_url[0]))
							$controller = $_url[0];
						if(isset($_url[1]))
							$action = $_url[1];
						$arguments = array_slice($this->eUrl,1);
						break;
					}
				}
			}
		}
		$this->inst($controller,$action,$arguments);
		
	}
	private function inst($controller,$action,$arguments=null)
	{	
		// print_r(func_get_args());exit;
		if(empty($controller) || empty($action))
			$this->throwError("Request Not Found1");
		$class = ucfirst($controller).'Controller';
		$file = BASE_DIR.self::CONTROLLER_PATH.$class.'.php';
		if(!file_exists($file))
			$this->throwError("Request Not Found2");
		@require($file);
		if(!class_exists($class))
			$this->throwError("Request Not Found3");
		$object = new $class();
		$action = "action".$action;
		if(!method_exists($object, $action))
			$this->throwError("Request Not Found4");
		try
		{
			call_user_func_array([$object,$action],$arguments);
		} catch (\ArgumentCountError $e)
		{

			 $this->throwError("Few arguments have been passed!");
		}
	}	
	private function throwError($msg=null)
	{
		if($this->api)
		{
			$inst = new \API();
			die($inst->setHeader(404,$msg));
		}else
		{
			die($msg);
		}
	}
	
	
}

