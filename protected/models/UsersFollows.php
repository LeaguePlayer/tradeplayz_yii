<?php

/**
* This is the model class for table "users_follows".
*
* The followings are the available columns in table 'users_follows':
    * @property integer $id
    * @property integer $users_id
    * @property integer $users_follow_id
*/
class UsersFollows extends EActiveRecord
{
    const LIMIT = 10;

    // типы подписок
    const UNFOLLOWED = 0;
    const FOLLOWED = 1;
    const ITSME = 2;

    // это используется в апи при получении списка подписчиков
    const IN_FOLLOWS = "in"; // ПОЛЬЗОВАТЕЛИ подписались на пользователя
    const OUT_FOLLOWS = "out"; // пользователь подписался на ПОЛЬЗОВАТЕЛЕЙ

    public function tableName()
    {
        return 'users_follows';
    }


    public function rules()
    {
        return array(
            array('users_id, users_follow_id', 'required'),
            array('users_id, users_follow_id', 'numerical', 'integerOnly'=>true),
            // The following rule is used by search().
            array('id, users_id, users_follow_id', 'safe', 'on'=>'search'),
        );
    }


    public function relations()
    {
        return array(
                'user' => array(self::BELONGS_TO, 'Users', 'users_id'),
                'user_follow' => array(self::BELONGS_TO, 'Users', 'users_follow_id'),
        );
    }


    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'users_id' => 'Users',
            'users_follow_id' => 'Users Follow',
        );
    }



    public function search()
    {
        $criteria=new CDbCriteria;
		$criteria->compare('id',$this->id);
		$criteria->compare('users_id',$this->users_id);
		$criteria->compare('users_follow_id',$this->users_follow_id);
        return new CActiveDataProvider($this, array(
            'criteria'=>$criteria,
        ));
    }

    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }


}
