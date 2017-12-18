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
    public $places = array(
            "Заголовок"=>'title',
            "Описание"=>'description',
        );

    public function tableName()
    {
        return 'apppages';
    }


    public function rules()
    {
        return array(
            array('meta_alias', 'length', 'max'=>255),
            array('meta_alias', 'required'),
            array('create_time, update_time', 'safe'),
            // The following rule is used by search().
            array('id, meta_alias, create_time, update_time', 'safe', 'on'=>'search'),
        );
    }


    public function relations()
    {
        $relations = array();
        $lng = Yii::app()->language;
        $cl_name = get_class($this);

        foreach ($this->places as $place) {

            $relations[$place] = array(self::HAS_ONE, 'ContentLang', 'post_id', 
                'condition'=>"model_name = '{$cl_name}' and id_place='{$place}' and id_lang = '{$lng}'");
        }

        // $relations['rooms'] = array(self::HAS_MANY, 'ObjectRooms', 'id_object');

        return $relations;
    }

    public function beforeSave()
    {
        parent::beforeSave();

        if($this->isNewRecord)
        {
            $this->create_time = date("Y-m-d H:i");
        }

        $this->update_time = date("Y-m-d H:i");

        return true;
    }


    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
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
