<?php


class PageController extends ApiController
{
	public function actionIndex($alias)
	{
		$json = new JsonModel;
		$result = array();
		

		

		$model = Apppages::model()->find( array( 
			'condition'=>"meta_alias = :alias", 
			'params'=>array(':alias'=>$alias) ) );

		if(count($model)>0)
		{
			
					// $result['id'] = $model->id;
					$result['title'] = $model->title->text;
					$result['description'] = $model->description->text;
					
		
		}
		else
		{
			
			$json->returnError(JsonModel::RETURN_NULL);
			
			return true;
		}

		

		$json->registerResponseObject('page', $result);
		
		$json->returnJson();
	}


	public function accessRules()
		{
			return array(
				array('allow',  // allow all users to perform 'index' and 'view' actions
					'actions'=>array('index'),
				),
			);
		}
	
	

}