<?php

class TournamentsController extends ApiController
{
	public function actionIndex()
	{


		//params
		$interval_start = date("Y-m-d H:i",strtotime("-1 day"));
		$interval_finish = date("Y-m-d H:i");

		// init
		$json = new JsonModel;
		$result = array();


		// check registred any tour
		if(is_object($this->user->active_participant)) // user already registred to tour
		{
			$json->registerResponseObject('allow', false);
			$json->returnJson();
			return false;
		}

		//criteria
		$criteria = new CDbCriteria;
		$criteria->addCondition(" dttm_begin BETWEEN :interval_start and :interval_finish ");
		

		$criteria->addInCondition('status', Tournaments::ALLOWED_STATUSES);
		$criteria->order = "dttm_begin DESC";


		$criteria->params[":interval_start"] =  $interval_start;
		$criteria->params[":interval_finish"] =  $interval_finish;

		$criteria->select = "*";

		
		//request
		$gotTournaments = Yii::app()->db->createCommand()->select( $criteria->select )
									   ->from(Tournaments::model()->tableName())
									   ->where($criteria->condition, $criteria->params)
									   ->order($criteria->order)
									   ->queryAll();

		// form data
		foreach($gotTournaments as $tour)
		{
			$getAllPlayersByTour = Tournaments::getAllPlayersByTourId( $tour['id'] );


			$result[] = array(
					'id'=>$tour['id'],
					'date_begin'=>date("H:i",strtotime($tour['dttm_begin'])),
					'prize_pool'=>number_format($tour['prize_pool'], 0, ',', ' ')." TPZ",
					'prize_places'=>$tour['prize_places'], // надо переопределить участники * на этот процент
					'registred_players'=>$getAllPlayersByTour,
				);
		}


		//return
		$json->registerResponseObject('tournaments', $result);
		$json->returnJson();
	}


	public function actionViewLobby( $id_tour )
	{
		// init
		$json = new JsonModel;
		$result = array();

		//criteria
		$criteria = new CDbCriteria;
		

		$criteria->addInCondition('status', Tournaments::ALLOWED_STATUSES);
		$criteria->addCondition("id = :id");
		$criteria->params[":id"] =  $id_tour;
		$criteria->params[":model_name"] =  "Tournaments";
		$criteria->params[":id_lang"] =  Yii::app()->language;
		$status_participants_still_play = Participants::STATUS_STILL_PLAY;

		$criteria->select = "t.*, ( SELECT count(*) FROM participants p WHERE id_tournament = t.id) as participants_all, ( SELECT count(*) FROM participants p WHERE id_tournament = t.id and p.status = {$status_participants_still_play}) as participants_still_play, (SELECT wswg_body FROM content_lang cl WHERE cl.post_id = :id and cl.id_place = 'rules' and cl.model_name = :model_name and id_lang = :id_lang LIMIT 1) as rules";

		
		//request
		$tour = Yii::app()->db->createCommand()->select( $criteria->select )
									   ->from(Tournaments::model()->tableName()." as t")
									   ->where($criteria->condition, $criteria->params)
									   ->order($criteria->order)
									   ->queryRow();

// 									   $query=str_replace(
//    array_keys($tour->params),
//    array_values($tour->params),
//    $tour->getText()
// );
									   // var_dump($tour->getText());
									   // die();
		// form data
		// var_dump($tour);die();
		// $countRegistredPlayers = Tournaments::getCountRegistredPlayers( $tour['id'] );
		$allRegistredPlayers = Tournaments::getAllRegistredPlayersByTourId( $tour['id'], $limit = 5 );
		$allPrizes = Tournaments::getAllPrizes( $allRegistredPlayers, $tour['prize_places'] , $limit = 5 );

		$currency_play = Currency::getTournamentAllowedCurrencies( $tour['id_currency'] );
		$format_play = Tournaments::getFormats( $tour['id_format'] );

		$result[] = array(
				'id'=>$tour['id'],
				'name'=>"{$tour['byuin']} TPZ / {$currency_play} / {$format_play}",
				'date_begin'=>date("H:i d.m.Y",strtotime($tour['dttm_begin'])),
				'status'=>Tournaments::getStatusAliases( $tour['status'] ),
				'prize_places'=>$tour['prize_places'], // надо переопределить участники * на этот процент
				'rules'=>$tour['rules'],
				'participants'=>$allRegistredPlayers,
				'prizes'=>$allPrizes,
				'count_participants'=>array(
						'all'=>$tour['participants_all'],
						'still_play'=>$tour['participants_still_play'],
					),
			);
		


		//return
		$json->registerResponseObject('tournament', $result);
		$json->returnJson();
	}

	public function actionUnregisterToTour( $id_tour )
	{
		// init
		$json = new JsonModel;
		$result = array();

		//get tour
		$tournament_model = Tournaments::getById( $id_tour );

		if(is_object($tournament_model))
		{
			// tour is ok
			//check already registration
			if( $tournament_model->checkParticipantRegistrationByUserId( $this->user->id ) )
			{
				// user not registred
				$this_participants = Participants::getModelByTourIdAndByUserId( $tournament_model->id, $this->user->id );


				if($this_participants->delete())
				{
					// participants removed is ok
					$new_balance = $this->user->balance + $tournament_model->byuin; // after return
					$this->user->balance = $new_balance;
					if($this->user->update()) // user unregistred
						$result["unregistration"] = true;
					else
					{
						$json->error_text=Yii::t('main','unknown_error');
						$json->detail_error=$new_participants->getErrors();
						$json->returnError(JsonModel::CUSTOM_ERROR);
						return true;
					}
					
				}
				else
				{
					$json->error_text=Yii::t('main','unknown_error');
					$json->detail_error=$new_participants->getErrors();
					$json->returnError(JsonModel::CUSTOM_ERROR);
					return true;
				}
				
			}
			else
			{
				$json->error_text=Yii::t('main','participant_not_registred');
				$json->returnError(JsonModel::CUSTOM_ERROR);

				return true;
			}

			

		}
		else
		{
			// cant reg tour - return message
			$json->error_text=$tournament_model;
			$json->returnError(JsonModel::CUSTOM_ERROR);

			return true;
		}

		//return
		$json->registerResponseObject('tournaments', $result);
		$json->returnJson();
	}


	public function actionRegisterToTour( $id_tour )
	{
		// init
		$json = new JsonModel;
		$result = array();

		//get tour
		$tournament_model = Tournaments::getById( $id_tour );

		if(is_object($tournament_model))
		{
			// tour is ok
			//check already registration
			if( ! $tournament_model->checkParticipantRegistrationByUserId( $this->user->id ) )
			{
				// user not registred
				$diff_balance = $this->user->balance - $tournament_model->byuin;
				if($diff_balance >= 0)
				{
					// balance is ok
					$this->user->balance = $diff_balance;
					if($this->user->update())
					{
						// data updated, now regitration him
						$new_participants = new Participants;
						$new_participants->id_client = $this->user->id;
						$new_participants->id_tournament = $tournament_model->id;
						$new_participants->balance = $tournament_model->begin_stack;
						$new_participants->place = Tournaments::getAllPlayersByTourId( $tournament_model->id )+1;
						if($new_participants->save()) // user registred
							$result["registration"] = true;
						else
						{
							$json->error_text=Yii::t('main','unknown_error');
							$json->detail_error=$new_participants->getErrors();
							$json->returnError(JsonModel::CUSTOM_ERROR);
							return true;
						}
					}
				}
				else
				{
					$json->error_text=Yii::t('main','not_enough_balance');
					$json->returnError(JsonModel::CUSTOM_ERROR);

					return true;
				}
			}
			else
			{
				$json->error_text=Yii::t('main','already_registred');
				$json->returnError(JsonModel::CUSTOM_ERROR);

				return true;
			}

			

		}
		else
		{
			// cant reg tour - return message
			$json->error_text=$tournament_model;
			$json->returnError(JsonModel::CUSTOM_ERROR);

			return true;
		}

		//return
		$json->registerResponseObject('tournaments', $result);
		$json->returnJson();
	}
}