<?php

/**
* This is the model class for table "party_malloko".
*
* The followings are the available columns in table 'party_malloko':
    * @property integer $id
    * @property integer $shops_id
    * @property string $discount
*/
class PartyMalloko extends EActiveRecord
{
    public function tableName()
    {
        return 'party_malloko';
    }


    public function rules()
    {
        return array(
            array('shops_id', 'required'),
            array('shops_id', 'numerical', 'integerOnly'=>true),
            array('discount', 'length', 'max'=>10),
            // The following rule is used by search().
            array('id, shops_id, discount', 'safe', 'on'=>'search'),
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
            'shops_id' => 'Shops',
            'discount' => 'Discount',
        );
    }



    public function search()
    {
        $criteria=new CDbCriteria;
		$criteria->compare('id',$this->id);
		$criteria->compare('shops_id',$this->shops_id);
		$criteria->compare('discount',$this->discount,true);
        return new CActiveDataProvider($this, array(
            'criteria'=>$criteria,
        ));
    }

    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }


}
