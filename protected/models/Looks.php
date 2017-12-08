<?php

/**
* This is the model class for table "looks".
*
* The followings are the available columns in table 'looks':
    * @property integer $id
    * @property string $dttm_date_create
    * @property string $img_look
    * @property integer $status
    * @property string $user_id
*/
class Looks extends EActiveRecord
{
    public $sum_likes;
    
    const LIMIT = 20;

    public function tableName()
    {
        return 'looks';
    }


    public function rules()
    {
        return array(
            array('img_look', 'file','types'=>'jpg, gif, png', 'allowEmpty'=>false, 'message'=>'Изображение обязательное для загрузки!', 'on'=>'insert'),
            array('img_look', 'length', 'max'=>255, 'on'=>'insert,update'),
            array('id_foursquare, place_name_foursquare', 'length', 'max'=>255),
            array('status, user_id', 'numerical', 'integerOnly'=>true),
           
            array('dttm_date_create', 'safe'),
            // The following rule is used by search().
            array('id, dttm_date_create, img_look, status, user_id', 'safe', 'on'=>'search'),
        );
    }


    public function relations()
    {
        return array(
           
            'likes'  => array(self::HAS_MANY, 'Likes', 'looks_id', 'joinType'=>'left join'),
            'comments'  => array(self::HAS_MANY, 'Comments', 'looks_id', 'joinType'=>'left join'),
            'user'  => array(self::BELONGS_TO, 'Users', 'user_id'),
            'bind'  => array(self::HAS_ONE, 'BindFoursqaure', 'id_foursquare', 'on'=>'bind.id_foursquare = id_foursquare'),
        );
    }


    public function attributeLabels()
    {
        return array(
            'id' => '№',
            'dttm_date_create' => 'Дата публикации',
            'img_look' => 'Лук',
            'status' => 'Статус',
            'user_id' => 'Пользователь',
        );
    }


    public function behaviors()
    {
        return CMap::mergeArray(parent::behaviors(), array(
			'imgBehaviorLook' => array(
				'class' => 'application.behaviors.UploadableImageBehavior',
				'attributeName' => 'img_look',
				'versions' => array(
					'icon' => array(
						'centeredpreview' => array(90, 90),
					),
					'small' => array(
						'resize' => array(200, 180),
					)
				),
			),
        ));
    }

    public function search()
    {
        $criteria=new CDbCriteria;
		$criteria->compare('id',$this->id);
		$criteria->compare('dttm_date_create',$this->dttm_date_create,true);
		$criteria->compare('img_look',$this->img_look,true);
		$criteria->compare('status',$this->status);
		$criteria->compare('user_id',$this->user_id,false);
        return new CActiveDataProvider($this, array(
            'criteria'=>$criteria,
            'sort'=>array(
                'defaultOrder'=>'id DESC',
              ),
        ));
    }

    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }

	public function beforeSave()
	{

		if (!empty($this->dttm_date_create))
			$this->dttm_date_create = Yii::app()->date->toMysql($this->dttm_date_create);
		return parent::beforeSave();
	}

	public function afterFind()
	{
		parent::afterFind();
		if ( in_array($this->scenario, array('insert', 'update')) ) { 
			$this->dttm_date_create = ($this->dttm_date_create !== '0000-00-00 00:00:00' ) ? date('d-m-Y H:i', strtotime($this->dttm_date_create)) : '';
		}
	}

    public function translition()
    {
        return 'Луки';
    }


}
