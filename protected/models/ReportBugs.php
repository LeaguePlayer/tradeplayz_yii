<?php

/**
* This is the model class for table "{{report_bugs}}".
*
* The followings are the available columns in table '{{report_bugs}}':
    * @property integer $id
    * @property string $error
    * @property string $message
    * @property string $token
    * @property integer $id_user
    * @property string $create_time
*/
class ReportBugs extends EActiveRecord
{
    public function tableName()
    {
        return '{{report_bugs}}';
    }


    public function rules()
    {
        return array(
            array('id_user', 'numerical', 'integerOnly'=>true),
            array('token', 'length', 'max'=>255),
            array('error, message, create_time', 'safe'),
            // The following rule is used by search().
            array('id, error, message, token, id_user, create_time', 'safe', 'on'=>'search'),
        );
    }


    public function relations()
    {
        return array(
                'user'  => array(self::BELONGS_TO, 'Users', 'id_user'),
        );
    }


    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'error' => 'Ошибка',
            'message' => 'Сообщение',
            'token' => 'TOKEN',
            'id_user' => 'ID_USER',
            'create_time' => 'Дата создания',
        );
    }



    public function search()
    {
        $criteria=new CDbCriteria;
		$criteria->compare('id',$this->id);
		$criteria->compare('error',$this->error,true);
		$criteria->compare('message',$this->message,true);
		$criteria->compare('token',$this->token,true);
		$criteria->compare('id_user',$this->id_user);
		$criteria->compare('create_time',$this->create_time,true);
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
        parent::beforeSave();
        $this->create_time = date("Y-m-d H:i");
        return true;
    }


}
