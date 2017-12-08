<?php

class NotificationController extends AdminController
{
	public function actionCreate()
	{
		Yii::app()->clientScript->registerScriptFile($this->getAssetsUrl().'/js/boardmenu.js', CClientScript::POS_END);
		$model = new Notification;	
		
		
		
		if(isset($_POST['Notification']))
		{
			$model->attributes = $_POST['Notification'];
			

			
			

			

			if($model->save())
			{
				// $push = new APNSPush;
				// $push->message = $_POST['Notification']['text'];
				// $push->sendPushForAll();

				$push = new PushModel;
				$push->message = $_POST['Notification']['text'];

				$models_iphone_devices = UserDevices::getIphoneTokens();
				$tokensIphone = CHtml::listData($models_iphone_devices, 'id', 'deviceToken');
				

				$models_android_devices = UserDevices::getAndroidTokens();
				$tokensandroid = CHtml::listData($models_android_devices, 'id', 'deviceToken');

				if(!empty($tokensIphone))
					$push->addIOsDevices( $tokensIphone );

				if(!empty($tokensandroid))
					$push->addAndroidDevices( $tokensandroid );

				$push->setData( array('open_downloader'=>1) );
				$push->sendPush();
				
				// $this->push($_POST['Notification']['text'], $model->id);
				$this->redirect("/admin/notification/list");	
			}
			
		}
		
		
		$this->render("create", array('model'=>$model) );
	}

	
	
	
	public function actionUpdate($id)
	{
		Yii::app()->clientScript->registerScriptFile($this->getAssetsUrl().'/js/boardmenu.js', CClientScript::POS_END);
		$model =  Notification::model()->findByPk($id);	
		
		
		
		if(isset($_POST['Notification']))
		{
			if ($_POST['Notification']['send']) {

				// $push = new APNSPush;
				// $push->message = $_POST['Notification']['text'];
				// $push->sendPushForAll();


				
			}
			$model->attributes = $_POST['Notification'];
			
			if($model->save()) {
				$push = new PushModel;
				$push->message = $_POST['Notification']['text'];

				$models_iphone_devices = UserDevices::getIphoneTokens();
				$tokensIphone = CHtml::listData($models_iphone_devices, 'id', 'deviceToken');
				

				$models_android_devices = UserDevices::getAndroidTokens();
				$tokensandroid = CHtml::listData($models_android_devices, 'id', 'deviceToken');

				if(!empty($tokensIphone))
					$push->addIOsDevices( $tokensIphone );

				if(!empty($tokensandroid))
					$push->addAndroidDevices( $tokensandroid );


				$push->sendPush();

				$this->redirect("/admin/notification/list");	
			}
			
		}
		
		
		$this->render("create", array('model'=>$model) );
	}


}
