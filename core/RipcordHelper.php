<?php

/**
AUTHOR: AHMED SALLAM
DESCRIPTION: THIS CLASS ACTS AS AN RIPCORD COMMON MODEL TO ESTABLISH CONNECTION ON ERP AND CRUD DATA 
*/
class RipcordHelper
{
	protected 	$url,
				$db,
				$username,
				$password,
				$uid = null,
				$connection,
				$model,
				$fields = [],
				$where = [],
				$data = [],
				$limit = null;
	function __construct($token)
	{
		$credentials = Helper::params()[ENV][$token];
		$url = $credentials['url'];
		$db = $credentials['db'];
		$username = $credentials['username'];
		$password = $credentials['password'];
		$this->setUrl($url)
			->setDB($db)
			->setUsername($username)
			->setPassword($password);
	}

	protected function connect($type="common")
	{
		try
		{
			$this->connection = ripcord::client($this->url."/xmlrpc/2/".$type);
				return $this;
		}
		catch (\Ripcord_ConfigurationException $e) {
			die('Internal server error');
		}
	}

	protected function setDB($db)
	{
		$this->db = $db;
		return $this;
	}
	protected function setUsername($username)
	{
		$this->username = $username;
		return $this;
	}
	protected function setPassword($password)
	{
		$this->password = $password;
		return $this;
	}
	protected function setUrl($url)
	{
		$this->url = $url;
		return $this;
	}
	protected function setUid()
	{
		$this->uid = $this->getUid();
		return $this;
	}
	protected function setModel($model)
	{
		$this->model = $model;
		return $this;
	}
	protected function setfields($fields = [])
	{
		$this->fields = $fields;
		return $this;
	}
	protected function setWhere($where = [])
	{
		$this->where = $where;
		return $this;
	}
	protected function setLimit($limit)
	{
		$this->limit = $limit;
		return $this;
	}



	protected function getUid()
	{
		$this->connect();	
		if(!$this->connection)
			return;
		return $this->connection->authenticate($this->db, $this->username, $this->password, array());
		 
	}

	protected function setData($data)
	{
		$this->data = $data;
		return $this;
	}

	protected function searchBy()
	{
		$this->connect("object");
		return $this->connection->execute_kw(
			$this->db,$this->uid,$this->password,
			$this->model,'search',[
				$this->where
			]
		);
	}
	protected function getFields()
	{
		$this->connect("object");
		return $this->connection->execute_kw(
			$this->db,$this->uid,$this->password,
			$this->model,'search_read',
			[$this->where],
   			['fields'=>$this->fields, 'limit'=>$this->limit]
		);
	}
	protected function save()
	{

		$this->connect("object");
		return $this->connection->execute_kw(
			$this->db,$this->uid,$this->password,
			$this->model,'create',[
				$this->data
			]
		);
	}
}