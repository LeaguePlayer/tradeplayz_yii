<?php

/**
* This is the model class for table "likes".
*
* The followings are the available columns in table 'likes':
    * @property integer $id
    * @property integer $looks_id
    * @property string $date_create
    * @property integer $users_id
*/
class Likes extends EActiveRecord
{
    
    const LIMIT = 2;


    public function tableName()
    {
        return 'likes';
    }


    public function rules()
    {
        return array(
            array('looks_id, users_id', 'required'),
            array('looks_id, users_id', 'numerical', 'integerOnly'=>true),
            array('date_create', 'safe'),
            // The following rule is used by search().
            array('id, looks_id, date_create, users_id', 'safe', 'on'=>'search'),
        );
    }


    public function relations()
    {
        return array(
                'user'  => array(self::BELONGS_TO, 'Users', 'users_id'),
        );
    }


    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'looks_id' => 'Looks',
            'date_create' => 'Date Create',
            'users_id' => 'Users',
        );
    }



    public function search()
    {
        $criteria=new CDbCriteria;
		$criteria->compare('id',$this->id);
		$criteria->compare('looks_id',$this->looks_id);
		$criteria->compare('date_create',$this->date_create,true);
		$criteria->compare('users_id',$this->users_id);
        return new CActiveDataProvider($this, array(
            'criteria'=>$criteria,
        ));
    }

    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }


}
