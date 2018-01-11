<?php
namespace app\base;
/**
* 
*/
class Base
{
	
	public function route($path)
	{
		if(empty($path))
			//error
		list($folder,$controller,$action) = explode('/', $path);
		$inst = new BASE.$folder.'/'.$controller();
		return $inst->$action;
	}
}