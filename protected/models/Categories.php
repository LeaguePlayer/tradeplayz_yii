<?php

/**
* This is the model class for table "categories".
*
* The followings are the available columns in table 'categories':
    * @property integer $id
    * @property string $title
    * @property string $img_preview
    * @property integer $status
*/
class Categories extends EActiveRecord
{

    const LIMIT = 40;


    public function tableName()
    {
        return 'categories';
    }


    public function rules()
    {
        return array(
            array('status', 'numerical', 'integerOnly'=>true),
            array('title, img_preview, img_discount_preview, img_category, color_rgb', 'length', 'max'=>255),
            // The following rule is used by search().
            array('id, title, img_preview, status', 'safe', 'on'=>'search'),
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
            'title' => 'Название',
            'img_preview' => 'Пакет',
            'img_discount_preview' => 'Пакет со скидкой Malloko',
            'img_category' => 'Картинка категории',
            'status' => 'Статус',
        );
    }


    public function behaviors()
    {
        return CMap::mergeArray(parent::behaviors(), array(
			'imgBehaviorPreview' => array(
				'class' => 'application.behaviors.UploadableImageBehavior',
				'attributeName' => 'img_preview',
				'versions' => array(
					'icon' => array(
						'centeredpreview' => array(90, 90),
					),
					   'small' => array(
                        'resize' => array(200, 180),
                    )
				),
			),

            'imgBehaviorDiscountPreview' => array(
                'class' => 'application.behaviors.UploadableImageBehavior',
                'attributeName' => 'img_discount_preview',
                'versions' => array(
                    'icon' => array(
                        'centeredpreview' => array(90, 90),
                    ),
                    'small' => array(
                        'resize' => array(200, 180),
                    )
                ),
            ),

            'imgBehaviorCategory' => array(
                'class' => 'application.behaviors.UploadableImageBehavior',
                'attributeName' => 'img_category',
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
		$criteria->compare('title',$this->title,true);
		$criteria->compare('img_preview',$this->img_preview,true);
		$criteria->compare('status',$this->status);
        return new CActiveDataProvider($this, array(
            'criteria'=>$criteria,
        ));
    }

    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }


    public static function getCategories($n=false)
    {
        $models = self::model()->findAll( array( 'condition'=>"status = :status", 'params'=>array( ':status'=> Categories::STATUS_PUBLISH ) ) );

        $array = CHtml::listData($models, 'id', 'title');
        
        if(is_numeric($n))
            return $array[$n];
        else
            return $array;
        
    }

    public function afterDelete()
    {
        parent::afterDelete();

        $all_shops_by_category = Shops::model()->findAll("categories_id = :categories_id", array(':categories_id'=>$this->id));
        
        
        foreach($all_shops_by_category as $shop)
            $shop->delete();

        

        return true;
    }


    public function translition()
    {
        return 'Товарные группы';
    }


}
