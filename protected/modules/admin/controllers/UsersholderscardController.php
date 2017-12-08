<?php

class UsersholderscardController extends AdminController
{
	public function actionRemove($id_user_holder_card)
	{
		$model = Usersholderscard::model()->findByPk($id_user_holder_card);
		if(!empty($model))
		{
	
				if($model->delete())
					{
						$deviceToken = $model->user->device->deviceToken;
						if(!empty($deviceToken))
						{
							$push = new APNSPush;
							$push->message = Yii::app()->config->get('app.push.passbook.fail');
							$push->addRecipientPush( $deviceToken );
							$push->sendPersonalPush();
						}

						$this->redirect("/admin/usersholderscard");
					}

		}
		else throw new CHttpException(404, 'Model not found');
	}

	public function actionTest()
	{
		$domain = "http://{$_SERVER['HTTP_HOST']}";

						$push = new APNSPush;
						$push->message = Yii::app()->config->get('app.push.passbook.success');
						$push->dataArrayForPush =  array( 'openPassbook'=> "{$domain}/api/users/CheckHolderCard?output=true&token=" ) ;
						$push->addRecipientPush( "de5744ec1075b7a41f51e73b195779b58121d22fe73e3789a84902e1bdde2c40" );
						$push->sendPersonalPush();
	}

	public function actionApply($id_user_holder_card)
	{
		$model = Usersholderscard::model()->findByPk($id_user_holder_card);
		if(!empty($model))
		{
			
			if(is_null($model->id_card))
			{
				$malloko_card = Mallokocards::model()->find("card_number = :card_number", array(':card_number'=>$model->card_number) );
				$model->id_card = $malloko_card->id;
				if($model->save()) // 
				{
					$deviceToken = $model->user->device->deviceToken;
					if(!empty($deviceToken))
					{
						$domain = "http://{$_SERVER['HTTP_HOST']}";

						$push = new APNSPush;
						$push->message = Yii::app()->config->get('app.push.passbook.success');
						$push->dataArrayForPush =  array( 'openPassbook'=> "{$domain}/api/users/CheckHolderCard?output=true&token=" ) ;
						$push->addRecipientPush( $deviceToken );
						$push->sendPersonalPush();
					}
					

						$this->redirect("/admin/usersholderscard");
				}
			}
			else throw new CHttpException(400, "Эта карта уже привязана к пользователю {$model->user->name}.");
		}
		else throw new CHttpException(404, 'Model not found');
	}

	public function actionList($type=false)
    {
        $data=array();
        $holders = new Usersholderscard('search');
        $holders->unsetAttributes();
        if ( isset($_GET['Usersholderscard']) ) {
            $holders->attributes = $_GET['Usersholderscard'];
        }

        $holders->not_null = true;
        
        $this->render('list', array(
            // 'model' => $model,
            'model' => $holders,
            'type' => $type,
        )); 
    }

	public function actionHolders()
	{
		$data=array();
        $holders = new Usersholderscard('search');
        $holders->unsetAttributes();
        if ( isset($_GET['Usersholderscard']) ) {
            $holders->attributes = $_GET['Usersholderscard'];
        }
        // echo count($holders->search);die();
 		// $users_all =  Users::model()->findAll("status=:status", array(':status'=>Users::STATUS_PUBLISH));
 		// $data['users'] = CHtml::listData($users_all, 'id', 'fullNameWithId');

        
        $this->render('holders', array(
            'model' => $holders,
            'data'=>$data,
        )); 
		// $this->render("holders");
	}
}
