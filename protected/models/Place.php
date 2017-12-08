<?php

/**
* This is the model class for table "place".
*
* The followings are the available columns in table 'place':
    * @property integer $id
    * @property string $street
    * @property integer $shops_id
    * @property integer $malls_id
    * @property integer $status
*/
class Place extends EActiveRecord
{
    public function tableName()
    {
        return 'place';
    }

     public static function getStatusAliases($status = -1)
    {
        $aliases = array(
            self::STATUS_CLOSED => 'Не опубликовано',
            self::STATUS_PUBLISH => 'Опубликовано',
           
        );

        if ($status > -1)
            return $aliases[$status];

        return $aliases;
    }


    public function rules()
    {
        return array(
           // array('shops_id, malls_id', 'required'),
            array('shops_id, malls_id, status', 'numerical', 'integerOnly'=>true),
            array('street', 'length', 'max'=>255),
            // The following rule is used by search().
            array('id, street, shops_id, malls_id, status', 'safe', 'on'=>'search'),
        );
    }


    public function relations()
    {
        return array(
            'phones'=>array(self::HAS_MANY, 'PlacePhone', 'place_id', 'order'=>'ID ASC'),
            'mall'=>array(self::BELONGS_TO, 'Malls', 'malls_id'),
            'shop'=>array(self::BELONGS_TO, 'Shops', 'shops_id'),
            'bind_foursquare'=>array(self::HAS_MANY, 'BindFoursqaure', 'id_place'),
        );
    }


    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'street' => 'Street',
            'shops_id' => 'Shops',
            'malls_id' => 'Malls',
            'status' => 'Status',
        );
    }



    public function search()
    {
        $criteria=new CDbCriteria;
		$criteria->compare('id',$this->id);
		$criteria->compare('street',$this->street,true);
		$criteria->compare('shops_id',$this->shops_id);
		$criteria->compare('malls_id',$this->malls_id);
		$criteria->compare('status',$this->status);
        return new CActiveDataProvider($this, array(
            'criteria'=>$criteria,
        ));
    }

    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }

    public function afterDelete()
    {

        parent::afterDelete();
        
        PlacePhone::model()->deleteAll("place_id = :place_id", array(':place_id'=>$this->id));
        BindFoursqaure::model()->deleteAll("id_place = :place_id", array(':place_id'=>$this->id));

        return true;
    }


}
