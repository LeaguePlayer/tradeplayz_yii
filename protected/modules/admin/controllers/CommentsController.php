<?php

class CommentsController extends AdminController
{
	public function actionList($id_look)
    {
        $model = $this->loadModel('Looks', $id_look);

        $comments_finder = new Comments('search');
        $comments_finder->unsetAttributes();
        if ( isset($_GET['Comments']) ) {
            $comments_finder->attributes = $_GET['Comments'];
        }
        $comments_finder->looks_id = $model->id;

        
        $this->render('list', array(
            'model' => $model,
            'comments_finder' => $comments_finder
        )); 
    }

    public function actionUpdate($id)
    {
        $model = $this->loadModel('Comments', $id);

        if(isset($_POST['Comments']))
        {
            $model->attributes = $_POST['Comments'];
            $success = $model->save();
            if( $success ) {
                $this->redirect(array('/admin/comments/list', 'id_look'=>$model->looks_id));
            }
        }
        $this->render('update', array('model' => $model));
    }

    public function actionDelete($id)
    {
        $model = $this->loadModel('Comments', $id);
        $id_look = $model->looks_id;
            if(!$model->delete())
            {
                    throw new CDbException('An error occured while trying to delete the meeting. Please try again or something.');
            }

            if(!isset($_GET['ajax']))
                    $this->redirect(array('/admin/comments/list/','id_look'=>$id_look));
    }
}
