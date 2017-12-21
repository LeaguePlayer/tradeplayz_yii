<?php

class TournamentsController extends ApiController
{
	public function actionIndex()
	{


		//params
		$interval_start = date("Y-m-d H:i",strtotime("-1 day"));
		$interval_finish = date("Y-m-d H:i",strtotime("+1 day"));

		// init
		$json = new JsonModel;
		$result = array();


		// check registred any tour
		if(is_object($this->user->active_participant)) // user already registred to tour
		{
			$allowed_status_tour_for_redirect = Tournaments::ALLOWED_FOR_REDIRECT;

			if(in_array($this->user->active_participant->tournament->status, $allowed_status_tour_for_redirect)) // игрок участвует в турнире
			{
				$json->registerResponseObject('redirect', true);
				$json->registerResponseObject('id_tour', $this->user->active_participant->id_tournament);
				$json->returnJson();
				return false;
			}
			

			
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
					'byuin'=>$tour['byuin'],
					'date_begin'=>date("H:i",strtotime($tour['dttm_begin'])),
					'prize_pool'=>number_format($tour['prize_pool'], 0, ',', ' ')." TPZ",
					// 'prize_places'=>$tour['prize_places'], // надо переопределить участники * на этот процент
					'registred_players'=>number_format($getAllPlayersByTour, 0, ',', ' '),
				);
		}


		//return
		$json->registerResponseObject('tournaments', $result);
		$json->returnJson();
	}


	public function actionGetAllPrizes( $id_tour )
	{
		// init
		$json = new JsonModel;
		$result = array();

		//criteria
		$criteria = new CDbCriteria;
		$criteria->addInCondition('status', Tournaments::ALLOWED_STATUSES);
		$criteria->addCondition("id = :id");
		$criteria->params[":id"] =  $id_tour;
		$criteria->select = "t.prize_places, t.id, (select count(*) from participants where id_tournament = t.id) as all_registred_players";
		
		//request
		$tour = Yii::app()->db->createCommand()->select( $criteria->select )
									   ->from(Tournaments::model()->tableName()." t")
									   ->where($criteria->condition, $criteria->params)
									   ->queryRow();

		if(empty($tour))
		{
			$json->error_text=Yii::t('main','tour_no_found');
			$json->returnError(JsonModel::CUSTOM_ERROR);
			return true;
		}

		$allPrizes = Tournaments::getAllPrizes( $tour['all_registred_players'], $tour['prize_places'], $limit = false, $prefix = "" );

		$result = array(
				'prizes'=>$allPrizes,
			);
		
		//return
		$json->registerResponseObject('tournament', $result);
		$json->returnJson();
	}


	public function actionGetActiveTour()
	{
		// init
		$json = new JsonModel;
		$result = array();

		// check registred any tour
		if(is_object($this->user->active_participant)) // user already registred to tour
		{
			$allowed_status_tour_for_redirect = Tournaments::ALLOWED_FOR_REDIRECT;

			if(in_array($this->user->active_participant->tournament->status, $allowed_status_tour_for_redirect)) // игрок участвует в турнире
			{
				$tour = $this->user->active_participant->tournament;
				$participant = $this->user->active_participant;
				$currency = Currency::getCurrencies( $tour->id_currency );
				$currency_to = Currency::getCurrencies( $tour->id_currency_to );

				$result = array(
						'id'=>$tour->id,
						'balance'=>$participant->balance,
						'balance'=>$participant->balance,
						'name'=>"{$currency} / {$currency_to}",
						'begin'=>date("H:i",strtotime($tour->dttm_begin)),
					);
			}
			else
			{
				$json->error_text=Yii::t('main','unknown_error');
				$json->returnError(JsonModel::CUSTOM_ERROR);
				return true;
			}
			

			
		}
		else
		{
			$json->error_text=Yii::t('main','unknown_error');
			$json->returnError(JsonModel::CUSTOM_ERROR);
			return true;
		}
		
		//return
		$json->registerResponseObject('tournament', $result);
		$json->returnJson();
	}


	public function actionGetAllParticipants( $id_tour, $query = false )
	{
		// init
		$json = new JsonModel;
		$result = array();

		$allRegistredPlayers = Tournaments::getAllRegistredPlayersByTourId( $id_tour, $limit = false, $query );

		$result = array(
				'participants'=>$allRegistredPlayers,
			);
		
		//return
		$json->registerResponseObject('tournament', $result);
		$json->returnJson();
	}


	public function actionViewLobby( $id_tour )
	{
		// init
		$json = new JsonModel;
		$result = array();

		//criteria
		$criteria = new CDbCriteria;
		

		$criteria->addInCondition('t.status', Tournaments::ALLOWED_STATUSES);
		$criteria->addCondition("t.id = :id");
		$criteria->params[":id"] =  $id_tour;
		$criteria->params[":model_name"] =  "Tournaments";
		$criteria->params[":id_lang"] =  Yii::app()->language;
		$status_participants_still_play = Participants::STATUS_STILL_PLAY;

		$criteria->select = "(CASE part.id_client WHEN {$this->user->id} THEN 1 ELSE 0 END) as you_registred, t.*, ( SELECT count(*) FROM participants p WHERE id_tournament = t.id) as participants_all, ( SELECT count(*) FROM participants p WHERE id_tournament = t.id and p.status = {$status_participants_still_play}) as participants_still_play, (SELECT wswg_body FROM content_lang cl WHERE cl.post_id = :id and cl.id_place = 'rules' and cl.model_name = :model_name and id_lang = :id_lang LIMIT 1) as rules, (select count(*) from participants where id_tournament = t.id) as all_registred_players";

		
		//request
		$tour = Yii::app()->db->createCommand()->select( $criteria->select )
									   ->from(Tournaments::model()->tableName()." as t left join participants part on (part.id_tournament = t.id and id_client = {$this->user->id})")
									   ->where($criteria->condition, $criteria->params)
									   ->order($criteria->order)
									   ->queryRow();

		if(empty($tour))
		{
			$json->error_text=Yii::t('main','tour_no_found');
			$json->returnError(JsonModel::CUSTOM_ERROR);
			return true;
		}


		$allRegistredPlayers = Tournaments::getAllRegistredPlayersByTourId( $tour['id'], $limit = 5 );
		$allPrizes = Tournaments::getAllPrizes( $tour['all_registred_players'], $tour['prize_places'] , $limit = 5 );

		$currency_play = Currency::getTournamentAllowedCurrencies( $tour['id_currency'] );
		$format_play = Tournaments::getFormats( $tour['id_format'] );

		$result = array(
				'id'=>$tour['id'],
				'you_registred'=>$tour['you_registred'],
				'name'=>"{$tour['byuin']} TPZ / {$currency_play} / {$format_play}",
				'date_begin'=>date("H:i d.m.Y",strtotime($tour['dttm_begin'])),
				'status'=>Tournaments::getStatusAliases( $tour['status'] ),
				'prize_places'=>number_format($tour['prize_places'], 0, ',', ' '), // надо переопределить участники * на этот процент
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


	public function actionGetStatusTour( $id_tour )
	{
		$json = new JsonModel;
		$result = array();

		$criteria = new CDbCriteria;
		$criteria->addCondition("id_client = :id_client and id_tournament = :id_tournament");
		$criteria->params[':id_client'] = $this->user->id;
		$criteria->params[':id_tournament'] = $id_tour;

		$participant = Participants::model()->find($criteria);

		if(is_object($participant))
		{
			if($participant->status == Participants::STATUS_FINISHED)
			{
				// отправляем сообщение
				if($participant->place == 1)
					$message = Yii::t('main','won_tour');
				else
					$message = Yii::t('main','lost_tour');

				$message .= " {$participant->place} ".Yii::t('main','place').". ";
				$message .= Yii::t('main','money_get');
				$message .= " {$participant->prize} TPZ.";

				$json->error_text=$message;
				$json->returnError(JsonModel::CUSTOM_ERROR);
				return true;
			}
			else
			{
				$result['balance'] =  $participant->balance;
			}
		}


		 $json->registerResponseObject('user', $result);
		
		 $json->returnJson();
	}


	public function actionBet( $id_type_bet, $sizing )
	{
		$json = new JsonModel;
		$result = array();


		if(!in_array($sizing, array(25,50,100)) || !in_array($id_type_bet, array( Tournaments::BET_DOWN, Tournaments::BET_UP )) ) // список доступных ставок
		{
			$json->error_text=Yii::t('main','error_sizing');
			$json->returnError(JsonModel::CUSTOM_ERROR);
			return true;
		}

		if(is_object($this->user->active_participant)) // пользователь участвует в турнире
		{
			$allowed_status_tour_for_bet = Tournaments::ALLOWED_FOR_BET;

			if(in_array($this->user->active_participant->tournament->status, $allowed_status_tour_for_bet) && $this->user->active_participant->tournament->paused == 0) // ставка разрешена
			{
				// проверяем баланс 
				$this->user->active_participant->balance -= $sizing;
				if($this->user->active_participant->balance >= 0)
				{
					// ставку разрешаем
					$bet = new TournamentBets;
					$bet->id_participants = $this->user->active_participant->id;
					$bet->id_type_bet = $id_type_bet;
					$bet->sizing = $sizing;
					$bet->id_tournament = $this->user->active_participant->tournament->id;
					$bet->create_time = date("Y-m-d H:i:s");
					

					
					if($bet->save())
					{
						// списываем баланс ставки
						$this->user->active_participant->update();

						$result = array(
								'balance'=>$this->user->active_participant->balance,
								'bet'=>true,
							);
					}
				}
				else
				{
					$json->error_text=Yii::t('main','not_enough_balance_for_bet');
					$json->returnError(JsonModel::CUSTOM_ERROR);
					return true;
				}
				
			}
			else
			{
				$json->error_text=Yii::t('main','action_not_available');
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
		
		



		 $json->registerResponseObject('bet', $result);
		
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
						$new_participants->place = null;
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