<?php

/**
* This is the model class for table "faq".
*
* The followings are the available columns in table 'faq':
    * @property integer $id
    * @property integer $status
*/
class Faq extends EActiveRecord
{
    public $places = array(
            "Заголовок"=>'title',
            "Описание"=>'description',
        );


    public function tableName()
    {
        return 'faq';
    }


    public function rules()
    {
        return array(
            array('status', 'numerical', 'integerOnly'=>true),
            // The following rule is used by search().
            array('id, status', 'safe', 'on'=>'search'),
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


    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'status' => 'Status',
        );
    }



    public function search()
    {
        $criteria=new CDbCriteria;
		$criteria->compare('id',$this->id);
		$criteria->compare('status',$this->status);
        return new CActiveDataProvider($this, array(
            'criteria'=>$criteria,
        ));
    }

    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }


}
