<?php

class LooksController extends AdminController
{
	public function actionList()
    {
        // $model = $this->loadModel('Shops', $malls_id);
        $data=array();
        $looks = new Looks('search');
        $looks->unsetAttributes();
        if ( isset($_GET['Looks']) ) {
            $looks->attributes = $_GET['Looks'];
        }
        
 		$users_all =  Users::model()->findAll("status=:status", array(':status'=>Users::STATUS_PUBLISH));
 		$data['users'] = CHtml::listData($users_all, 'id', 'fullNameWithId');

        
        $this->render('list', array(
            'model' => $looks,
            'data'=>$data,
        )); 
    }
}
