<?php

class UsersController extends ApiController
{
	public function actionGetProfile()
	{

		$json = new JsonModel;

		$urlAvatar = null;
		if(!is_null($this->user->img_avatar))
			{

				$pos = strpos('http', $this->user->img_avatar);
					if ($pos === false)
						$urlAvatar = 'http://'.$_SERVER['HTTP_HOST'].$this->user->getImageUrl('small');

			}
			
				
			

		$result = array(
				"id"=>$this->user->id,
				"firstname"=>$this->user->firstname,
				"lastname"=>$this->user->lastname,
				"img_avatar"=>$urlAvatar,
				"balance"=>$this->user->balance,
				"login"=>$this->user->login,
				"rating"=>$this->user->rating,
				"address"=>$this->user->address,
				"zipcode"=>$this->user->zipcode,
				"email"=>$this->user->email,
				"phone"=>$this->user->phone,
				"currency"=>$this->user->currency,
			);
		
	


		 $json->registerResponseObject('user', $result);
		
		 $json->returnJson();


	}

	public function actionForgotPassword()
	{
		$subject = 'new password';
		$message = 'new message';
		$a = SiteHelper::sendMail($subject,$message,$to='minderov@amobile-studio.ru');
		var_dump($a);die();
	}


	public function actionEditProfile()
	{
		// allowed values for change
		$allowedParams = array(
				'name',
				// 'img_avatar',
				'address',
				'zipcode',
				'email',
				'phone',
				'currency',
			);
		$json = new JsonModel;
		$result = false;


		// $json->registerResponseObject('user', $_FILES);
		
		//  $json->returnJson();
		//  return false;
		$user_params = Yii::app()->request->getPost('user');
		// $user_params = Yii::app()->request->getParam('user');

		// test data
			// $user_params = array(
			// 			"id"=>5555,
			// 			"name"=>"leo min czxc czx",
			// 			"img_avatar"=>"https://lh3.googleusercontent.com/-XdUIqdMkCWA/AAAAAAAAAAI/AAAAAAAAAAA/4252rscbv5M/s384/photo.jpg",
			// 			"balance"=>9999999999,
			// 			"login"=>"qweqwewqew",
			// 			"rating"=>123123123232132,
			// 			"address"=>"address tyumen",
			// 			"zipcode"=>"31232132",
			// 			"email"=>"mail@example.ru",
			// 			"phone"=>"+1434331232132",
			// 			"currency"=>"43",
			// 			"status"=>"43",
			// 		 );


		if(!empty($_FILES['Users']))
			$this->user->attributes = $_FILES['Users'];


		if(!is_null($user_params))
		{
			foreach($user_params as $param => $value)
			{
				if(!in_array($param, $allowedParams))
					unset($user_params[$param]);
				elseif($param == 'email') // validate email
				{
					if(filter_var($value, FILTER_VALIDATE_EMAIL))
						$this->user->email = $value;
				}
				elseif($param == 'currency') // validate currency
				{
					if(Currency::existCurrencyById($value))
						$this->user->currency = $value;
				}
				elseif($param == 'name') // validate name
				{
					$fullName = explode(" ", $value);
					$this->user->firstname = $fullName[0];
					$this->user->lastname = "";
					unset($fullName[0]);
					if(count($fullName) > 0)
						foreach($fullName as $piece)
							$this->user->lastname .= $piece." ";

					$this->user->lastname = trim($this->user->lastname);
				}
				else
					$this->user->$param = $value;
			}

		

			$result = $this->user->update();
			if(!$result)
			{
				$json->error_text=Yii::t('main','unknown_error');
				$json->detail_error=$this->user->getErrors();
				$json->returnError(JsonModel::CUSTOM_ERROR);

				return true;
			}
		}
		
	


		 $json->registerResponseObject('user', array('edit'=>$result));
		
		 $json->returnJson();


	}


	public function accessRules()
		{
			return array(
				array('allow',  // allow all users to perform 'index' and 'view' actions
					'actions'=>array('forgotPassword'),
				),
			);
		}
	

}