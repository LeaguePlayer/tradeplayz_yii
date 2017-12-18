<?php

class GetGraphCommand extends CConsoleCommand {
    public function run($args) {
        // тут делаем то, что нам нужно
    	$id_currency = Currency::CURRENCY_BTC;
    	$id_currency_to = Currency::CURRENCY_USD;
    	$time_now = date('Y-m-d H:i:s');
    	$value = rand(15000, 25000);

        $SQL="INSERT INTO graph (id_currency, id_currency_to, coord_x, coord_y) VALUES ({$id_currency}, {$id_currency_to}, '{$time_now}', '{$value}')";
		$connection=Yii::app()->db; 
		$command=$connection->createCommand($SQL);
		$command->execute(); // execute the non-query SQL


    }
}