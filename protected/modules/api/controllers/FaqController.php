<?php

class FaqController extends ApiController
{
	public function actionIndex( $query = false )
	{
		// init
		$json = new JsonModel;
		$result = array();


		//criteria
		$criteria = new CDbCriteria;
		$criteria->addCondition(" faq.status = :status ");
		
		$criteria->order = "cl_title.wswg_body ASC";
		$criteria->params[":model_name"] =  "Faq";
		$criteria->params[":id_lang"] =  Yii::app()->language;

		$criteria->params[":status"] =  Faq::STATUS_PUBLISH;



			$criteria->select = "faq.id, cl_title.wswg_body as title, cl_desc.wswg_body as description";


		if($query)
			$criteria->addSearchCondition('cl_title.wswg_body', $query,true,'AND');
		
		

		
		//request
		$gotFAQs = Yii::app()->db->createCommand()->select( $criteria->select )
									   ->from(Faq::model()->tableName(). " inner join content_lang as cl_title on (cl_title.post_id = faq.id and cl_title.id_place = 'title' and cl_title.model_name = :model_name and cl_title.id_lang = :id_lang)". " inner join content_lang as cl_desc on (cl_desc.post_id = faq.id and cl_desc.id_place = 'description' and cl_desc.model_name = :model_name and cl_desc.id_lang = :id_lang)")
									   ->where($criteria->condition, $criteria->params)
									   ->order($criteria->order)
									   ->queryAll();

									  // var_dump($gotFAQs);die();
		// // form data
		foreach($gotFAQs as $faq)
		{
			$result[] = array(
					'id'=>$faq['id'],
					'title'=>$faq['title'],
					'description'=>$faq['description'],
				);
		}


		//return
		$json->registerResponseObject('faq', $result);
		$json->returnJson();
	}


}