<?php

class EventsstockController extends AdminController
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
        $model = new Eventsstock();
        $data = array();

        $data['shops'] = Shops::getShops(false, true);
        $data['malls'] = Malls::getMalls( $only_malls = true, $with_null = true );


        if(isset($_POST['Eventsstock']))
        {
            $model->attributes = $_POST['Eventsstock'];
            $success = $model->save();
            if( $success ) {
                $this->redirect(array('/admin/eventsstock/list'));
            }
        }
        $this->render('create', array('model' => $model, 'data'=>$data));
    }

    public function actionUpdate($id)
    {
        $model = $this->loadModel('Eventsstock', $id);
        $data = array();

        $data['shops'] = Shops::getShops(false, true);
        $data['malls'] = Malls::getMalls( $only_malls = true, $with_null = true );

        if(isset($_POST['Eventsstock']))
        {
            $model->attributes = $_POST['Eventsstock'];
            $success = $model->save();
            if( $success ) {
                $this->redirect(array('/admin/eventsstock/list'));
            }
        }
        $this->render('create', array('model' => $model, 'data'=>$data));
    }
}
