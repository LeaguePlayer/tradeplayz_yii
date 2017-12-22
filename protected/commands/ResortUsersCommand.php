<?php

class ResortUsersCommand extends CConsoleCommand {
    public function run($args) {
        // тут делаем то, что нам нужно

        $SQL="SELECT id_client, (SELECT SUM(s.prize::integer) FROM Participants s WHERE s.id_client = p.id_client and create_time BETWEEN NOW() - interval '6 month' and NOW()) as sum_win_tpz FROM Participants p  GROUP BY p.id_client ORDER BY sum_win_tpz DESC";
		$connection=Yii::app()->db; 
		$command=$connection->createCommand($SQL);
		$usersByWins = $command->queryAll(); // execute the non-query SQL

        $sorteredUsers = array();
        $place = 1;
        foreach($usersByWins as $user)
        {
            $sorteredUsers[] = $user['id_client'];
            $SQL="UPDATE users SET rating = {$place} WHERE id = {$user[id_client]}";
            $connection->createCommand($SQL)->execute();
            $place++;
        }

        if(!empty($sorteredUsers))
        {
            $sorteredUsersString = implode(",", $sorteredUsers);
            $SQL = "SELECT * FROM users WHERE id not in ({$sorteredUsersString}) ORDER BY ID ASC";
            $command=$connection->createCommand($SQL);
            $notPlayedUsers = $command->queryAll(); // execute the non-query SQL
            foreach($notPlayedUsers as $user)
            {
                $SQL="UPDATE users SET rating = {$place} WHERE id = {$user['id']}";
                $connection->createCommand($SQL)->execute();
                $place++;
            }

        }
        

    }
}