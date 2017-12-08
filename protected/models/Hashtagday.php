<?php

/**
* This is the model class for table "hashtag_day".
*
* The followings are the available columns in table 'hashtag_day':
    * @property integer $id
    * @property string $dt_date_begin
    * @property string $dt_date_finish
    * @property string $title
    * @property integer $malls_id
    * @property integer $status
*/
class Hashtagday extends EActiveRecord
{
    public function tableName()
    {
        return 'hashtag_day';
    }


    public function rules()
    {
        return array(
            array('title', 'required'),
            array('malls_id, status', 'numerical', 'integerOnly'=>true),
            array('title', 'length', 'max'=>255),
            array('dt_date_begin, dt_date_finish', 'safe'),
            // The following rule is used by search().
            array('id, dt_date_begin, dt_date_finish, title, malls_id, status', 'safe', 'on'=>'search'),
        );
    }


    public function relations()
    {
        return array(
        );
    }


    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'dt_date_begin' => 'Дата начала показа',
            'dt_date_finish' => 'Дата конца показа',
            'title' => 'Хэштег',
            'malls_id' => 'Привязка к ТРЦ',
            'status' => 'Статус',
        );
    }



    public function search()
    {
        $criteria=new CDbCriteria;
		$criteria->compare('id',$this->id);
		$criteria->compare('dt_date_begin',$this->dt_date_begin,true);
		$criteria->compare('dt_date_finish',$this->dt_date_finish,true);
		$criteria->compare('title',$this->title,true);
		$criteria->compare('malls_id',$this->malls_id);
		$criteria->compare('status',$this->status);
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
		if (!empty($this->dt_date_begin))
			$this->dt_date_begin = date('Y-m-d',strtotime($this->dt_date_begin));
		if (!empty($this->dt_date_finish))
			$this->dt_date_finish = date('Y-m-d',strtotime($this->dt_date_finish));
		return parent::beforeSave();
	}

	public function afterFind()
	{
		parent::afterFind();
		if ( in_array($this->scenario, array('insert', 'update')) ) { 
			$this->dt_date_begin = ($this->dt_date_begin !== '0000-00-00' ) ? date('d-m-Y', strtotime($this->dt_date_begin)) : '';
			$this->dt_date_finish = ($this->dt_date_finish !== '0000-00-00' ) ? date('d-m-Y', strtotime($this->dt_date_finish)) : '';
		}
	}

    public function translition()
    {
        return 'Хэштеги дня';
    }
}
