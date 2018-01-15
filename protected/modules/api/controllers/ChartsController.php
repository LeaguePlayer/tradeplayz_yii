<?php

class ChartsController extends ApiController
{
	public $layout='//layouts/charts';
	// public $theme='charts';

	public function actionGetChart()
	{

		// $url_to_api_charts =  "http://dev.tradeplayz.com/api/charts";
		$url_to_api_charts =  "http://tpz.server.loc.192.168.88.23.xip.io/api/charts";


		// var_dump();die();

		$this->render("index", array(
				'url_to_api_charts'=>$url_to_api_charts,
			));
	}

	public function actionGetLastGraph()
	{
		$json = new JsonModel;
		$now = date('Y-m-d H:i:s');
		 $SQL="SELECT coord_x,coord_y FROM graph ORDER BY coord_x DESC LIMIT 1";
		$connection=Yii::app()->db; 
		$command=$connection->createCommand($SQL);
		$row = $command->queryRow();

		$row['coord_x_2'] = strtotime("-1 minute ".$row['coord_x']);
		$row['coord_y_2'] = round($row['coord_y'] - ($row['coord_y']*0.1));
			
		$row['coord_x'] = strtotime($row['coord_x']);
		$row['coord_y'] = round($row['coord_y']);

		

		$json->justReturnItToJson( $row );
	}


	// ?symbol=AAPL&resolution=D&from=1483877516&to=1514981576
	public function actionHistory($symbol, $resolution, $from, $to)
	{
		$json = new JsonModel;
		$data = array();

		$from = date('Y-m-d H:i:s',$from);
		$to = date('Y-m-d H:i:s',$to);
		
		// var_dump($from);
		// var_dump($to);

	    $SQL="SELECT coord_x as t, coord_y as c FROM graph WHERE coord_x BETWEEN '{$from}' and '{$to}'";
		$connection=Yii::app()->db; 
		$command=$connection->createCommand($SQL);
		$predate = $command->queryAll(); // execute the non-query SQL

		if(!empty($predate))
		{
			foreach($predate as $chart)
			{
				$data['t'][] = strtotime($chart['t']);
				$data['c'][] = $chart['c'];
			}
			$data['s'] = "ok";
		}
		else
		{
			$data['s'] = "no_data";
			$data['nextTime'] = strtotime("+5 seconds");
		}
		
			header('Access-Control-Allow-Origin: *');
			$json->justReturnItToJson($data);

	}


	public function actionGetAllTrades()
	{
		// var_dump(array_keys($_POST['shapes']));die();

		if(!is_null($this->user->active_participant->tournament))
		{
			$json = new JsonModel;
			$data = array();
			$shapes = array();

			if(!empty($_POST['shapes']))
			{
				foreach(array_keys($_POST['shapes']) as $sh)
					$shapes[] = date('Y-m-d H:i:s',$sh);
			}
			
			// var_dump($shapes);die();
			$criteria = new CDbCriteria;
			$criteria->addCondition("t.id_tournament = :id_tour and level = :level and cart = :cart");

			

			$criteria->params[':id_tour'] = $this->user->active_participant->id_tournament;
			$criteria->params[':level'] = $this->user->active_participant->level;
			$criteria->params[':cart'] = $this->user->active_participant->cart;

			$criteria->join = ' inner join "participants" "p" on p.id = t.id_participants';



			if(!empty($shapes)){

				$criteria->addNotInCondition("t.create_time", $shapes );

				// var_dump($criteria);die();
			}

			$bets = TournamentBets::model()->findAll($criteria);

			foreach($bets as $bet)
			{
				if($bet->id_type_bet == 0) // down
				{
					$type = "down";
					$arrow = "arrow_down";
				}
				else
				{
					$type = "up";
					$arrow = "arrow_up";
				}

				// var_dump($bet->id_participants);
				if($this->user->active_participant->id == $bet->id_participants)
					$name = "You";
				else
					$name = $bet->participant->user->firstname;

				$sizing = number_format($bet->sizing, 0, ',', ' ');
				$currency = "$";

				$message = "{$name} bet {$type} {$currency}{$sizing}";

				$data[] = array(
						'time'=>strtotime($bet->create_time),
						'text'=>$message,
						'coord_y'=>$bet->value_when_was_bet,
						'arrow'=>$arrow,
					);
				
			}
// die();


			//work with modal
			if(!is_null($this->user->active_participant->tournament))
			{
				Yii::import("application.commands.*");
				$modal_data = array();
				$tournament = $this->user->active_participant->tournament;
				if($tournament->status == Tournaments::STATUS_PUBLISH)
				{
					$modal_data['show']=true;
					//tournament not started
					// die("tournament not started");
				}
				elseif( in_array($tournament->status, array(Tournaments::STATUS_PREPARATION, Tournaments::STATUS_RUNNING) ) )
				{

					if( $tournament->status == Tournaments::STATUS_RUNNING && ($tournament->paused == 0 || $tournament->paused == 1) ) // tour running or silince mode
					{
						$modal_data['show']=false;
						// die('running or silince');
						if($tournament->level  !=  $this->user->active_participant->level)
						{
							if( $this->user->active_participant->status == Participants::STATUS_STILL_PLAY )
							{

								$tour_begin_time = strtotime("+1 minute ".$tournament->dttm_begin); // это та минута, которая в холостую простаивает после начала турнира, т.к. подготовительная
			            		$time_to_finish_round_timestamp = $tour_begin_time + ( (GameplayCommand::TIME_ROUND) + ( ($tournament->level-1) * (GameplayCommand::TIME_BREAK_BETWEEN_ROUNDS + GameplayCommand::TIME_ROUND) ) );

								$modal_data['timer']=date('Y/m/d H:i:s',$time_to_finish_round_timestamp);

								$modal_data['show']=true;
								$modal_data['title']=Yii::t('main','at_start_round');
								// i have freewin
								$modal_data['content'] = "You have freewin";
							}
						}
					}
					else
					{

				
						$tour_begin_time = strtotime("+1 minute ".$tournament->dttm_begin); // это та минута, которая в холостую простаивает после начала турнира, т.к. подготовительная
	            		$time_to_finish_round_timestamp = $tour_begin_time + ( (GameplayCommand::TIME_ROUND) + ( ($tournament->level-1) * (GameplayCommand::TIME_BREAK_BETWEEN_ROUNDS + GameplayCommand::TIME_ROUND) ) );
	            		// $time_to_finish_round_timestamp = $tour_begin_time + ( (GameplayCommand::TIME_ROUND) + ( ($tournament->level-1) * (GameplayCommand::TIME_BREAK_BETWEEN_ROUNDS + GameplayCommand::TIME_ROUND) ) );

						$modal_data['show']=true;
						$modal_data['title']=Yii::t('main','at_start_round'); // free win time

						if($tournament->status  == Tournaments::STATUS_PREPARATION)
							$time_time_show_modal = $tour_begin_time;
						else
							$time_time_show_modal = $time_to_finish_round_timestamp;

							$modal_data['timer']=date('Y/m/d H:i:s',$time_time_show_modal);
						//preparation. we have info about enemy or freewin

						if($tournament->paused == 2) // now break
						{
							$time_to_ready_next_round_timestamp = $tour_begin_time + ( (GameplayCommand::TIME_ROUND + GameplayCommand::TIME_BREAK_BETWEEN_ROUNDS) + ( ($tournament->level-1) * (GameplayCommand::TIME_BREAK_BETWEEN_ROUNDS + GameplayCommand::TIME_ROUND) ) );
							$modal_data['title']=Yii::t('main','now_break_round');
							$modal_data['timer']=date('Y/m/d H:i:s',$time_to_ready_next_round_timestamp);
							$got_level = $tournament->level + 1;
						}
						else $got_level = $tournament->level;

						if($got_level  ==  $this->user->active_participant->level) // i have enemies
						{
							$all_players = Participants::model()->findAll("id_tournament = :id_tournament and level = :level and cart = :cart", array(
									':id_tournament'=>$this->user->active_participant->id_tournament,
									':level'=>$this->user->active_participant->level,
									':cart'=>$this->user->active_participant->cart,
								));

							$cnt = "";
							foreach($all_players as $p)
							{	
								$cnt .= '<div class="player">
											<img src="'.$p->user->getAvatar("icon").'">
											<div class="player_name">'.$p->user->getFullName().'</div>
										</div><div class="versus">VS.</div>';
								
							}
							$modal_data['content'] = $cnt;
							// die("i have enemies");
						}
						else
						{
							// i have free win or lost
							if( $this->user->active_participant->status == Participants::STATUS_STILL_PLAY )
							{
								// i have freewin
								// $time_to_finish_round_timestamp = $tour_begin_time + ( (GameplayCommand::TIME_ROUND) + ( ($tournament->level -1) * (GameplayCommand::TIME_BREAK_BETWEEN_ROUNDS + GameplayCommand::TIME_ROUND) ) );
								$time_to_ready_next_round_timestamp = $tour_begin_time + ( (GameplayCommand::TIME_ROUND + GameplayCommand::TIME_BREAK_BETWEEN_ROUNDS) + ( ($tournament->level -1-1) * (GameplayCommand::TIME_BREAK_BETWEEN_ROUNDS + GameplayCommand::TIME_ROUND) ) );

								$modal_data['timer']=date('Y/m/d H:i:s',$time_to_ready_next_round_timestamp);
								$modal_data['content'] = "You have freewin";
							}
							// else
							// 	die("i lost");
						}
					}
					
				}
				$json->registerResponseObject('modal', $modal_data);
			}
			

			$json->registerResponseObject('trades', $data);
			
			$json->returnJson();

			// header('Access-Control-Allow-Origin: *');
			// $json->justReturnItToJson($data);
		}

		
		
		

	}

	//symbols?symbol=AAPL
	public function actionSymbols($symbol)
	{

		$json = new JsonModel;
		
		$data = array(
				  "name" => "BTC",
				  "exchange-traded" => "BTC",
				  "exchange-listed" => "BTC",
				  "timezone" => "America/New_York",
				  "minmov" => 1,
				  "minmov2" => 0,
				  "pointvalue" => 1,
				  "session" => "1558-1600",
				  "has_intraday" => false,
				  "has_no_volume" => false,
				  "description" => "Bitcoin",
				  "type" => "stock",
				  "supported_resolutions" => [
				    "15S",
				    "D",
				    "2D",
				    "3D",
				    "W",
				    "3W",
				    "M",
				    "6M"
				  ],
				  "pricescale" => 100,
				  "ticker" => "BTC"
			);

		header('Access-Control-Allow-Origin: *');
		$json->justReturnItToJson($data);

	}
	//timescale_marks?symbol=AAPL~0&from=1513555200&to=2114362800&resolution=1
	public function actionTimescale_marks($symbol, $from, $to, $resolution)
	{

		$json = new JsonModel;
		
		$data = array(
				
				array(
				    "id"=> "tsm1",
				    "time"=> 1515064092,
				    "color"=> "red",
				    "label"=> "AB",
				    "tooltip"=> "TOOL TIP TEXT"
				  )
				
			);

		header('Access-Control-Allow-Origin: *');
		$json->justReturnItToJson($data);

	}

	// symbol_info?group=NYSE

	public function actionSymbol_info($group)
	{

		$json = new JsonModel;
		
		$data = array(
			
				     "symbol"=> ["BTC"],
             "description"=> ["Bitcoin"],
             "exchange-listed"=> "NYSE",
             "exchange-traded"=> "NYSE",
             "minmovement"=> 1,
             "minmovement2"=> 0,
             "pricescale"=> [100],
             "has-dwm"=> true,
             "has-intraday"=> true,
 
             "has_empty_bars"=> [false],
             "has-empty-bars"=> [false],
             "has-no-volume"=> [false],
             "type"=> ["stock"],
             "ticker"=> ["BTC~0"],
             "expiration_date"=>[true],
             "timezone"=> "America/New_York",
             "session-regular"=> ["1558-1800"],
             // "supported-resolutions"=> ["1S"],
             "supported_resolutions"=> [
				    // "1S",
				    "15S",
				  ]
			);

		header('Access-Control-Allow-Origin: *');
		$json->justReturnItToJson($data);

	}


	public function actionTime()
	{
		header('Access-Control-Allow-Origin: *');
		echo time();
	}

	public function actionConfig()
	{
		$json = new JsonModel;
		$data = array(
				"supports_search"=> true,
				  "supports_group_request"=> true,
				  "supports_marks"=> false,
				  "supports_timescale_marks"=> false,
				  "supports_time"=> true,
				  "exchanges"=> [
				    array(
				      "value"=> "",
				      "name"=> "All Exchanges",
				      "desc"=> ""
				    ),
				    array(
				      "value"=> "NasdaqNM",
				      "name"=> "NasdaqNM",
				      "desc"=> "NasdaqNM"
				    ),
				    // array(
				    //   "value"=> "NYSE",
				    //   "name"=> "NYSE",
				    //   "desc"=> "NYSE"
				    // ),
				    // array(
				    //   "value"=> "NCM",
				    //   "name"=> "NCM",
				    //   "desc"=> "NCM"
				    // ),
				    // array(
				    //   "value"=> "NGM",
				    //   "name"=> "NGM",
				    //   "desc"=> "NGM"
				    // )
				  ],
				  "symbols_types"=> [
				    array(
				      "name"=> "All types",
				      "value"=> ""
				    ),
				    array(
				      "name"=> "Stock",
				      "value"=> "stock"
				    ),
				    array(
				      "name"=> "Index",
				      "value"=> "index"
				    )
				  ],
				  "supported_resolutions"=> [
				    // "1S",
				    "D",
				    "2D",
				    "3D",
				    "W",
				    "3W",
				    "M",
				    "6M"
				  ]
			);

		header('Access-Control-Allow-Origin: *');
		$json->justReturnItToJson($data);
	}

	// marks?symbol=AAPL&from=1483972200&to=2114362800&resolution=D
	public function actionMarks($symbol, $from, $to, $resolution)
	{

		$json = new JsonModel;
		
		$data = array(
				"id"=> [
					    0,
					    // 1,
					    // 2,
					    // 3,
					    // 4,
					    // 5,6,7,8,9,10,11,12,13

					  ],
					  "time"=> [
					    1515066367,
					    // 1515066367,
					    // 1515066367,
					    // 1515066367,
					    // 1515066367,
					    // 1515066367,
					    // 1515066367,
					    // 1515066367,
					    // 1515066367,
					    // 1515066367,
					    // 1515067696,
					    // 1515067696,
					    // 1515067696,
					    // 1515067696,
					  ],
					  "color"=> [
					    "green",
					    // "red",
					    //   "green",
					    // "red",
					    //   "green",
					    // "red",
					    //   "green",
					    // "red",
					    //   "green",
					    // "red",
					    //   "green",
					    // "red",  
					    // "green",
					    // "red",
					  ],
					  "text"=> [
					    "Today <strong>Leonid</strong>",
					    // "NOBODY",
					    // "Today <strong>Leonid</strong>",
					    // "NOBODY",
					    // "Today <strong>Leonid</strong>",
					    // "NOBODY",
					    // "Today <strong>Leonid</strong>",
					    // "NOBODY",
					    // "Today <strong>Leonid</strong>",
					    // "NOBODY",
					    // "Today <strong>Leonid</strong>",
					    // "NOBODY",
					    // "Today <strong>Leonid</strong>",
					    // "NOBODY",
					  ],
					  "label"=> [
					    "LM",
					    // "YS",
					    // "LM",
					    // "YS",
					    // "LM",
					    // "YS",
					    // "LM",
					    // "YS",
					    // "LM",
					    // "YS",
					    // "LM",
					    // "YS",
					    // "LM",
					    // "YS",
					    // "LM",
					    // "YS",
					  ],
					  "labelFontColor"=> [
					    "white",
					    // "white",
					    // "white",
					    // "white",
					    // "white",
					    // "white",
					    // "white",
					    // "white",
					    // "white",
					    // "white",
					    // "white",
					    // "white",
					    // "white",
					    // "white",
					  ],
					  "minSize"=> [
					    6,
					    // 6,
					    // 6,
					    // 6,
					    // 6,
					    // 6,
					    // 6,
					    // 6,
					    // 6,
					    // 6,
					    // 16,
					    // 2,
					    // 1,
					    // 24,
					  ]
			);

		header('Access-Control-Allow-Origin: *');
		$json->justReturnItToJson($data);

	}

	public function accessRules()
	{
		return array(
			array('allow',  // allow all users to perform 'index' and 'view' actions
				'actions'=>array('history', 'symbols', 'symbol_info','config', 'time', 'marks', 'timescale_marks', 'getLastGraph'),
			),
		);
	}
}