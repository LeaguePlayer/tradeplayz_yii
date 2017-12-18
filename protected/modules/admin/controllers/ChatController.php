<?php

class ChatController extends AdminController
{
	public $layout = '/layouts/structure';
    
	public function actionList()
    {
    	Yii::app()->clientScript->registerScriptFile($this->getAssetsUrl() . '/js/chat.js', CClientScript::POS_END);


    	$model = new Chat;
    	if(isset($_POST['Chat']))
        {
        	
        	// var_dump($_POST);die();

        	// если обычный пользователь задаёт вопрос
        	if(!Yii::app()->user->isAdmin())
        	{
        		$model->attributes = $_POST['Chat'];
	            $model->id_user = Yii::app()->user->getId();
	            $model->status = Chat::STATUS_NEW;
        	}
            else
            {
                if(!empty($_POST['Chat']['id']) && isset($_POST['Chat']['id']))
                {
                    $model = Chat::model()->findByPk($_POST['Chat']['id']);
                    // var_dump($_POST['Chat']['id']);die();
                    if(is_object($model))
                    {
                        $model->answer = $_POST['Chat']['answer'];
                        $model->status = Chat::STATUS_ANSWERED;
                        $model->update();
                    }
                    else
                        die("Неизвестная ошибка!");
                }
            }
           


            $success = $model->save();
            // var_dump($success);die();
            if( $success ) {
                $this->redirect(array('/admin/chat/list'));
            }
        }


        $Chat = new Chat('search');
        $Chat->unsetAttributes();

        if ( isset($_GET['Chat']) ) {
            $Chat->attributes = $_GET['Chat'];
        }

        // if(!Yii::app()->user->isAdmin())
        // {

        // 	$Chat->id_user = Yii::app()->user->getId();
        // }

        
        


        // var_dump($metadata['managers']);die();


        
        $this->render('list', array(
            'models' => $Chat,
            'model' => $model,
        )); 
    }
}
