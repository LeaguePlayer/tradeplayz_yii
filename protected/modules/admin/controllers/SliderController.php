<?php

class SliderController extends AdminController
{
	public function actionCreate()
    {
        $model = new Slider();
        $data = array();

        
        

        // if(isset($_POST['Slider']))
        // {
            // $model->attributes = $_POST['Slider'];

        $model->status = Slider::STATUS_CLOSED;

            $success = $model->save();
            if( $success ) {
                $this->redirect(array('/admin/slider/update','id'=>$model->id));
            }
        // }

        

        // $this->render('create', array('model' => $model, 'data'=>$data));
    }

    public function actionUpdate($id)
    {
        $model = $this->loadModel('Slider', $id);
        $data = array();

        if( Yii::app()->request->isAjaxRequest )
        {
            echo $model->saveImage($_POST['image_base64']);
            die();
        }

        if(isset($_POST['Slider']))
        {
            $model->attributes = $_POST['Slider'];

            

            $success = $model->save();
            if( $success ) {
                $this->redirect(array('/admin/slider/list'));
            }
        }

       

        $this->render('create', array('model' => $model, 'data'=>$data));
    }



    public function actionGetpostid($type)
    {
    	if(Yii::app()->request->isAjaxRequest)
    	{
    		header('Content-type: application/json');
    		
    		$result = [];
    		switch($type)
    		{
    			case 'shops':

    				$models = Shops::model()->with('category')->findAll( [
    								'condition'=>'t.status = :status',
    								'order'=>'t.title ASC',
    								'params'=>[ ':status'=>Shops::STATUS_PUBLISH ],
    							] );

    				foreach($models as $model)
    				{
    					$result[$model->id]['value'] = $model->id;
    					$result[$model->id]['label'] = "{$model->title} (Кат. {$model->category->title})";
    				} 
    			break;

    			case 'looks':

    				$models = Looks::model()->findAll( [
    								'condition'=>'t.status = :status',
    								'order'=>'t.id DESC',
    								'params'=>[ ':status'=>Looks::STATUS_PUBLISH ],
    							] );

    				foreach($models as $model)
    				{
    					$result[$model->id]['value'] = $model->id;
    					$result[$model->id]['label'] = "№ {$model->id}";
    				} 
    			break;

    			case 'events':
    				$today = date('Y-m-d H:i');
    				$models = Eventsstock::model()->findAll( [
    								'condition'=>'t.status = :status and id_type = :type and ( dttm_date_start <= :today and (:today <= dttm_date_finish or :today <= dttm_date_hide) )',
    								'order'=>'t.title ASC',
    								'params'=>[ 
    									':status'=>Eventsstock::STATUS_PUBLISH,
    									':type'=>Eventsstock::ES_EVENTS,
    									':today'=>$today,

    									 ],
    							] );

    				foreach($models as $model)
    				{
    					$result[$model->id]['value'] = $model->id;
    					$date_begin = date('d.m.Y H:i', strtotime($model->dttm_date_start));
    					$result[$model->id]['label'] = "$model->title (Начало {$date_begin})";
    				} 
    			break;

    			case 'stocks':
    				$today = date('Y-m-d H:i');
    				$models = Eventsstock::model()->findAll( [
    								'condition'=>'t.status = :status and id_type = :type and ( dttm_date_start <= :today and (:today <= dttm_date_finish or :today <= dttm_date_hide) )',
    								'order'=>'t.title ASC',
    								'params'=>[ 
    									':status'=>Eventsstock::STATUS_PUBLISH,
    									':type'=>Eventsstock::ES_STOCKS,
    									':today'=>$today,

    									 ],
    							] );

    				foreach($models as $model)
    				{
    					$result[$model->id]['value'] = $model->id;
    					$result[$model->id]['label'] = $model->title;
    				} 
    			break;

    			case 'bonus':
    				$today = date('Y-m-d H:i');
    				$models = Eventsstock::model()->findAll( [
    								'condition'=>'t.status = :status and id_type = :type and ( dttm_date_start <= :today and (:today <= dttm_date_finish or :today <= dttm_date_hide) )',
    								'order'=>'t.title ASC',
    								'params'=>[ 
    									':status'=>Eventsstock::STATUS_PUBLISH,
    									':type'=>Eventsstock::ES_BONUS,
    									':today'=>$today,

    									 ],
    							] );

    				foreach($models as $model)
    				{
    					$result[$model->id]['value'] = $model->id;
    					$result[$model->id]['label'] = $model->title;
    				} 
    			break;
    		}

    		
    		echo CJSON::encode($result);
    		Yii::app()->end();
    	}
    	return true;
    }

    public function init()
    {
    	parent::init();

    		Yii::app()->clientScript->registerScriptFile($this->getAssetsUrl().'/js/slider.js', CClientScript::POS_END);
            Yii::app()->clientScript->registerCssFile($this->getAssetsUrl().'/css/slider.css');

            Yii::app()->clientScript->registerScriptFile($this->getAssetsUrl().'/js/cropper.js', CClientScript::POS_END);
            Yii::app()->clientScript->registerCssFile($this->getAssetsUrl().'/css/cropper.css');

            

            Yii::app()->clientScript->registerScriptFile($this->getAssetsUrl().'/js/fancybox/source/jquery.fancybox.js', CClientScript::POS_END);
            Yii::app()->clientScript->registerCssFile($this->getAssetsUrl().'/css/fancybox/jquery.fancybox.css');

    	return true;
    }
}
