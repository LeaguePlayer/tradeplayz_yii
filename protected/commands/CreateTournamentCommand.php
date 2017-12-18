<?php

class CreateTournamentCommand extends CConsoleCommand {
    public function run($args) {
        $next_hour = date('Y-m-d H:i', strtotime("+1 hour"));
  

        // тут делаем то, что нам нужно
    	$tour = new Tournaments;
        $tour->dttm_begin = $next_hour;
        $tour->status = Tournaments::STATUS_PUBLISH;
        $tour->prize_places = 10;
        $tour->byuin = 2;
        $tour->id_format = Tournaments::FORMAT_2X2;
        $tour->id_currency = Currency::CURRENCY_BTC;
        $tour->id_currency_to = Currency::CURRENCY_USD;
        $tour->prize_pool = 16;
        $tour->begin_stack = 100;

        


        if($tour->save())
        {
            foreach(Yii::app()->params['ALLOWED_COUNTRIES'] as $country)
            {
                $lang = new ContentLang;
                $lang->model_name = get_class($tour);
                $lang->wswg_body = "Its made automatic";
                $lang->id_lang = $country;
                $lang->id_place = "rules";
                $lang->post_id = $tour->id;
                $lang->save();
            }
        }

    }
}