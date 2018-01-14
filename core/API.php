<?php
// namespace core;
 /**
AUTHOR: AHMED SALLAM
DESCRIPTION: THIS CLASS ACTS AS CORE OF HTTP REQUEST. SET HEADER, DETECT REQUEST METHOD,SET & VALIDATE
PAYLOAD, HANDLING REQUEST ERROR
*/
class API
{
	
	protected 	$token,
				$method,
				$requiredMethod = "GET",
				$payload = [],
				$requiredPayload = [],
				$response = [
					"status" => "failed",
					"message" => null,
					"data" => null
				];
	const 	OK_MSG = "ok",
			ERROR_MSG ="failed";
	function __construct()
	{
        header("Access-Control-Allow-Orgin: *");
        header("Access-Control-Allow-Methods: *");
        header("Content-Type: application/json");
        $this->method = $_SERVER['REQUEST_METHOD'];

	}

	protected function setHeader($status)
	{
		header("HTTP/1.1 " . $status . " " . $this->requestStatus($status));
		return json_encode($this->response);
	}
	protected function requiredMethod($method = "GET")
	{
		$this->requiredMethod = $method;
		return $this;
	}
	protected function setPayload()
	{
        if($this->method != $this->requiredMethod)
        	$this->requestError(405,"Invalid Request Method!");
		$body = file_get_contents("php://input");
		if(!isset($body) || empty($body))
			$this->requestError(405,"Required Data Not Found!");
		$this->payload = json_decode($body,true);
		return $this;
	}

	protected function authorize()
	{
		if(!isset($this->payload['token']) || empty($this->payload['token']) 
			|| !isset(Helper::params()[ENV][$this->payload['token']]))
			$this->requestError(405,"Authorization Failed!");
		$this->token = $this->payload['token'];
		return $this;
			
	}
	private function requestStatus($code)
	{
		$satus = [
			200 => 'OK',
            404 => 'Not Found',   
            405 => 'Method Not Allowed',
            500 => 'Internal Server Error'
        ];
        return ($satus[$code]) ? $satus[$code] : $satus[500];
	}
	protected function ripcordError($response)
	{
		if(isset($response['faultCode']))
		{
			$this->response['status'] = self::ERROR_MSG;
			$this->response['message'] = "something went wrong!";
		 	die($this->setHeader(500));
		}
	}
	protected function requestError($code=500,$msg="something went wrong!")
	{
		$this->response['status'] = self::ERROR_MSG;
		$this->response['message'] = $msg;
	 	die($this->setHeader($code));
	}
	protected function ripcordOK($data=null,$msg=null)
	{
		$this->response['status'] = self::OK_MSG;
		$this->response['message'] = $msg;
		$this->response['data'] = $data;
		die($this->setHeader(200));
	}

	//Validate Basice array of data for Multi-dimension array ovveride this method in the child class
	protected function validatePayload()
	{
		$errors = [];
		if(empty($this->payload))
			$this->requestError(405,"Empty Payload!");
		foreach ($this->requiredPayload as $key => $value)
		{
			foreach ($this->payload as $key1 => $value1)
			{
				if(isset($value['required']) && $value['required'] && (!isset($this->payload[$key]) || empty($this->payload[$key])))
					$errors [] = "{$key} is rquired!";
				if(isset($value['dataType']) && $value['dataType'] != gettype($this->payload[$key]))
					$errors [] = "Invalid {$key} type, must be {$value['dataType']}";
			}
			
		}
		if(count($errors) > 0)
			$this->requestError(405,$errors);
	}
}