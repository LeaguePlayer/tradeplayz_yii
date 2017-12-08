<?php

class SiteController extends FrontController
{
	public $layout = '//layouts/simple';
	
	/**
	 * Declares class-based actions.
	 */
	public function actions()
	{
		return array(
			// captcha action renders the CAPTCHA image displayed on the contact page
			'captcha'=>array(
				'class'=>'CCaptchaAction',
				'backColor'=>0xFFFFFF,
			),
			// page action renders "static" pages stored under 'protected/views/site/pages'
			// They can be accessed via: index.php?r=site/page&view=FileName
			'page'=>array(
				'class'=>'CViewAction',
			),
		);
	}

	public function actionTestNotification($type, $device_token)
	{
		$push = new PushModel;
		$push->message = "Это тестовое сообщение!1232";
		switch ($type) {
			case 'ios':

				$push->addIOsDevices( $device_token );
					$push->DEBUG_MODE == PushModel::DEBUG_OFF;
				var_dump($push->sendPushIOs());
				break;
			
			case 'android':
				 $push->addAndroidDevices( $device_token );
				 var_dump($push->sendPushAndroid());
				break;

			
		}
	}

	public function actionTest()
	{
		$phones = PlacePhone::model()->findAll("phone != ''");

		$array = array();
		foreach ($phones as $phone) {
			$phone_number = SiteHelper::clearPhoneMask( $phone->phone );
			if(is_numeric($phone_number))
			{
				if(strlen($phone_number) == 10)
					{
						$phone_number = "8".$phone_number;
					}
					if(strlen($phone_number) == 6)
					{
						$phone_number = "83452".$phone_number;
					}
				

				// $mystring = 'abc';
				$findme   = '83452';
				$pos = strpos($phone_number, $findme);
				// var_dump($pos);
				// $array[] = $phone_number;
				$phone_number = ($pos === 0) ?  SiteHelper::getPhoneByMaskTyumen($phone_number) :  SiteHelper::getPhoneByMask($phone_number);
				// $array[  ] = $phone_number;
			}
			else
				$phone_number = "";

			// echo $phone_number;
			// echo "<br>";
			
			$phone->phone = $phone_number;
			$phone->update();
			
			// 
		}
// echo count($array);
		// SiteHelper::mpr($array);

		// echo Shops::getFolderName(427,'Men`s fashion');
   //    $push = new PushModel;
   //    $push->message = "ITS TEST MESSAGE";
   //    $push->setData( array('link'=>'https://mobsted.com/users/login') );
	  // $push->addIOsDevices("e066dcb06971ae53ff8c6886d7af39c05d6eb4d3442a51cdd0fff1a3ac70460e");
	  
	  // $push->addAndroidDevices("APA91bFbppqCaoNNbwUQKOSoMeJciEV5demi4chWz0NC4g1adI9az8XNIRlp1yWUEOFoXWVNq8M3xv6hI_uE8UtVrpjNAA8H78Fjxu15UjbdzzyiIiD2lgDOMkB0RmWNYltt1CBHsVQP2r8WP6vSm6hOBrUmtN0ltQ");
	  // SiteHelper::mpr($push->sendPush());
// 		BindsShopArea::model()->deleteAll();

// 		// die();

//       	$malls = MallPlan::model()->findAll();
//       	foreach($malls as $mall)
//       	{
//       		$coords = unserialize($mall->json_areas);
//       		// SiteHelper::mpr($coords);
// 			if(empty($coords))
// 				continue;
//       		foreach ($coords as $id_area => $coor) {
//       			$data = array(
//       				'id_plan'=>$mall->id,
//       				'area_id'=>$id_area,
//       				'shops_id'=>null,
//       				'create_time'=>date("Y-m-d H:i"),
//       				'update_time'=>date("Y-m-d H:i"),
//       				'id_mall'=>$mall->mall->id,
//       			);
// // SiteHelper::mpr($data);
//       			$BindsShopArea = new BindsShopArea;
//       			$BindsShopArea->attributes = $data;
//       			// SiteHelper::mpr($BindsShopArea->attributes);

//       			// die();
//       			$BindsShopArea->save();
//       		}

//       	}
	}
    

	/**
	 * This is the default 'index' action that is invoked
	 * when an action is not explicitly requested by users.
	 */
	public function actionIndex()
	{
		$this->redirect('/admin');
  //       $this->title = Yii::app()->config->get('app.name');
		// $this->render('index');
	}

	/**
	 * This is the action to handle external exceptions.
	 */
	public function actionError()
	{
		if($error=Yii::app()->errorHandler->error)
		{
			if(Yii::app()->request->isAjaxRequest)
				echo $error['message'];
			else
				$this->render('error', $error);
		}
	}
}