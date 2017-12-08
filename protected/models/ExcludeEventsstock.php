<?php

/**
* This is the model class for table "{{exclude_eventsstock}}".
*
* The followings are the available columns in table '{{exclude_eventsstock}}':
    * @property integer $id
    * @property integer $users_id
    * @property integer $post_id
*/
class ExcludeEventsstock extends EActiveRecord
{
    public function tableName()
    {
        return '{{exclude_eventsstock}}';
    }


    public function rules()
    {
        return array(
            array('users_id, post_id', 'numerical', 'integerOnly'=>true),
            // The following rule is used by search().
            array('id, users_id, post_id', 'safe', 'on'=>'search'),
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
            'users_id' => 'ID_пользователя',
            'post_id' => 'ID_акции_события',
        );
    }



    public function search()
    {
        $criteria=new CDbCriteria;
		$criteria->compare('id',$this->id);
		$criteria->compare('users_id',$this->users_id);
		$criteria->compare('post_id',$this->post_id);
        return new CActiveDataProvider($this, array(
            'criteria'=>$criteria,
        ));
    }

    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }


}
