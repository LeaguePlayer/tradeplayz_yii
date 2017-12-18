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
            "Правила"=>'rules',
        );


    public function tableName()
    {
        return 'tournaments';
    }

    // Типы ставок
    const BET_DOWN = 0; // на понижение
    const BET_UP = 1; // на повышение


     // Статусы в базе данных
    const STATUS_NOT_PUBLISH = 0;
    const STATUS_PUBLISH = 1;
    const STATUS_FINISHED = 2;
    const STATUS_REMOVED = 3;
    const STATUS_PREPARATION = 4; //preparation 
    const STATUS_RUNNING = 5; //RUNNING 
    const STATUS_DEFAULT = self::STATUS_PUBLISH;

    const ALLOWED_STATUSES = array(
            Tournaments::STATUS_PUBLISH,
            Tournaments::STATUS_FINISHED,
            Tournaments::STATUS_PREPARATION,
            Tournaments::STATUS_RUNNING,
        );

    const ALLOWED_FOR_BET = array(
            // Tournaments::STATUS_PUBLISH,
            // Tournaments::STATUS_PREPARATION,
            Tournaments::STATUS_RUNNING,
        );

    const ALLOWED_FOR_REDIRECT = array(
            Tournaments::STATUS_PUBLISH,
            Tournaments::STATUS_PREPARATION,
            Tournaments::STATUS_RUNNING,
        );


    public static function getStatusAliases($status = -1)
    {
        $aliases = array(
            self::STATUS_NOT_PUBLISH => Yii::t('main','status_not_publish'),
            self::STATUS_PUBLISH => Yii::t('main','status_publish'),
            self::STATUS_FINISHED => Yii::t('main','status_finished'),
            self::STATUS_REMOVED => Yii::t('main','status_removed'),
            self::STATUS_PREPARATION => Yii::t('main','status_preparation'),
            self::STATUS_RUNNING => Yii::t('main','status_running'),
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
            array('status, prize_places, id_format, id_currency, level, id_currency_to, paused', 'numerical', 'integerOnly'=>true),
            array('byuin, prize_pool, begin_stack', 'length', 'max'=>8),
            array('dttm_begin, prize_pool, begin_stack, byuin', 'required'),
            array('dttm_begin, dttm_finish', 'safe'),
            // The following rule is used by search().
            array('id, dttm_begin, status, prize_places, byuin, level, id_currency_to, id_format, id_currency, prize_pool, dttm_finish, begin_stack', 'safe', 'on'=>'search'),
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
            'id_currency_to' => 'Id Currency To',
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

    public static function getAllPrizes( $all_players_in_tour, $percent_in_the_money, $limit = false, $prefix = "TPZ" )
    {
        // запилить механику распределяющую
        
        $result = array();

        foreach(self::getTempPrizes() as $i => $prize)
            $result[] = array(
                    'row_number'=>(string)$i,
                    'prize'=>trim("{$prize} {$prefix}"),
                );

        // $max = 50;

        // for($i = 1; $i <= $max; $i++)
        // {
        //     $prize = $max * 10 - $i*9;
        //     $result[] = array(
        //         'row_number'=>(string)$i,
        //         'prize'=>trim("{$prize} {$prefix}"),
        //     );
        // }


        if( is_numeric($limit) && $limit <= count($result) )
           $result = array_slice($result, 0, $limit);
        

        
        return $result;
    }

    public static function getTempPrizes($n = false)
    {
        $result = array(
                1 => 50,
                2 => 20,
                3 => 9,
                4 => 9,
                5 => 1,
                6 => 1,
                7 => 1,
                8 => 1,
                9 => 1,
                10 => 1,
                11 => 1,
                12 => 1,
                13 => 1,
                14 => 1,
            );

        if(is_numeric($n))
            return (is_null($result[$n])) ? 0 : $result[$n];
        else
            return $result;
    }



    public function getAllRegistredPlayersByTourId( $id_tour, $limit = false, $query = false )
    {
        $id_auth_user = Yii::app()->controller->user->id;

        $criteria = new CDbCriteria;
        $criteria->addCondition("id_tournament = :id_tournament");
        $criteria->params[":id_tournament"] = $id_tour;
        $criteria->order = "place ASC";

        if($query)
        {
            $criteriaQuery = new CDbCriteria;
            $criteriaQuery->addCondition("LOWER(concat(lastname, ' ', firstname)) LIKE " .  Yii::app()->db->quoteValue('%' . mb_strtolower($query) . '%') );
            $criteriaQuery->addCondition("id_tournament = :id_tournament");
            $criteriaQuery->params[":id_tournament"] = $id_tour;
            // $criteriaQuery->order = "place ASC NULLS LAST";
            $idsExist = array();
            $filteredData = Yii::app()->db->createCommand()->select( "p.id, place" )
                                       ->from(Participants::model()->tableName(). " as p left join users u on (u.id = p.id_client)")
                                       ->where($criteriaQuery->condition, $criteriaQuery->params)
                                       ->order($criteriaQuery->order)
                                       ->queryAll();

            foreach($filteredData as $n => $d)
                $idsExist[] = $d['id'];

            unset($criteriaQuery);
            // var_dump($idsExist);die();
            // $criteria->addInCondition('p.id', $idsExist);
        }

        $word = Yii::t('main','status_participant_still_play');
        $criteria->select = "(CASE when place is null THEN ''::text ELSE place::text END) as row, (CASE p.status when 0 THEN '{$word}'::text ELSE p.prize::text END) as status, (CASE id_client WHEN {$id_auth_user} THEN 1 ELSE 0 END) as me, concat(lastname, ' ', firstname) as fullname, p.id, p.status as id_status";

        if(is_numeric($limit))
            $criteria->limit = $limit;

        $data = Yii::app()->db->createCommand()->select( $criteria->select )
                                       ->from(Participants::model()->tableName(). " as p left join users u on (u.id = p.id_client)")
                                       ->where($criteria->condition, $criteria->params)
                                       ->order($criteria->order)
                                       ->limit($criteria->limit)
                                       ->queryAll();

                        
        if(!empty($idsExist))
        {
            foreach($data as $i => $d)
                if(!in_array($d['id'], $idsExist))
                    unset($data[$i]);
            $data = array_values(array_filter($data));
        }           
        

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
