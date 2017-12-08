<?php

/**
* This is the model class for table "alternative_names_shop".
*
* The followings are the available columns in table 'alternative_names_shop':
    * @property integer $id
    * @property integer $shops_id
    * @property string $title
*/
class AlternativeNamesShop extends EActiveRecord
{
    public function tableName()
    {
        return 'alternative_names_shop';
    }


    public function rules()
    {
        return array(
         
            array('shops_id', 'numerical', 'integerOnly'=>true),
            array('title', 'length', 'max'=>255),
            // The following rule is used by search().
            array('id, shops_id, title', 'safe', 'on'=>'search'),
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
            'title' => 'Title',
        );
    }



    public function search()
    {
        $criteria=new CDbCriteria;
		$criteria->compare('id',$this->id);
		$criteria->compare('shops_id',$this->shops_id);
		$criteria->compare('title',$this->title,true);
        return new CActiveDataProvider($this, array(
            'criteria'=>$criteria,
        ));
    }

    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }


}
