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
				"address"=>(is_null($this->user->address)) ? Yii::t('main','not_indicated') : $this->user->address,
				"zipcode"=>(is_null($this->user->zipcode)) ? Yii::t('main','not_indicated') : $this->user->zipcode,
				"email"=>(is_null($this->user->email)) ? Yii::t('main','not_indicated') : $this->user->email,
				"phone"=>(is_null($this->user->phone)) ? Yii::t('main','not_indicated') : $this->user->phone,
				"currency"=>$this->user->currency,
			);
		
	


		 $json->registerResponseObject('user', $result);
		
		 $json->returnJson();


	}



	

}