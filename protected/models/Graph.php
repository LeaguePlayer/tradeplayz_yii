<?php

/**
* This is the model class for table "graph".
*
* The followings are the available columns in table 'graph':
    * @property integer $id_currency
    * @property integer $id_currency_to
    * @property string $coord_x
    * @property string $coord_y
*/
class Graph extends EActiveRecord
{
    public function tableName()
    {
        return 'graph';
    }


    public function rules()
    {
        return array(
            array('id_currency, id_currency_to', 'numerical', 'integerOnly'=>true),
            array('coord_y', 'length', 'max'=>8),
            array('coord_x', 'safe'),
            // The following rule is used by search().
            array('id_currency, id_currency_to, coord_x, coord_y', 'safe', 'on'=>'search'),
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
            'id_currency' => 'Id Currency',
            'id_currency_to' => 'Id Currency To',
            'coord_x' => 'Coord X',
            'coord_y' => 'Coord Y',
        );
    }



    public function search()
    {
        $criteria=new CDbCriteria;
		$criteria->compare('id_currency',$this->id_currency);
		$criteria->compare('id_currency_to',$this->id_currency_to);
		$criteria->compare('coord_x',$this->coord_x,true);
		$criteria->compare('coord_y',$this->coord_y,true);
        return new CActiveDataProvider($this, array(
            'criteria'=>$criteria,
        ));
    }

    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }


}
