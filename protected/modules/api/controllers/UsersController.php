<?php

class UsersController extends ApiController
{
	public function actionGetProfile()
	{

		$json = new JsonModel;
		$result = array(
				"id"=>$this->user->id,
				"firstname"=>$this->user->firstname,
				"lastname"=>$this->user->lastname,
				"img_avatar"=>$this->user->img_avatar,
				"balance"=>$this->user->balance,
				"login"=>$this->user->login,
				"rating"=>$this->user->rating,
				"address"=>$this->user->address,
				"zipcode"=>$this->user->zipcode,
				"email"=>$this->user->email,
				"currency"=>$this->user->currency,
			);
		
	


		 $json->registerResponseObject('user', $result);
		
		 $json->returnJson();


	}



	

}