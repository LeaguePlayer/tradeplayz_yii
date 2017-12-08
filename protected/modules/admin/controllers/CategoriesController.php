<?php

class CategoriesController extends AdminController
{
	public function actionCreate()
    {
        $model = new Categories();
        $data = array();

      
        

        if(isset($_POST['Categories']))
        {
            $model->attributes = $_POST['Categories'];

            if(isset($_POST['RGB']))
            	$model->color_rgb = serialize($_POST['RGB']);


            $success = $model->save();
            if( $success ) {
                $this->redirect(array('/admin/categories/list'));
            }
        }

        $model->color_rgb = unserialize($model->color_rgb);

        $this->render('create', array('model' => $model, 'data'=>$data));
    }

    public function actionUpdate($id)
    {
        $model = $this->loadModel('Categories', $id);
        $data = array();

        

        if(isset($_POST['Categories']))
        {
            $model->attributes = $_POST['Categories'];

            if(isset($_POST['RGB']))
            	$model->color_rgb = serialize($_POST['RGB']);

            $success = $model->save();
            if( $success ) {
                $this->redirect(array('/admin/categories/list'));
            }
        }

        $model->color_rgb = unserialize($model->color_rgb);

        $this->render('create', array('model' => $model, 'data'=>$data));
    }
}
