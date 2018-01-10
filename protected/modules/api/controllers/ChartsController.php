<?php

class ChartsController extends ApiController
{
	public $layout='//layouts/charts';
	// public $theme='charts';

	public function actionGetChart()
	{

		// $url_to_api_charts =  "http://dev.tradeplayz.com/api/charts";
		$url_to_api_charts =  "http://tpz.server.loc.192.168.88.23.xip.io/api/charts";

		$this->render("index", array(
				'url_to_api_charts'=>$url_to_api_charts,
			));
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
	   // die();
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
			$criteria->addCondition("id_tournament = :id_tour");

			

			$criteria->params[':id_tour'] = $this->user->active_participant->id_tournament;



			if(!empty($shapes)){

				$criteria->addNotInCondition("create_time", $shapes );

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

				// var_dump($bet->id_participants);die();
				if($this->user->active_participant->id == $bet->id_participants)
					$name = "You";
				else
					$name = $this->user->firstname;

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
			// var_dump($data);
			// die();

			//return
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
				'actions'=>array('history', 'symbols', 'symbol_info','config', 'time', 'marks', 'timescale_marks'),
			),
		);
	}
}