<?php


class LoginController extends ApiController
{
	public function actionAuthSocial()
	{
		$provider = Yii::app()->request->getParam('provider');
		$user = Yii::app()->request->getParam('user');
		$user_device = Yii::app()->request->getParam('user_device');
		$social = Yii::app()->request->getParam('social');

		$errors = false;
		

		// //test data
		// $social = "google+"; // google+, facebook, twitter
		// $user = array(
		// 				'email'=>'minderov@amobile-studio.ru', 
		// 				'firstname'=>"Leonid",
		// 				'lastname'=>"Minderov",
		// 				'currency'=>Currency::CURRENCY_USD, 
		// 				'img_avatar'=>"https://lh3.googleusercontent.com/-XdUIqdMkCWA/AAAAAAAAAAI/AAAAAAAAAAA/4252rscbv5M/s384/photo.jpg",
		// 			 );


		// $provider = array(
		// 					'loginprovideridentifier'=>'45780234952224232322222'
		// 				 );
		// $user_device = array(
		// 						'model_phone'=>'iPhone 5S', 
		// 						'devicetoken'=>'23dq34asdh32423h432ghbh34vdasd21', // UDID
		// 						'id_os'=>'iOs 7.0',
		// 						'device_type'=>UserDevices::TYPE_TOKEN_ANDROID, // 0 - ios, 2 - android
		// 					);
		


		$json = new JsonModel;
		if(empty($user) || empty($user_device) || empty($provider))
		{
			$json->error_text=Yii::t('main','fatal_error');
			$json->returnError(JsonModel::CUSTOM_ERROR);
			return true;
		}


		$got_user_provider = UsersProvider::model()->find(
			array( 
				'condition'=>'loginprovider=:loginprovider and loginprovideridentifier = :loginprovideridentifier', 
				'params'=>array(
						':loginprovider'=>$social,
						':loginprovideridentifier'=>$provider['loginprovideridentifier']
								)
				));


		if(is_object($got_user_provider)) // если тру - значит пользователь уже авторизовывался под этой соц. сетью.
		{
			// пользователь существует 
			$got_user = Users::model()->findByPk($got_user_provider->id_user);

			if(!empty($got_user)) // пользователь существует
			{
				// $got_user->attributes = $user;
				// $got_user->img_avatar = $user['img_avatar'];
				// $got_user->update();

					// обновляем данные об устройстве
					$got_user_device = UserDevices::model()->find(
					array( 
						'condition'=>'model_phone=:model_phone and devicetoken = :devicetoken' , 
						'params'=>array(
								':model_phone'=>$user_device['model_phone'],
								':devicetoken'=>$user_device['devicetoken']
										)
						));

					if(is_object($got_user_device)) // проверяем заходил ли с этого устройства пользователь в прошлый раз
					{
						// если заходил, обновляем iOs
						if($got_user_device->id_os != $user_device['id_os']) 
						{
							$got_user_device->id_os = $user_device['id_os'];
							$got_user_device->update();
						}
					}
					else
					{
						// var_dump($user_device);die();
						$got_user_device = UserDevices::registerDevice($user_device, $got_user->id);
						
					}
			}
			else // пользователь не существует
			{

					$json->error_text=Yii::t('main','auth_error');
					$json->returnError(JsonModel::CUSTOM_ERROR);

					return true;
			}
			

		}
		else
		{
			// новый пользователь
			$got_user = new Users;
			
			$got_user->attributes = $user;
			$got_user->balance = 0;
			// var_dump();
			$got_user->img_avatar = $user['img_avatar'];
			// var_dump($got_user->attributes);die();
			$got_user->rating = Users::model()->count()+1; //
			$got_user->status = Users::STATUS_PUBLISH;
			
			if($got_user->save())
			{
				$got_user_device = UserDevices::registerDevice($user_device, $got_user->id);

				$new_user_provider = new UsersProvider;
				$new_user_provider->attributes = $provider;
				$new_user_provider->loginprovider = $social;
				$new_user_provider->id_user = $got_user->id;
				$new_user_provider->save();
			}
			else $errors = true;


			
		}


		if(!$errors)
		{
			// когда манипуляции с аккаунтом прошли - создаем токен, но сперва проверим, возможно он уже создан
			$got_token = TokenUser::model()->find( array('condition'=>"id_user = :id_user", 'params'=>array(':id_user'=>$got_user->id)) );

			if($got_token===null) // значит токен раньше не существовал
			{
				$got_token = new TokenUser;
				$got_token->id_user = $got_user->id;
				$got_token->token = md5($got_user->id.$got_user_device->devicetoken.time());
				$got_token->last_visit = date('Y-m-d H:i');
				$got_token->save();
			}
			else
			{
				// токен существовал, надо просто обновить время последней авторизации и сам токен
				$got_token->token = md5($got_user->id.$got_user_device->devicetoken.time());
				$got_token->last_visit = date('Y-m-d H:i');
				$got_token->update();
			}
		}
		else
		{
				$json->error_text=Yii::t('main','new_user_error');
				$json->detail_error=$got_user->getErrors();
				$json->returnError(JsonModel::CUSTOM_ERROR);

				return true;
		}

		
		$json->registerResponseObject('token', $got_token->token);
		$json->returnJson();
	}


	public function actionNewUser()
	{
		$user = Yii::app()->request->getParam('user');
		$user_device = Yii::app()->request->getParam('user_device');

		$errors = false;
		

		// //test data
		// $user = array(
		// 				'login'=>'minderov@amobile-studio.ru', 
		// 				'password'=>md5("123456"),
		// 				'currency'=>Currency::CURRENCY_USD,
		// 			 );


		// $user_device = array(
		// 						'model_phone'=>'iPhone 5S', 
		// 						'devicetoken'=>'23dq34asdh32423h432ghbh34vdasd21', // UDID
		// 						'id_os'=>'iOs 7.0',
		// 						'device_type'=>UserDevices::TYPE_TOKEN_ANDROID, // 0 - ios, 2 - android
		// 					);
		
// var_dump();




		$json = new JsonModel;

// 		$json->detail_error=$user;
// 				$json->returnError(JsonModel::CUSTOM_ERROR);
// return true;

		// var_dump($user['login']);die();
		

		if(empty($user['login']) || empty($user['password']) || trim($user['password'])=="" || trim($user['login'])=="" || !isset($user['login']) || !isset($user['password']))
		{
			$json->error_text=Yii::t('main','fill_fields');
			$json->returnError(JsonModel::CUSTOM_ERROR);
			return true;
		}
		else
			$user['login'] = strtolower($user['login']);

		if(!filter_var($user['login'], FILTER_VALIDATE_EMAIL)) // валидируем емейл / логин
		{
			$json->error_text=Yii::t('main','email_incorrect');
			$json->returnError(JsonModel::CUSTOM_ERROR);
			return true;
		}

		if(empty($user) || empty($user_device))
		{
			$json->error_text=Yii::t('main','fatal_error');
			$json->returnError(JsonModel::CUSTOM_ERROR);
			return true;
		}


		
			// пользователь существует 
			$got_user = Users::model()->find("login = :login", array(':login'=>$user['login']));

			if(empty($got_user)) // пользователь не существует
			{

				// новый пользователь
				$got_user = new Users;
				// foreach($user as $l => $u)
				// 	$got_user->$l = trim($u);
				$got_user->attributes = $user;
				$got_user->email = trim($got_user->login);
				$got_user->balance = 0;
				$got_user->rating = Users::model()->count()+1; //
				$got_user->status = Users::STATUS_PUBLISH;
				
				if($got_user->save())
					$got_user_device = UserDevices::registerDevice($user_device, $got_user->id);
				else 
					$errors = true;
			}
			else // пользователь существует
			{

					$json->error_text=Yii::t('main','user_exist');
					$json->returnError(JsonModel::CUSTOM_ERROR);

					return true;

			}
			

		


		if(!$errors)
		{
			// выдаём новый токен пользователю
				$got_token = new TokenUser;
				$got_token->id_user = $got_user->id;
				$got_token->token = md5($got_user->id.$got_user_device->devicetoken.time());
				$got_token->last_visit = date('Y-m-d H:i');
				$got_token->save();
			
		}
		else
		{
				$json->error_text=Yii::t('main','new_user_error');
				$json->detail_error=$got_user->getErrors();
				$json->returnError(JsonModel::CUSTOM_ERROR);

				return true;
		}

		
		$json->registerResponseObject('token', $got_token->token);
		$json->returnJson();
	}


	public function actionIndex()
	{

		$user = Yii::app()->request->getParam('user');
		$user_device = Yii::app()->request->getParam('user_device');

		$errors = false;
		

		// //test data
		// $user = array(
		// 				'login'=>'minderov@amobile-studio.ru', 
		// 				'password'=>md5("qwe123"),
		// 			 );


		// $user_device = array(
		// 						'model_phone'=>'iPhone 5S', 
		// 						'devicetoken'=>'23dq34asdh32423h432ghbh34vdasd21', // UDID
		// 						'id_os'=>'iOs 7.0',
		// 						'device_type'=>UserDevices::TYPE_TOKEN_ANDROID, // 0 - ios, 2 - android
		// 					);
		


		$json = new JsonModel;
		if(empty($user) || empty($user_device))
		{
			$json->error_text=Yii::t('main','fatal_error');
			$json->returnError(JsonModel::CUSTOM_ERROR);
			return true;
		}


		
			// пользователь существует 
			$got_user = Users::model()->find("LOWER(login) = :login and password = :password", array(':login'=>strtolower($user['login']), ':password'=>$user['password']));

			if(empty($got_user)) // пользователь не найден
			{
				$json->error_text=Yii::t('main','login_pas_error');
					$json->returnError(JsonModel::CUSTOM_ERROR);

					return true;
			}
			
			

		


		if(!$errors)
		{
			$got_user_device = $got_user->device;
			
			// когда манипуляции с аккаунтом прошли - создаем токен, но сперва проверим, возможно он уже создан
			$got_token = TokenUser::model()->find( array('condition'=>"id_user = :id_user", 'params'=>array(':id_user'=>$got_user->id)) );

			if($got_token===null) // значит токен раньше не существовал
			{
				$got_token = new TokenUser;
				$got_token->id_user = $got_user->id;
				$got_token->token = md5($got_user->id.$got_user_device->devicetoken.time());
				$got_token->last_visit = date('Y-m-d H:i');
				$got_token->save();
			}
			else
			{
				// токен существовал, надо просто обновить время последней авторизации и сам токен
				$got_token->token = md5($got_user->id.$got_user_device->devicetoken.time());
				$got_token->last_visit = date('Y-m-d H:i');
				$got_token->update();
			}
		}
		

		
		$json->registerResponseObject('token', $got_token->token);
		$json->returnJson();

	}

	public function actionValidToken($token)
	{
	
		$is_exist_token = true;
		
		
		$json = new JsonModel;
		

		$got_token_model = TokenUser::model()->find( array('condition'=>"token = :token", 'params'=>array(':token'=>$token)) );


			if(empty($got_token_model)) // значит токен раньше не существовал
				$is_exist_token = false;
			else
			{
				// токен существует, проверяем, существует ли еще пользователь
				
				$user_model = Users::model()->findByPk($got_token_model->id_user, "status = :status", array(':status'=>Users::STATUS_PUBLISH));


				if(empty($user_model))
					$is_exist_token = false;
				else
				{
					$second_phase_auth = "NO";
					if(is_object($user_model->provider))
						$second_phase_auth = $user_model->provider->loginprovider;

					$json->registerResponseObject('second_phase_auth', $second_phase_auth);
				}
			}

		$json->registerResponseObject('token_exist', $is_exist_token);

		$json->returnJson();
	}


	public function accessRules()
		{
			return array(
				array('allow',  // allow all users to perform 'index' and 'view' actions
					'actions'=>array('index','validtoken', 'authsocial', 'newuser'),
				),
			);
		}
}




