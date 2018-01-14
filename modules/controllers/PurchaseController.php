<?php

/**
* 
*/
class PurchaseController 
 extends API
{
	
	public function actionTest($id)
	{	
		if(!isset($id))
			die("Detailed description of what's wrong here");
			$code = 404;
		$msg = "Args not set!";
		if(isset($id))
		{
			$code = 200;
			$msg = "Args set!";
			$data = $id;
		}
		$this->response['status'] = $msg;
		$this->response['data'] = $data;
		echo $this->setHeader($code);
	}

	public function actionGetUser()
	{
		$obj = new Purchase();

		print_r($obj->getUser());
	}

	public function actionGetPurchase()
	{
		$obj = new Purchase();

		$result = $obj->getPurchase();
		if(isset($result['faultCode']))
			$this->ripcordError($result);
		if($result)
			$this->ripcordOK($result,'successful request!');;
	}

	public function actionCreatePurchase()
	{
		$this->requiredMethod('POST')
			->setPayload()
			->authorize()
			->purchaseRequiredPayload()
			->validatePayload();
		$obj = new Purchase($this->token);
		$result = $obj->setPurchase(
			[
				"name"=>$this->payload['name']
			]
		);
		if(isset($result['faultCode']))
			$this->ripcordError($result);
		if($result)
			$this->ripcordOK($result,'Purchase has been created!');
	}

	private function purchaseRequiredPayload()
	{
		$this->requiredPayload = [
			"name" => [
				"required" => true,
				"dataType" => "string"
			]
		];
		return $this;
	}

}
