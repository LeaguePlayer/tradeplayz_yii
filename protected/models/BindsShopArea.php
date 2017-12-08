<?php

/**
* This is the model class for table "{{binds_shop_area}}".
*
* The followings are the available columns in table '{{binds_shop_area}}':
    * @property integer $id
    * @property integer $area_id
    * @property integer $shops_id
    * @property string $create_time
    * @property string $update_time
*/
class BindsShopArea extends EActiveRecord
{
    public function tableName()
    {
        return '{{binds_shop_area}}';
    }


    public function rules()
    {
        return array(
            array('area_id, shops_id, id_plan, id_mall, area_id', 'numerical', 'integerOnly'=>true),
            array('create_time, update_time', 'safe'),
            // The following rule is used by search().
            array('id, area_id, shops_id, create_time, update_time', 'safe', 'on'=>'search'),
        );
    }


    public function relations()
    {
        return array(
                'mallplan'=>array(self::BELONGS_TO, 'MallPlan', 'id_plan'),
                'shop'=>array(self::BELONGS_TO, 'Shops', 'shops_id'),
        );
    }


    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'area_id' => 'ID_AREA',
            'shops_id' => 'Доступные магазины в этом ТРЦ',
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
		$criteria->compare('area_id',$this->area_id);
		$criteria->compare('shops_id',$this->shops_id);
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
