<?php

/**
* This is the model class for table "tournament_bets".
*
* The followings are the available columns in table 'tournament_bets':
    * @property integer $id
    * @property integer $id_participants
    * @property integer $id_type_bet
    * @property string $sizing
    * @property string $result
    * @property integer $id_tournament
    * @property string $create_time
*/
class TournamentBets extends EActiveRecord
{
    public function tableName()
    {
        return 'tournament_bets';
    }


    public function rules()
    {
        return array(
            array('id_participants, id_type_bet, id_tournament', 'numerical', 'integerOnly'=>true),
            array('sizing, result', 'length', 'max'=>8),
            array('create_time, value_when_was_bet', 'safe'),
            // The following rule is used by search().
            array('id, id_participants, id_type_bet, sizing, result, id_tournament, create_time', 'safe', 'on'=>'search'),
        );
    }


    public function relations()
    {
        return array(
            'participant' => array(self::BELONGS_TO, 'Participants', 'id_participants'),
        );
    }


    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'id_participants' => 'Id Participants',
            'id_type_bet' => 'Id Type Bet',
            'sizing' => 'Sizing',
            'result' => 'Result',
            'id_tournament' => 'Id Tournament',
            'create_time' => 'Create Time',
        );
    }

    public function beforeSave()
    {
        parent::beforeSave();

        if($this->isNewRecord)
        {
            $last_graph = $this->actualGraphData();
            $this->value_when_was_bet = $last_graph->coord_y;
            $this->create_time = $last_graph->coord_x;
            return true;
        }
        else return false;

        
    }

    public function actualGraphData() // функцицию надо расширить, когда будут появляться другие валюты для игры!!
    {
        $graph = Graph::model()->find(array(
                'select'=>'DISTINCT ON (coord_x) coord_x, coord_y',
                'order'=>"coord_x DESC",
                'condition'=>"coord_x <= :time_bet",
                'params'=>array(':time_bet'=>$this->create_time),
            ));

        return $graph;
    }

    public function search()
    {
        $criteria=new CDbCriteria;
		$criteria->compare('id',$this->id);
		$criteria->compare('id_participants',$this->id_participants);
		$criteria->compare('id_type_bet',$this->id_type_bet);
		$criteria->compare('sizing',$this->sizing,true);
		$criteria->compare('result',$this->result,true);
		$criteria->compare('id_tournament',$this->id_tournament);
		$criteria->compare('create_time',$this->create_time,true);
        return new CActiveDataProvider($this, array(
            'criteria'=>$criteria,
        ));
    }

    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }


}
