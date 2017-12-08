<?php

/**
* This is the model class for table "token_user".
*
* The followings are the available columns in table 'token_user':
    * @property integer $id
    * @property integer $id_user
    * @property string $token
    * @property string $last_visit
*/
class TokenUser extends EActiveRecord
{
    public function tableName()
    {
        return 'token_user';
    }


    public function rules()
    {
        return array(
            // array('id', 'required'),
            array('id, id_user', 'numerical', 'integerOnly'=>true),
            array('token', 'length', 'max'=>255),
            array('last_visit', 'safe'),
            // The following rule is used by search().
            array('id, id_user, token, last_visit', 'safe', 'on'=>'search'),
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
            'id_user' => 'Id User',
            'token' => 'Token',
            'last_visit' => 'Last Visit',
        );
    }



    public function search()
    {
        $criteria=new CDbCriteria;
		$criteria->compare('id',$this->id);
		$criteria->compare('id_user',$this->id_user);
		$criteria->compare('token',$this->token,true);
		$criteria->compare('last_visit',$this->last_visit,true);
        return new CActiveDataProvider($this, array(
            'criteria'=>$criteria,
        ));
    }

    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }


}
