<?php

class ChartsController extends ApiController
{
	public $layout='//layouts/charts';
	// public $theme='charts';

	public function actionGetChart()
	{
		$url_to_api_charts =  "http://tpz.server.loc.192.168.88.23.xip.io/api/charts";

		$this->render("index", array(
				'url_to_api_charts'=>$url_to_api_charts,
			));
	}


	// ?symbol=AAPL&resolution=D&from=1483877516&to=1514981576
	public function actionHistory($symbol, $resolution, $from, $to)
	{
		// var_dump(strtotime("2018-01-04 17:08:16"));die();
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
				  "session" => "0930-1630",
				  "has_intraday" => false,
				  "has_no_volume" => false,
				  "description" => "Bitcoin",
				  "type" => "stock",
				  "supported_resolutions" => [
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
			
				     "symbol"=> ["BTC", "MSFT", "SPX"],
             "description"=> ["Bitcoin", "Microsoft corp", "S&P 500 index"],
             "exchange-listed"=> "NYSE",
             "exchange-traded"=> "NYSE",
             "minmovement"=> 1,
             "minmovement2"=> 0,
             "pricescale"=> [1, 1, 100],
             "has-dwm"=> true,
             "has-intraday"=> true,
             "has-no-volume"=> [false, false, true],
             "type"=> ["stock", "stock", "index"],
             "ticker"=> ["BTC~0", "MSFT~0", "$SPX500"],
             "timezone"=> "America/New_York",
             "session-regular"=> "0900-1600",
             // "supported-resolutions"=> ["1S"],
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
					    1,
					  ],
					  "time"=> [
					    1515066119,
					    1515066119,
					    // 1514678400,
					    // 1514419200,
					    // 1514419200,
					    // 1513728000,
					    // 1512432000
					  ],
					  "color"=> [
					    "green",
					    "red",
					  ],
					  "text"=> [
					    "Today",
					    "Today",
					  ],
					  "label"=> [
					    "LM",
					    "YS",
					  ],
					  "labelFontColor"=> [
					    "white",
					    "white",
					  ],
					  "minSize"=> [
					    6,
					    6,
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