<?php

/**
* This is the model class for table "user_devices".
*
* The followings are the available columns in table 'user_devices':
    * @property integer $id
    * @property string $model_phone
    * @property string $devicetoken
    * @property string $token_push
    * @property integer $id_user
    * @property string $id_os
    * @property integer $device_type
*/
class UserDevices extends EActiveRecord
{
    public function tableName()
    {
        return 'user_devices';
    }


    public function rules()
    {
        return array(
            // array('id', 'required'),
            array('id, id_user, device_type', 'numerical', 'integerOnly'=>true),
            array('model_phone, devicetoken, token_push, id_os', 'length', 'max'=>255),
            // The following rule is used by search().
            array('id, model_phone, devicetoken, token_push, id_user, id_os, device_type', 'safe', 'on'=>'search'),
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
            'model_phone' => 'Model Phone',
            'devicetoken' => 'Device Token',
            'id_user' => 'Id User',
            'id_os' => 'Id Os',
            'device_type' => 'Device Type',
            'token_push' => 'Token Push',
        );
    }



    public function search()
    {
        $criteria=new CDbCriteria;
		$criteria->compare('id',$this->id);
		$criteria->compare('model_phone',$this->model_phone,true);
		$criteria->compare('devicetoken',$this->devicetoken,true);
		$criteria->compare('id_user',$this->id_user);
		$criteria->compare('id_os',$this->id_os,true);
		$criteria->compare('device_type',$this->device_type);
        return new CActiveDataProvider($this, array(
            'criteria'=>$criteria,
        ));
    }

    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }


    public static function registerDevice($attibutes, $id_user)
    {

        $new_user_device = new UserDevices;
        $new_user_device->attributes = $attibutes;
        $new_user_device->id_user = $id_user;
        if($new_user_device->save()) return $new_user_device;
    }

    const TYPE_TOKEN_ALL = 1;
    const TYPE_TOKEN_IOS = 0;
    const TYPE_TOKEN_ANDROID = 2;

    protected static function getTokensByType($type=false)
    {
        $criteria = new CDbCriteria(array('select'=>'id, token_push', 'condition'=>'token_push is NOT NULL'));
        $criteria->group = "token_push";
        $is_counter = false;

        $criteria->addCondition("token_push not in ('SIMULATOR_DEVICE_TOKEN', 'debug', 'null')");

        switch ($type) {
            case self::TYPE_TOKEN_IOS:
                    $criteria->addCondition("device_type = :type");
                    
                    $criteria->params[':type'] = self::TYPE_TOKEN_IOS;
                break;

            case self::TYPE_TOKEN_ANDROID:
                    $criteria->addCondition("device_type = :type");
                    $criteria->params[':type'] = self::TYPE_TOKEN_ANDROID;
                break;
            
            case self::TYPE_TOKEN_ALL:
                    $is_counter = true;
                    
                break;
        }
        return ($is_counter) ? self::model()->count($criteria) : self::model()->findAll($criteria);
    }

    public static function getIphoneTokens()
    {
        return self::getTokensByType(self::TYPE_TOKEN_IOS);
    }

    public static function getAndroidTokens()
    {
        return self::getTokensByType(self::TYPE_TOKEN_ANDROID);
    }

    public static function getCountTokens()
    {
        return self::getTokensByType(self::TYPE_TOKEN_ALL);
    }

}
