<?php

/**
* This is the model class for table "device_token".
*
* The followings are the available columns in table 'device_token':
    * @property integer $id
    * @property string $device
    * @property string $deviceToken
    * @property string $date_create
*/
class DeviceToken extends EActiveRecord
{
    public function tableName()
    {
        return 'device_token';
    }


    public function rules()
    {
        return array(
            array('id', 'required'),
            array('id', 'numerical', 'integerOnly'=>true),
            array('device, deviceToken', 'length', 'max'=>255),
            array('date_create', 'safe'),
            // The following rule is used by search().
            array('id, device, deviceToken, date_create', 'safe', 'on'=>'search'),
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
            'device' => 'Device',
            'deviceToken' => 'Device Token',
            'date_create' => 'Date Create',
        );
    }



    public function search()
    {
        $criteria=new CDbCriteria;
		$criteria->compare('id',$this->id);
		$criteria->compare('device',$this->device,true);
		$criteria->compare('deviceToken',$this->deviceToken,true);
		$criteria->compare('date_create',$this->date_create,true);
        return new CActiveDataProvider($this, array(
            'criteria'=>$criteria,
        ));
    }

    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }


}
