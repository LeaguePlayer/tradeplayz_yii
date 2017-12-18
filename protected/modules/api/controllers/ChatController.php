<?php

class ChatController extends ApiController
{
	public function actionSendMessage( $message = "" )
	{
		$json = new JsonModel;
		$result = false;
		
		$message = trim($message);
		if(!empty($message))
		{
			$message = addcslashes($message, '%_');

			$newMessageModel = new Chat;
			$newMessageModel->message = $message;
			$newMessageModel->id_user = $this->user->id;
			$result = $newMessageModel->save();
		}
		else
		{
			$json->error_text=Yii::t('main','cant_send_empty_message');
			$json->returnError(JsonModel::CUSTOM_ERROR);

			return true;
		}
		
	


		 $json->registerResponseObject('chat', $result);
		
		 $json->returnJson();
	}

	public function actionGetNewAnswers()
	{
		$json = new JsonModel;
		$result = 0;
		
		$result = Chat::getAnsweredChats( $this->user->id );
		$json->registerResponseObject('chat', array('count'=>$result));
		
		$json->returnJson();
	}


	public function actionGetChat()
	{
		$json = new JsonModel;
		$result = array();
		
		$criteria=new CDbCriteria;
        $criteria->addCondition("id_user = :id_user");
        $criteria->params = array(
            ':id_user' => $this->user->id,
        ); 
        $criteria->select = "*";
        $criteria->order = "create_time ASC";
        $limit = 50;

		
		//request
		$chats = Yii::app()->db->createCommand()->select( $criteria->select )
									   ->from(Chat::model()->tableName())
									   ->where($criteria->condition, $criteria->params)
									   ->order($criteria->order)
									   ->limit($limit)
									   ->queryAll();

		if(!empty($chats))
		{
			foreach($chats as $chat)
			{
				

				$result[] = array(
						'type'=>(string)Chat::TYPE_QUESTION,
						'message'=>$chat['message'],
						'name'=>"",
					);

				$chat['answer'] = trim($chat['answer']);
				if( !empty($chat['answer']) && !is_null($chat['answer']) )
				{
					$result[] = array(
						'type'=>(string)Chat::TYPE_ANSWER,
						'message'=>$chat['answer'],
						'name'=>"Admin",
					);
				}

				
			}
			// dismiss unviewed counter
			Chat::setAllViewed( $this->user->id );
		}
		


		 $json->registerResponseObject('chat', $result);
		
		 $json->returnJson();
	}
}