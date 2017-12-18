<?php

/**
* This is the model class for table "participants".
*
* The followings are the available columns in table 'participants':
    * @property integer $id
    * @property integer $id_client
    * @property integer $id_tournament
    * @property integer $status
    * @property string $balance
    * @property integer $place
    * @property string $prize
    * @property string $create_time
    * @property integer $level
*/
class Participants extends EActiveRecord
{
    public function tableName()
    {
        return 'participants';
    }


     // Статусы в базе данных
    const STATUS_STILL_PLAY = 0;
    const STATUS_FINISHED = 1;



    public static function getStatusAliases($status = -1)
    {
        $aliases = array(
            self::STATUS_STILL_PLAY => Yii::t('main','status_participant_still_play'),
            self::STATUS_FINISHED => Yii::t('main','status_participant_finished'),
        );

        if ($status > -1)
            return $aliases[$status];

        return $aliases;
    }


    public function rules()
    {
        return array(
            array('id_client, id_tournament, status, place, level, cart', 'numerical', 'integerOnly'=>true),
            array('balance, prize', 'length', 'max'=>8),
            array('create_time', 'safe'),
            // The following rule is used by search().
            array('id, id_client, id_tournament, status, balance, cart, place, prize, create_time, level', 'safe', 'on'=>'search'),
        );
    }



    public function beforeSave()
    {

        if ($this->isNewRecord)
        {
            $this->create_time = date("Y-m-d H:i:s");
            $this->status = self::STATUS_STILL_PLAY;
            $this->level = 0; // begin level stage
        }

        return parent::beforeSave();
    }


    public function relations()
    {
        return array(
            'tournament' => array(self::BELONGS_TO, 'Tournaments', 'id_tournament'),
            'user' => array(self::BELONGS_TO, 'Users', 'id_client'),
        );
    }


    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'id_client' => 'Id Client',
            'id_tournament' => 'Id Tournament',
            'status' => 'Status',
            'balance' => 'Balance',
            'place' => 'Place',
            'prize' => 'Prize',
            'create_time' => 'Create Time',
            'level' => 'Level',
        );
    }



    public function search()
    {
        $criteria=new CDbCriteria;
		$criteria->compare('id',$this->id);
		$criteria->compare('id_client',$this->id_client);
		$criteria->compare('id_tournament',$this->id_tournament);
		$criteria->compare('status',$this->status);
		$criteria->compare('balance',$this->balance,true);
		$criteria->compare('place',$this->place);
		$criteria->compare('prize',$this->prize,true);
		$criteria->compare('create_time',$this->create_time,true);
		$criteria->compare('level',$this->level);
        return new CActiveDataProvider($this, array(
            'criteria'=>$criteria,
        ));
    }

    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }

     public static function getModelByTourIdAndByUserId($id_tour, $id_client)
    {
       // return false;
       return self::model()->find("id_client = :id_client and id_tournament = :id_tournament", array(
            ':id_tournament'=>$id_tour,
            ':id_client'=>$id_client,
        ));
    }


}
