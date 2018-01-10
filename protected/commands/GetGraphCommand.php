<?php

class GetGraphCommand extends CConsoleCommand {
    public function run($args) {
        $percents = 0.03;
        // тут делаем то, что нам нужно
    	$id_currency = Currency::CURRENCY_BTC;
    	$id_currency_to = Currency::CURRENCY_USD;
    	$time_now = date('Y-m-d H:i:s');
    	// $value = rand(15000, 25000);

        $SQL="SELECT * FROM graph ORDER BY coord_x DESC LIMIT 1";
        $connection=Yii::app()->db; 
        $command=$connection->createCommand($SQL);
        $row=$connection->createCommand($SQL)->queryRow();
        $last_y = $row['coord_y'];

        // var_dump($time_now);
        // var_dump($row['coord_x']);
        if( strtotime($time_now) != strtotime($row['coord_x']) )
        {
            // var_dump('create new');
            $value = rand( $last_y * (1-$percents) , $last_y * (1+$percents));
            $SQL="INSERT INTO graph (id_currency, id_currency_to, coord_x, coord_y) VALUES ({$id_currency}, {$id_currency_to}, '{$time_now}', '{$value}')";
            $command=$connection->createCommand($SQL);
            $command->execute(); // execute the non-query SQL
        }

        


    }
}