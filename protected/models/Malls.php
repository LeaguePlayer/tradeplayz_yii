<?php

/**
* This is the model class for table "malls".
*
* The followings are the available columns in table 'malls':
    * @property integer $id
    * @property integer $id_type
    * @property string $title
    * @property string $img_logo
*/
class Malls extends EActiveRecord
{
    public function tableName()
    {
        return 'malls';
    }

    const LIMIT = 10;


    public function rules()
    {
        return array(
            array('id_type', 'numerical', 'integerOnly'=>true),
            array('title, img_logo, default_street', 'length', 'max'=>255),
            // The following rule is used by search().
            array('id, id_type, title, img_logo, default_street', 'safe', 'on'=>'search'),
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
            'id_type' => 'Id Type',
            'title' => 'Название',
            'img_logo' => 'Логотип',
            'default_street' => 'Адрес ТРЦ',
        );
    }


    public function behaviors()
    {
        return CMap::mergeArray(parent::behaviors(), array(
			'imgBehaviorLogo' => array(
				'class' => 'application.behaviors.UploadableImageBehavior',
				'attributeName' => 'img_logo',
				'versions' => array(
					'icon' => array(
						'centeredpreview' => array(90, 90),
					),
					'small' => array(
						'resize' => array(200, 180),
					)
				),
			),
        ));
    }

    public function search()
    {
        $criteria=new CDbCriteria;
		$criteria->compare('id',$this->id);
		$criteria->compare('id_type',$this->id_type);
		$criteria->compare('title',$this->title,true);
		$criteria->compare('img_logo',$this->img_logo,true);
        return new CActiveDataProvider($this, array(
            'criteria'=>$criteria,
        ));
    }
    
    // public static function getTypes($n=false)
    // {
    //     $array = array( 'ТРЦ', 'Партнер' );
        
    //     if(is_numeric($n))
    //         return $array[$n];
    //     else
    //         return $array;
        
    // }

    public static function getMalls($only = false, $with_null = false )
    {
        $result = array();
        $models = self::model()->findAll( array( 'order'=>'id_type ASC, title ASC' ) );

        if($with_null) $result[0] = "Не выбран";
       
        foreach($models as $model)
        {
            $result['ТРЦ'][$model->id] = $model->title;
        }

        if(!$only)
            $result['Партнер'][0] = "Является партнеров ВНЕ ТРЦ";



        return $result;
    }

    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }

    public function translition()
    {
        return 'ТРЦ';
    }


}
