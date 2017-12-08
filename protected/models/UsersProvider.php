<?php

/**
* This is the model class for table "users_provider".
*
* The followings are the available columns in table 'users_provider':
    * @property integer $id
    * @property integer $id_user
    * @property string $loginprovider
    * @property string $loginprovideridentifier
*/
class UsersProvider extends EActiveRecord
{
    public function tableName()
    {
        return 'users_provider';
    }


    public function rules()
    {
        return array(
            array('id_user', 'numerical', 'integerOnly'=>true),
            array('loginprovider, loginprovideridentifier', 'length', 'max'=>255),
            // The following rule is used by search().
            array('id, id_user, loginprovider, loginprovideridentifier', 'safe', 'on'=>'search'),
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
            'loginprovider' => 'Login Provider',
            'loginprovideridentifier' => 'Login Provider Identifier',
        );
    }



    public function search()
    {
        $criteria=new CDbCriteria;
		$criteria->compare('id',$this->id);
		$criteria->compare('id_user',$this->id_user);
		$criteria->compare('loginprovider',$this->loginprovider,true);
		$criteria->compare('loginprovideridentifier',$this->loginprovideridentifier,true);
        return new CActiveDataProvider($this, array(
            'criteria'=>$criteria,
        ));
    }

    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }


}
