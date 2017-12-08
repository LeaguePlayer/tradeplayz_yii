<?php

/**
* This is the model class for table "{{notification}}".
*
* The followings are the available columns in table '{{notification}}':
    * @property integer $id
    * @property string $text
    * @property string $create_date
*/
class Notification extends EActiveRecord
{

    public function tableName()
    {
        return '{{notification}}';
    }


    public function rules()
    {
        return array(
            array('text', 'required'),
            array('text', 'match', 'pattern' => '/^[\p{L}\s,a-z,A-Z,А-Я,а-я,\-,0-9,\,,\!,\.]+$/u'),
            array('text', 'length', 'max'=>95),
            array('text, create_date', 'safe'),
            // The following rule is used by search().
            array('id, text, create_date', 'safe', 'on'=>'search'),
        );
    }

    public function behaviors(){
        return array(
            'CTimestampBehavior' => array(
                'class' => 'zii.behaviors.CTimestampBehavior',
                'createAttribute' => 'create_date',
            )
        );
    }


    public function relations()
    {
        return array();
    }

    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'text' => 'Текст уведомления (95 символов)',
            'create_date' => 'Дата создания PUSH сообщения',
            'send' => 'Сделать рассылку',
        );
    }

    public function search()
    {
        $criteria=new CDbCriteria;
		$criteria->compare('id',$this->id);
		$criteria->compare('text',$this->text);
		$criteria->compare('create_date',$this->create_date);
        $criteria->order = 'id DESC';
        return new CActiveDataProvider($this, array(
            'criteria'=>$criteria,
        ));
    }

    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }


}
