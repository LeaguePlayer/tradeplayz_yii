<?php

/**
* This is the model class for table "place_phone".
*
* The followings are the available columns in table 'place_phone':
    * @property integer $id
    * @property string $phone
    * @property integer $place_id
*/
class PlacePhone extends EActiveRecord
{
    public function tableName()
    {
        return 'place_phone';
    }


    public function rules()
    {
        return array(
          //  array('place_id', 'required'),
            array('place_id', 'numerical', 'integerOnly'=>true),
            array('phone', 'length', 'max'=>255),
            // The following rule is used by search().
            array('id, phone, place_id', 'safe', 'on'=>'search'),
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
            'phone' => 'Phone',
            'place_id' => 'Place',
        );
    }



    public function search()
    {
        $criteria=new CDbCriteria;
		$criteria->compare('id',$this->id);
		$criteria->compare('phone',$this->phone,true);
		$criteria->compare('place_id',$this->place_id);
        return new CActiveDataProvider($this, array(
            'criteria'=>$criteria,
        ));
    }

    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }


}
