<?php

/**
* This is the model class for table "events_stock".
*
* The followings are the available columns in table 'events_stock':
    * @property integer $id
    * @property integer $id_type
    * @property string $title
    * @property string $description
    * @property string $dttm_date_start
    * @property string $dttm_date_finish
    * @property string $dttm_date_hide
    * @property integer $status
    * @property integer $shops_id
*/
class Eventsstock extends EActiveRecord
{

    const LIMIT = 50;

    // константы отвечают за отображение записи на экране в приложении
    const SHOW_NORMAL = 1; // значит что показывается нормально, стандартным цветом
    const SHOW_HIDDEN = 0; // значит что показывается блекло, событие уже прошло

    public function tableName()
    {
        return 'events_stock';
    }


    public function rules()
    {
        return array(
            array('title', 'required'),
            array('id_type, status, shops_id, malls_id', 'numerical', 'integerOnly'=>true),
            array('title', 'length', 'max'=>255),
            array('description, dttm_date_start, dttm_date_finish, dttm_date_hide', 'safe'),
            // The following rule is used by search().
            array('id, id_type, title, description, dttm_date_start, dttm_date_finish, dttm_date_hide, status, shops_id', 'safe', 'on'=>'search'),
        );
    }


    public function relations()
    {
        return array(
            // 'favorite_rel'  => array(self::HAS_ONE, 'UsersFavorites', 'post_id', 'condition'=>"post_type='events'"),
            // 'shop'  => array(self::BEGLONGS_TO, 'Shops', 'shops_id'),
            'shop' => array(self::BELONGS_TO, 'Shops', 'shops_id'),
            'mall' => array(self::BELONGS_TO, 'Malls', 'malls_id'),
        );
    }


    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'id_type' => 'Тип',
            'title' => 'Заголовок',
            'description' => 'Описание',
            'dttm_date_start' => 'Дата начала акции/события/бонуса',
            'dttm_date_finish' => 'Дата окончания акции/события/бонуса',
            'dttm_date_hide' => 'Дата, до которой будет выводится акция/событие/бонус в приложении',
            'status' => 'Статус',
            'shops_id' => 'Привязанный магазин',
            'malls_id' => 'Привязанный ТРЦ',
        );
    }



    public function search()
    {
        $criteria=new CDbCriteria;
        $criteria->compare('id',$this->id);
        $criteria->compare('id_type',$this->id_type);
        $criteria->compare('title',$this->title,true);
        $criteria->compare('description',$this->description,true);
        $criteria->compare('dttm_date_start',$this->dttm_date_start,true);
        $criteria->compare('dttm_date_finish',$this->dttm_date_finish,true);
        $criteria->compare('dttm_date_hide',$this->dttm_date_hide,true);
        $criteria->compare('status',$this->status);
        $criteria->compare('shops_id',$this->shops_id);
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
        if (!empty($this->dttm_date_start))
            $this->dttm_date_start = Yii::app()->date->toMysql($this->dttm_date_start);
        if (!empty($this->dttm_date_finish))
            $this->dttm_date_finish = Yii::app()->date->toMysql($this->dttm_date_finish);
        if (!empty($this->dttm_date_hide))
            $this->dttm_date_hide = Yii::app()->date->toMysql($this->dttm_date_hide);
        return parent::beforeSave();
    }

    public function afterFind()
    {
        parent::afterFind();
        if ( in_array($this->scenario, array('insert', 'update')) ) { 
            $this->dttm_date_start = ($this->dttm_date_start !== '0000-00-00 00:00:00' ) ? date('d-m-Y H:i', strtotime($this->dttm_date_start)) : '';
            $this->dttm_date_finish = ($this->dttm_date_finish !== '0000-00-00 00:00:00' ) ? date('d-m-Y H:i', strtotime($this->dttm_date_finish)) : '';
            $this->dttm_date_hide = ($this->dttm_date_hide !== '0000-00-00 00:00:00' ) ? date('d-m-Y H:i', strtotime($this->dttm_date_hide)) : '';
        }
    }

    const ES_STOCKS = 0;
    const ES_EVENTS = 1;
    const ES_BONUS = 2;

      public static function getTypes($n=false)
    {
        $array = array( self::ES_STOCKS=> 'Акция', self::ES_EVENTS=> 'Событие', self::ES_BONUS=> 'Бонус' );
        
        if(is_numeric($n))
            return $array[$n];
        else
            return $array;
        
    }

    public function translition()
    {
        return "Акции, события, бонусы";
    }
}
