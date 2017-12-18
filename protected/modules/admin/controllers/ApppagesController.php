<?php

class ApppagesController extends AdminController
{
	public function actionCreate()
    {
        $model = new Apppages();
        $data = array();

 
            
        

        if(isset($_POST['ContentLang']))
        {
        	$model->contentLangs = $_POST['ContentLang'];

        	if($model->isNewRecord)
	            $model->meta_alias = SiteHelper::translit(mb_strtolower($_POST['ContentLang']['title']));

            $success = $model->save();
            if( $success ) {
                $this->redirect(array('/admin/apppages/list'));
            }
        }


        $this->render('create', array('model' => $model, 'data'=>$data));
    }


    public function actionUpdate($id)
    {
        $model = $this->loadModel('Apppages', $id);
        $data = array();

        

        if(isset($_POST['ContentLang']))
        {
        	$model->contentLangs = $_POST['ContentLang'];

        	if($model->isNewRecord)
	            $model->meta_alias = SiteHelper::translit(mb_strtolower($_POST['ContentLang']['title']));

            $success = $model->save();
            if( $success ) {
                $this->redirect(array('/admin/apppages/list'));
            }
        }

    

        $this->render('create', array('model' => $model, 'data'=>$data));
    }
}
