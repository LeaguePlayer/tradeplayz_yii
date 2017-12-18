<?php

class TournamentsController extends AdminController
{
	public function actionTest($id_tour)
	{
		Yii::app()->clientScript->registerScriptFile($this->getAssetsUrl() . '/js/test.js', CClientScript::POS_END);
		Yii::app()->clientScript->registerCssFile($this->getAssetsUrl().'/css/test.css');

		$this->render("test",array(
				'id_tour'=>$id_tour,
			));
	}

	public function actionDataTest($id_tour)
	{
		$graphCriteria = new CDbCriteria;
		$graphCriteria->order = "coord_x DESC";
		$graphCriteria->limit = "50";

		$tour = Tournaments::model()->findByPk($id_tour);
		$status = Tournaments::getStatusAliases($tour->status);
		$tourdata = "<div class='tour'>Tour: {$tour->id} / Status: {$status} / Level: {$tour->level}</div>";

		$graphs = Graph::model()->findAll($graphCriteria);
		$graph_array = "<table class='tbl'>";
		foreach($graphs as $graph){

			$graph_array .= "<tr>";
			$graph_array .= "<td>X: {$graph->coord_x}; Y: {$graph->coord_y}</td>";
			$graph_array .= "</tr>";
		}
		$graph_array .= "</table>";



		$usersCriteria = new CDbCriteria;
		$usersCriteria->order = "status ASC, place ASC";
		$usersCriteria->addCondition("id_tournament = {$id_tour}");

		$participants = Participants::model()->findAll($usersCriteria);
		$participant_array = "<table class='tbl'>";
		$participant_array .= "<thead><tr><td>place/name/prize/status/balance</td></tr></thead>";
		$participant_array .= "<tbody>";
		foreach($participants as $participant){
			$st = Participants::getStatusAliases($participant->status);
			$participant_array .= "<tr>";
			$participant_array .= "<td class='user_status_{$participant->status}'>{$participant->place} / {$participant->user->firstname} {$participant->user->lastname} / {$participant->prize} / {$st} / {$participant->balance}</td>";
			$participant_array .= "</tr>";
		}
		$participant_array .= "</tbody>";
		$participant_array .= "</table>";


		$betCriteria = new CDbCriteria;
		$betCriteria->order = "create_time DESC";
		$betCriteria->addCondition("id_tournament = {$id_tour}");

		$bets = TournamentBets::model()->findAll($betCriteria);
		$bet_array = "<table class='tbl'>";
		$bet_array .= "<thead><tr><td>name/type/sizing/result</td></tr></thead>";
		$bet_array .= "<tbody>";
		foreach($bets as $bet){
			$st = ($bet->id_type_bet == 0) ? 'DOWN' : "UP";
			$bet_array .= "<tr>";
			$bet_array .= "<td>{$bet->participant->user->firstname} {$bet->participant->user->lastname} / {$st} / {$bet->sizing} / {$bet->result}</td>";
			$bet_array .= "</tr>";
		}
		$bet_array .= "</tbody>";
		$bet_array .= "</table>";

		$content = $tourdata.$graph_array.$participant_array.$bet_array;
		echo $content;
	}
}
