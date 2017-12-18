<?php

class GameplayCommand extends CConsoleCommand {

const CART_NEXT_FOUND = 'go_next_round';
const TIME_ROUND = 60; // in seconds
const TIME_BREAK_BETWEEN_ROUNDS = 30; // in seconds
const TIME_SILINCE = 10; // in seconds

    public function run($args) {
        $now = date('Y-m-d H:i:s');
// $connection=Yii::app()->db;  // remove late
// $SQL="SELECT * FROM tournaments WHERE id = 2";
//         $tour=$connection->createCommand($SQL)->queryRow();
// // remove late
//                 $graph_value_y_in_end = Graph::model()->find(array(
//                                                 'order'=>"coord_x DESC",
//                                                 'condition'=>"coord_x <= :time_bet",
//                                                 'params'=>array(':time_bet'=>$now),
//                                             ))->coord_y;


// $SQL="UPDATE participants SET place = 999 WHERE id = 19 RETURNING id_client";
//       $a =                       $connection->createCommand($SQL)->queryRow();
// var_dump($a);
// die();

 
      
    // var_dump($cart_for_change_winner);
                // die('stoped');

        // берем все турниры опубликованные турниры, которые должны начаться
        $status_publish = Tournaments::STATUS_PUBLISH;
        $SQL="SELECT * FROM tournaments WHERE status = {$status_publish} and dttm_begin <= '{$now}'";
        $connection=Yii::app()->db; 
        $allPublishTours=$connection->createCommand($SQL)->queryAll();

        // мы должны его перевести в подготовку и сделать сортировку людей
        foreach($allPublishTours as $tour)
        {
            $current_level = 1;
            $status = Tournaments::STATUS_PREPARATION;
            

            // сеем людей
            $SQL="SELECT * FROM participants WHERE id_tournament = {$tour[id]} ORDER BY random()";
            $allParticipants=$connection->createCommand($SQL)->queryAll();

            $carts_allowed = floor( count($allParticipants) / $tour['id_format'] ); // $tour['id_format'] - это макс. число участников в корзине
            $cart_fulled = 0; // устаналиваем счётчик заполненных корзин
            $carts = array(); // корзины с участниками
            foreach($allParticipants as $n_participant => $participant)
            {
                if(($n_participant+1)%$tour['id_format'] == 0) // заполняем одну корзину целиком
                {
                    $carts[$cart_fulled][] = $participant['id']; // кладём участника в корзину
                    $cart_fulled++; // метим корзину как ещё одну заполненную
                }
                else
                {
                    //проверяем, не все ли корзину уже заняты
                    if( $cart_fulled == $carts_allowed )
                    {
                        // корзины все заняты, люди идут в сл. раунд, опишем этот переход ниже
                        $carts[self::CART_NEXT_FOUND][] = $participant['id'];
                    }
                    else
                        $carts[$cart_fulled][] = $participant['id']; // кладём участника в корзину
                }

                unset($allParticipants[$n_participant]); // заполненного человека удаляем из массива
            }

            // // переводим турин в уровень 1 и переводим в статус  подготовка
            $SQL="UPDATE tournaments SET level = '{$current_level}', status = '{$status}' WHERE id = '$tour[id]'";
            $connection->createCommand($SQL)->execute();
            
            // поменяем в базе данных игровок, в рамках посева
            foreach($carts as $index_cart => $cart)
            {
                if($index_cart === self::CART_NEXT_FOUND) //это та корзина, где люди идут в сл. раунд, она всегда будет последней
                {
                    $current_level++; 
                    $index_cart = 0;
                }
                    
                   
                    $ids_participants = implode(',', $cart);
                    $SQL="UPDATE participants SET level = {$current_level}, cart = {$index_cart}, balance='{$tour[begin_stack]}' WHERE id in ({$ids_participants})";
                    $connection->createCommand($SQL)->execute();
            }

            

        }
        //end foreach


        // берем все турниры со статусом подготовка и проверяем, готовы ли они начаться, т.к. у нас идёт время тишины 1 минута
        $status_publish = Tournaments::STATUS_PREPARATION;
        $SQL="SELECT * FROM tournaments WHERE status = {$status_publish} and dttm_begin + INTERVAL '1 minute' <= '{$now}'";
        $allToursReadyToStart=$connection->createCommand($SQL)->queryAll();

        foreach($allToursReadyToStart as $tour)
        {
            $status = Tournaments::STATUS_RUNNING;
            // переводим турнир в режим начатые
            $SQL="UPDATE tournaments SET status = '{$status}' WHERE id = '$tour[id]'";
            $connection->createCommand($SQL)->execute();
        }
        //end foreach


        // берем все начатые турниры
        $status_publish = Tournaments::STATUS_RUNNING;
        $SQL="SELECT * FROM tournaments WHERE status = {$status_publish}";
        $allToursReadyToStart=$connection->createCommand($SQL)->queryAll();

        foreach($allToursReadyToStart as $tour)
        {
            $now_timestamp = time();
            $tour_begin_time = strtotime("+1 minute ".$tour['dttm_begin']); // это та минута, которая в холостую простаивает после начала турнира, т.к. подготовительная
            $time_to_finish_round_timestamp = $tour_begin_time + ( (self::TIME_ROUND) + ( ($tour['level']-1) * (self::TIME_BREAK_BETWEEN_ROUNDS + self::TIME_ROUND) ) );
            $time_silince_begin = $tour_begin_time +  ( (self::TIME_ROUND-self::TIME_SILINCE) + ( ($tour['level']-1) * (self::TIME_BREAK_BETWEEN_ROUNDS + self::TIME_ROUND) ) ) ;
            $time_to_ready_next_round_timestamp = $tour_begin_time + ( (self::TIME_ROUND + self::TIME_BREAK_BETWEEN_ROUNDS) + ( ($tour['level']-1) * (self::TIME_BREAK_BETWEEN_ROUNDS + self::TIME_ROUND) ) );

            // test time data
            var_dump(date("d.m.Y H:i:s"));
            var_dump(date("d.m.Y H:i:s",$time_silince_begin));
            var_dump(date("d.m.Y H:i:s",$time_to_finish_round_timestamp));
            var_dump(date("d.m.Y H:i:s",$time_to_ready_next_round_timestamp));

            //// несколько слов о paused параметре
            //  пауза ставится тогда, когда турнир переходит в режим тишины, 
            //  за 10 сек до конца рауна, присвоение значение 1.
            //  Когда раунд завершен, ставится пауза со значением 2, и ждёт 
            //  старта сл. рауна, когда раунд стартует, пауза меняется на значение 0.
            //  Значение 0 - это значение, которое позволяет делать ставки
            ////

            if($time_to_ready_next_round_timestamp <= $now_timestamp && $tour['paused']==2) // начинаем сл. раунд
            {
                // var_dump('unpause');
                // перебрасываем турнир на сл. уровень и стартуем его
                $next_level = $tour['level'] + 1 ;
                // снимаем турнир с паузы
                $SQL="UPDATE tournaments SET paused = 0, level = {$next_level} WHERE id = '$tour[id]'";
                $connection->createCommand($SQL)->execute();
            }
            elseif($time_to_finish_round_timestamp <= $now_timestamp && $tour['paused']==1) // раунд завершен, идёт подсчёт данных по раунду
            {
                // котировка на момент окончания турнира 
                // ТУТ НЕТ ПРИВЯЗКИ К ВАЛЮТЕ!!!
                $graph_value_y_in_end = Graph::model()->find(array(
                                                'order'=>"coord_x DESC",
                                                'condition'=>"coord_x <= :time_bet",
                                                'params'=>array(':time_bet'=>$now),
                                            ))->coord_y;
                // var_dump('finished');
                // делаем подсчёт результатов игровок.
                $SQL="UPDATE tournaments SET paused = 2 WHERE id = '$tour[id]'";
                $connection->createCommand($SQL)->execute();

                 // формируем подсчёт по игрокам и перекидываем на сл. уровень тех, кто выиграл
                $status_pr = Participants::STATUS_STILL_PLAY;
                $SQL="SELECT * FROM participants WHERE id_tournament = {$tour[id]} and status = {$status_pr} and level = {$tour[level]} ORDER BY level ASC, cart ASC";
                $allParticipants=$connection->createCommand($SQL)->queryAll();


                $SQL="SELECT count(*) as count FROM participants WHERE id_tournament = {$tour[id]}";
                $countAllParticipants=$connection->createCommand($SQL)->queryRow();

                $max_levels = ceil( $countAllParticipants['count'] / $tour['id_format'] ); // $tour['id_format'] - это макс.
                $finish_level = false;
                if($max_levels == $tour['level']) // это был последний уровень турнира
                {
                    // офаем его и ставим время окончания
                    $finish_level = true;
                    $status = Tournaments::STATUS_FINISHED;
                    $SQL="UPDATE tournaments SET status = {$status}, dttm_finish = '{$now}' WHERE id = '$tour[id]'";
                    $connection->createCommand($SQL)->execute();
                } 

                $cart_for_change_winner = array();
                foreach($allParticipants as $participant)
                {
                    $SQL="SELECT * FROM tournament_bets WHERE id_tournament = {$tour[id]} and id_participants =  {$participant[id]} and result is null ORDER BY create_time ASC";
                    $all_player_bets_in_round=$connection->createCommand($SQL)->queryAll();

                    $max_result_user = 0;
                    foreach($all_player_bets_in_round as $bet_in_round)
                    {
                        //R - результат
                        // D - котировка на момент окончания раунда
                        // d1 - котировка на момент ставки1
                        // х1 - размер ставки

                        // Формула:
                        // R=x1*(D-d1)
                         $v1 = ($bet_in_round['id_type_bet'] == Tournaments::BET_DOWN) ? -1 : 1;
                         $x1 = $bet_in_round['sizing'];
                         $D = $graph_value_y_in_end;
                         $d1 = $bet_in_round['value_when_was_bet'];
                         $R = ($v1*$x1)*($D - $d1);

                         // var_dump($d1);die();
                         $max_result_user += $R;
                         // фиксируем результат ставки в бд
                         $SQL="UPDATE tournament_bets SET result = {$R} WHERE id = '$bet_in_round[id]'";
                         $connection->createCommand($SQL)->execute();

                    }
                    $cart_for_change_winner[ $participant['cart'] ][ $participant['id'] ] = $max_result_user;
                    $max_result_user = 0;
                }
                 $next_level = $tour['level']+1;

                 // var_dump($cart_for_change_winner);die();
                foreach($cart_for_change_winner as $oCart)
                {
                   
                    arsort($oCart);
                    

                    $final_result_winner = current($oCart);
                    $keys = array_keys($oCart);
                    $id_participant_winner = $keys[0];
                    
                     

                    unset($oCart[$id_participant_winner]);
                    $ids_losers = implode(',', array_keys($oCart));
                    // меняем статус на вылетевшие
                    if(!empty($oCart))
                    {
                        $status = Participants::STATUS_FINISHED;
                         foreach($oCart as $id_loser=> $loser)
                        {
                            // var_dump($id_loser);die();
                            $SQL="SELECT min(place) as max FROM Participants WHERE id_tournament = {$tour[id]}";
                            $max_place = $connection->createCommand($SQL)->queryRow()['max'];
                            // var_dump($max_place);
                            $max_place = (is_null($max_place)) ? $countAllParticipants['count'] : $max_place-1;
                            // var_dump($max_place);
                            // $max_place
                            $got_prize = Tournaments::getTempPrizes($max_place);
                            
                             $SQL="UPDATE participants SET status = {$status}, place = {$max_place}, prize = {$got_prize} WHERE id = {$id_loser} RETURNING id_client";
                             $id_client_user = $connection->createCommand($SQL)->queryRow()['id_client'];

                             // зачисляем на баланс пользователя 
                             if($got_prize > 0)
                             {
                                $SQL="UPDATE users SET balance = (balance + {$got_prize}) WHERE id = {$id_client_user}";
                                $connection->createCommand($SQL)->execute();
                             }
                        }
                    }

                    // назначаем победителем и пробрасываем в сл. раунд, сбрасывем баланс на начальный
                    if($finish_level)
                    {
                        $got_prize = Tournaments::getTempPrizes(1);
                        $SQL="UPDATE participants SET level = {$next_level}, status = 1, place = 1, prize = {$got_prize}, balance = '{$tour[begin_stack]}' WHERE id = '{$id_participant_winner}'  RETURNING id_client";
                        $id_client_user = $connection->createCommand($SQL)->queryRow()['id_client'];

                        if($got_prize > 0)
                         {
                            $SQL="UPDATE users SET balance = (balance + {$got_prize}) WHERE id = {$id_client_user}";
                            $connection->createCommand($SQL)->execute();
                         }
                    }
                     else
                     {

                         $SQL="UPDATE participants SET level = {$next_level}, balance = '{$tour[begin_stack]}' WHERE id = '{$id_participant_winner}'";
                         $connection->createCommand($SQL)->execute();
                     }

                    // сеем оставшихся участников по корзинам
                    // сеем людей
                    if(!$finish_level)
                    {
                        $status_pr = Participants::STATUS_STILL_PLAY;
                        $SQL="SELECT * FROM participants WHERE id_tournament = {$tour[id]} and status = {$status_pr} and level = {$next_level} ORDER BY random()";
                        $allParticipants=$connection->createCommand($SQL)->queryAll();

                        $carts_allowed = floor( count($allParticipants) / $tour['id_format'] ); // $tour['id_format'] - это макс. число участников в корзине
                        $cart_fulled = 0; // устаналиваем счётчик заполненных корзин
                        $carts = array(); // корзины с участниками
                        foreach($allParticipants as $n_participant => $participant)
                        {
                            if(($n_participant+1)%$tour['id_format'] == 0) // заполняем одну корзину целиком
                            {
                                $carts[$cart_fulled][] = $participant['id']; // кладём участника в корзину
                                $cart_fulled++; // метим корзину как ещё одну заполненную
                            }
                            else
                            {
                                //проверяем, не все ли корзину уже заняты
                                if( $cart_fulled == $carts_allowed )
                                {
                                    // корзины все заняты, люди идут в сл. раунд, опишем этот переход ниже
                                    $carts[self::CART_NEXT_FOUND][] = $participant['id'];
                                }
                                else
                                    $carts[$cart_fulled][] = $participant['id']; // кладём участника в корзину
                            }

                            unset($allParticipants[$n_participant]); // заполненного человека удаляем из массива
                        }
                        
                        $current_level = $next_level;
                        // поменяем в базе данных игровок, в рамках посева
                        foreach($carts as $index_cart => $cart)
                        {
                            if($index_cart === self::CART_NEXT_FOUND) //это та корзина, где люди идут в сл. раунд, она всегда будет последней
                            {
                                $current_level++; 
                                $index_cart = 0;
                            }
                                
                               
                                $ids_participants = implode(',', $cart);
                                $SQL="UPDATE participants SET level = {$current_level}, cart = {$index_cart}, balance='{$tour[begin_stack]}' WHERE id in ({$ids_participants})";
                                $connection->createCommand($SQL)->execute();
                        }
                    }
                   
                }

            }
            elseif($time_silince_begin <= $now_timestamp && $tour['paused']==0) // раунд переходит в режим тишины
            {
                // var_dump('silince');
                // set to silince
                // ничего не делаем ,просто ждём окончание раунда
                $SQL="UPDATE tournaments SET paused = 1 WHERE id = '$tour[id]'";
                $connection->createCommand($SQL)->execute();
            }
            // elseif($tour['paused']==2) // uncomment for test data
            //     var_dump('waiting start');
            // elseif($tour['paused']==1)
            //     var_dump('silince running');
            // else
            //     var_dump("running level {$tour[level]}");
            // иначе ничего не делаем, идут турнир


        }

    }
}