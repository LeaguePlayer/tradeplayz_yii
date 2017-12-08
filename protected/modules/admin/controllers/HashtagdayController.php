<?php

class HashtagDayController extends AdminController
{
    public function init()
    {
        parent::init();

        $cs = Yii::app()->clientScript;
        
        $cs->registerScriptFile($this->getAssetsUrl().'/js/moment.js', CClientScript::POS_HEAD);

        return true;
    }
    
	public function actionCreate()
    {
        $model = new HashtagDay;
      

        if(isset($_POST['Hashtagday']))
        { 
            $model->attributes = $_POST['Hashtagday'];
            $success = $model->save();
            if( $success ) {
                $this->redirect(array('/admin/hashtagday'));
            }
        }
        $this->render('create', array('model' => $model));
    }

    public function actionUpdate($id)
    {
        $model = $this->loadModel('Hashtagday', $id);
       

        if(isset($_POST['Hashtagday']))
        {
            $model->attributes = $_POST['Hashtagday'];
            $success = $model->save();
            if( $success ) {
                $this->redirect(array('/admin/Hashtagday'));
            }
        }
        $this->render('create', array('model' => $model));
    }
}
