<?php

/**
* This is the model class for table "{{users_holders_card}}".
*
* The followings are the available columns in table '{{users_holders_card}}':
    * @property integer $id
    * @property integer $id_user
    * @property integer $id_card
    * @property string $card_number
    * @property string $create_time
    * @property string $update_time
*/
class Usersholderscard extends EActiveRecord
{

    public $not_null = false;

    public function tableName()
    {
        return '{{users_holders_card}}';
    }


    public function rules()
    {
        return array(
            array('id_user, id_card', 'numerical', 'integerOnly'=>true),
            array('card_number, card_number_for_user', 'length', 'max'=>255),
            array('create_time, update_time', 'safe'),
            // The following rule is used by search().
            array('id, id_user, id_card, card_number, create_time, update_time', 'safe', 'on'=>'search'),
        );
    }


    public function relations()
    {
        return array(
            'card' => array(self::BELONGS_TO, 'Mallokocards', 'id_card'),
            
            'user'  => array(self::BELONGS_TO, 'Users', 'id_user'),
        );
    }


    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'id_user' => 'Пользователь',
            'id_card' => 'Карта Malloko',
            'card_number' => 'Номер карты Malloko',
            'create_time' => 'Дата создания',
            'update_time' => 'Дата последнего редактирования',
        );
    }


    public function behaviors()
    {
        return CMap::mergeArray(parent::behaviors(), array(
			'CTimestampBehavior' => array(
				'class' => 'zii.behaviors.CTimestampBehavior',
                'createAttribute' => 'create_time',
                'updateAttribute' => 'update_time',
                'setUpdateOnCreate' => true,
			),
        ));
    }

    public function search()
    {
        $criteria=new CDbCriteria;
		$criteria->compare('id',$this->id);
		$criteria->compare('id_user',$this->id_user);
		$criteria->compare('id_card',$this->id_card);
		$criteria->compare('card_number',$this->card_number,true);
		$criteria->compare('create_time',$this->create_time,true);
		$criteria->compare('update_time',$this->update_time,true);
        // $criteria->addCondition('id_card is null');
        if($this->not_null)
            $criteria->addCondition('id_card is null');

        return new CActiveDataProvider($this, array(
            'criteria'=>$criteria,
            'sort'=>array(
                'defaultOrder'=>'create_time DESC',
              ),
        ));
    }

    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }


}
