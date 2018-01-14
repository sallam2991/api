<?php

/**
* 
*/
class Purchase extends RipcordHelper
{

	function __construct($token)
	{
		parent::__construct($token);
	}


	public function getUser()
	{
		return $this->getUid();
	}

	public function getPurchase()
	{
		return $this->setUid()
					->setModel('res.partner')
					->setWhere([])
					->setFields(['name'])
					->getFields();
	}

	public function setPurchase($data=[])
	{
		return $this->setUid()
					->setModel('res.partner')
					->setData($data)
					->save();
	}
}