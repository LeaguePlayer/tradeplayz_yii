<?php

/**
* This is the model class for table "content_lang".
*
* The followings are the available columns in table 'content_lang':
    * @property integer $id
    * @property string $model_name
    * @property string $wswg_body
    * @property integer $id_lang
    * @property string $id_place
    * @property integer $post_id
*/
class ContentLang extends EActiveRecord
{
    public function tableName()
    {
        return 'content_lang';
    }


    public function rules()
    {
        return array(
            array('post_id', 'numerical', 'integerOnly'=>true),
            array('model_name, wswg_body, id_lang, id_place', 'safe'),
            // The following rule is used by search().
            array('id, model_name, wswg_body, id_lang, id_place, post_id', 'safe', 'on'=>'search'),
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
            'model_name' => 'Model Name',
            'wswg_body' => 'Wswg Body',
            'id_lang' => 'Id Lang',
            'id_place' => 'Id Place',
            'post_id' => 'Post',
        );
    }



    public function search()
    {
        $criteria=new CDbCriteria;
		$criteria->compare('id',$this->id);
		$criteria->compare('model_name',$this->model_name,true);
		$criteria->compare('wswg_body',$this->wswg_body,true);
		$criteria->compare('id_lang',$this->id_lang);
		$criteria->compare('id_place',$this->id_place,true);
		$criteria->compare('post_id',$this->post_id);
        return new CActiveDataProvider($this, array(
            'criteria'=>$criteria,
        ));
    }

    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }

     public function getText()
    {


        return $this->wswg_body;
    }
}
