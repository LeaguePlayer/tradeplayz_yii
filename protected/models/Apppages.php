<?php

/**
* This is the model class for table "{{apppages}}".
*
* The followings are the available columns in table '{{apppages}}':
    * @property integer $id
    * @property string $title
    * @property string $wswg_body
    * @property string $meta_alias
    * @property string $create_time
    * @property string $update_time
*/
class Apppages extends EActiveRecord
{
    public function tableName()
    {
        return '{{apppages}}';
    }


    public function rules()
    {
        return array(
            array('title, meta_alias', 'length', 'max'=>255),
            array('wswg_body, create_time, update_time', 'safe'),
            // The following rule is used by search().
            array('id, title, wswg_body, meta_alias, create_time, update_time', 'safe', 'on'=>'search'),
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
            'title' => 'Заголовок',
            'wswg_body' => 'Текст',
            'meta_alias' => 'META_ALIAS',
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
		$criteria->compare('title',$this->title,true);
		$criteria->compare('wswg_body',$this->wswg_body,true);
		$criteria->compare('meta_alias',$this->meta_alias,true);
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

    public function translition()
    {
        return 'Статические страницы';
    }
}
