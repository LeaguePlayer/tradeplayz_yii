<?php

/**
* This is the model class for table "tournaments".
*
* The followings are the available columns in table 'tournaments':
    * @property integer $id
    * @property string $dttm_begin
    * @property integer $status
    * @property integer $prize_places
    * @property string $byuin
    * @property integer $id_format
    * @property integer $id_currency
    * @property string $prize_pool
    * @property string $dttm_finish
    * @property string $begin_stack
*/
class Tournaments extends EActiveRecord
{
    public $places = array(
            "Правила"=>'rulezzz',
        );


    public function tableName()
    {
        return 'tournaments';
    }

     // Статусы в базе данных
    const STATUS_NOT_PUBLISH = 0;
    const STATUS_PUBLISH = 1;
    const STATUS_FINISHED = 2;
    const STATUS_REMOVED = 3;
    const STATUS_DEFAULT = self::STATUS_PUBLISH;

    const ALLOWED_STATUSES = array(
            Tournaments::STATUS_PUBLISH,
            Tournaments::STATUS_FINISHED,
        );


    public static function getStatusAliases($status = -1)
    {
        $aliases = array(
            self::STATUS_NOT_PUBLISH => Yii::t('main','status_not_publish'),
            self::STATUS_PUBLISH => Yii::t('main','status_publish'),
            self::STATUS_FINISHED => Yii::t('main','status_finished'),
            self::STATUS_REMOVED => Yii::t('main','status_removed'),
        );

        if ($status > -1)
            return $aliases[$status];

        return $aliases;
    }


    // ФОРМАТЫ
    const FORMAT_2X2 = 2;
    const FORMAT_3X3 = 3;
    const FORMAT_4X4 = 4;


    public static function getFormats($status = -1)
    {
        $aliases = array(
            self::FORMAT_2X2 => Yii::t('main','2_max'),
            self::FORMAT_3X3 => Yii::t('main','3_max'),
            self::FORMAT_4X4 => Yii::t('main','4_max'),
        );

        if ($status > -1)
            return $aliases[$status];

        return $aliases;
    }




    public function rules()
    {
        return array(
            array('status, prize_places, id_format, id_currency', 'numerical', 'integerOnly'=>true),
            array('byuin, prize_pool, begin_stack', 'length', 'max'=>8),
            array('dttm_begin, prize_pool, begin_stack, byuin', 'required'),
            array('dttm_begin, dttm_finish', 'safe'),
            // The following rule is used by search().
            array('id, dttm_begin, status, prize_places, byuin, id_format, id_currency, prize_pool, dttm_finish, begin_stack', 'safe', 'on'=>'search'),
        );
    }


    public static function getById( $id )
    {
        $model = self::model()->findByPk($id);

        if(is_object($model))
        {
            if( $model->status == self::STATUS_PUBLISH )
            {
                // status ok
                return $model; // return model
            }
            else
                return Yii::t('main','registr_restrict');
        }
        else 
            return Yii::t('main','tour_no_found');
    }


    public function relations()
    {
        $relations = array();
        $lng = Yii::app()->language;
        $cl_name = get_class($this);

        foreach ($this->places as $place) {

            $relations[$place] = array(self::HAS_ONE, 'ContentLang', 'post_id', 
                'condition'=>"model_name = '{$cl_name}' and id_place='{$place}' and id_lang = '{$lng}'");
        }

        // $relations['rooms'] = array(self::HAS_MANY, 'ObjectRooms', 'id_object');

        return $relations;
    }


    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'dttm_begin' => 'Dttm Begin',
            'status' => 'Status',
            'prize_places' => 'Prize Places, in %',
            'byuin' => 'Byuin',
            'id_format' => 'Id Format',
            'id_currency' => 'Id Currency',
            'prize_pool' => 'Prize Pool',
            'dttm_finish' => 'Dttm Finish',
            'begin_stack' => 'Begin Stack',
        );
    }



    public function search()
    {
        $criteria=new CDbCriteria;
		$criteria->compare('id',$this->id);
		$criteria->compare('dttm_begin',$this->dttm_begin,true);
		$criteria->compare('status',$this->status);
		$criteria->compare('prize_places',$this->prize_places);
		$criteria->compare('byuin',$this->byuin,true);
		$criteria->compare('id_format',$this->id_format);
		$criteria->compare('id_currency',$this->id_currency);
		$criteria->compare('prize_pool',$this->prize_pool,true);
		$criteria->compare('dttm_finish',$this->dttm_finish,true);
		$criteria->compare('begin_stack',$this->begin_stack,true);
        return new CActiveDataProvider($this, array(
            'criteria'=>$criteria,
        ));
    }

    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }

	public function beforeSave()
	{

		if (!empty($this->dttm_begin))
			$this->dttm_begin = date("Y-m-d H:i",strtotime($this->dttm_begin));
		if (!empty($this->dttm_finish))
            $this->dttm_finish = date("Y-m-d H:i",strtotime($this->dttm_begin));
        else
             $this->dttm_finish = null;

		return parent::beforeSave();
	}

    public static function getAllPlayersByTourId( $id )
    {
        return Participants::model()->count("id_tournament = :id_tournament", array(
                        ':id_tournament'=>$id,
                    ));
    }

    public static function getAllPrizes( $all_players_in_tour, $percent_in_the_money, $limit = false )
    {
        // запилить механику распределяющую


        return array(
                1=>"54 TPZ",
                2=>"34 TPZ",
                3=>"24 TPZ",
                4=>"14 TPZ",
                5=>"4 TPZ",
            );  
    }



    public function getAllRegistredPlayersByTourId( $id_tour, $limit = false )
    {
        $status_word_still_play = Participants::getStatusAliases( Participants::STATUS_STILL_PLAY );
        $status_word_finished = Participants::getStatusAliases( Participants::STATUS_FINISHED );

        $criteria = new CDbCriteria;
        $criteria->addCondition("id_tournament = :id_tournament");
        $criteria->params[":id_tournament"] = $id_tour;
        $criteria->order = "place ASC";
        $criteria->select = "row_number() OVER (), (CASE p.status WHEN 0 THEN '{$status_word_still_play}' WHEN 1 THEN '{$status_word_finished}' END) as status, (SELECT concat(lastname, ' ', firstname) as fullname  FROM Users u WHERE u.id = p.id_client)";
        if(is_numeric($limit))
            $criteria->limit = $limit;

        // $command = Yii::app()->db->createCommand('SET @row_number = 0;')->queryAll();
        $data = Yii::app()->db->createCommand()->select( $criteria->select )
                                       ->from(Participants::model()->tableName(). " as p")
                                       ->where($criteria->condition, $criteria->params)
                                       ->order($criteria->order)
                                       ->queryAll();

        return $data;

    }

    public function checkParticipantRegistrationByUserId( $id ){
        $model = Participants::model()->find("id_tournament = :id_tournament and id_client = :id_client", array(
                ":id_tournament"=>$this->id,
                ":id_client"=>$id,
            ));
        return is_object( $model ) ? true : false;
    }


	public function afterFind()
	{
		parent::afterFind();
		// if ( in_array($this->scenario, array('insert', 'update')) ) { 
		// 	$this->dttm_begin = ($this->dttm_begin !== '0000-00-00 00:00:00' ) ? date('d-m-Y H:i', strtotime($this->dttm_begin)) : '';
		// 	$this->dttm_finish = ($this->dttm_finish !== '0000-00-00 00:00:00' ) ? date('d-m-Y H:i', strtotime($this->dttm_finish)) : '';
		// }
	}
}
