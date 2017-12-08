<?php

/**
* This is the model class for table "{{bind_foursqaure}}".
*
* The followings are the available columns in table '{{bind_foursqaure}}':
    * @property integer $id
    * @property string $id_foursquare
    * @property integer $id_place
    * @property string $create_time
    * @property string $update_time
*/
class BindFoursqaure extends EActiveRecord
{
    public function tableName()
    {
        return '{{bind_foursqaure}}';
    }


    public function rules()
    {
        return array(
            array('id_place', 'numerical', 'integerOnly'=>true),
            array('id_foursquare', 'length', 'max'=>255),
            array('create_time, update_time', 'safe'),
            // The following rule is used by search().
            array('id, id_foursquare, id_place, create_time, update_time', 'safe', 'on'=>'search'),
        );
    }


    public function relations()
    {
        return array(
            'place'  => array(self::BELONGS_TO, 'Place', 'id_place', 'condition'=>'place.status = :status','params'=>[':status'=>Place::STATUS_PUBLISH]),
        );
    }


    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'id_foursquare' => 'id_foursquare',
            'id_place' => 'id_place',
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
		$criteria->compare('id_foursquare',$this->id_foursquare,true);
		$criteria->compare('id_place',$this->id_place);
		$criteria->compare('create_time',$this->create_time,true);
		$criteria->compare('update_time',$this->update_time,true);
        return new CActiveDataProvider($this, array(
            'criteria'=>$criteria,
        ));
    }

    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }


}
