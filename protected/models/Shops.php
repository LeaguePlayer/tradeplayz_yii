<?php

/**
* This is the model class for table "shops".
*
* The followings are the available columns in table 'shops':
    * @property integer $id
    * @property string $title
    * @property integer $categories_id
    * @property integer $status
    * @property string $homepage
*/
class Shops extends EActiveRecord
{
    public $ex_categories_id;
    const uploadsDirName = '/uploads/';
    const SHOPS_ON_LINE = 3;
    const folder_name = 'shops';
    public $go_to_make_package = true;

    public $discount;


    const LIMIT = 180;

    

    public function tableName()
    {
        return 'shops';
    }


    public function rules()
    {
        return array(
            array('categories_id, title', 'required'),
            array('categories_id, status, id_type', 'numerical', 'integerOnly'=>true),
            array('title, homepage, path_package', 'length', 'max'=>255),
            array('wswg_body', 'safe'),
            // The following rule is used by search().
            array('id, title, categories_id, status, homepage, path_package', 'safe', 'on'=>'search'),
        );
    }


    public function relations()
    {
        return array(
            'altnames'=>array(self::HAS_MANY, 'AlternativeNamesShop', 'shops_id', 'order'=>'altnames.ID ASC'),
            'places'=>array(self::HAS_MANY, 'Place', 'shops_id', 'order'=>'places.ID ASC'),
            'malloko'=>array(self::HAS_ONE, 'PartyMalloko', 'shops_id'),
            'category'=>array(self::BELONGS_TO, 'Categories', 'categories_id'),
            'area_mall'=>array(self::HAS_ONE, 'BindsShopArea', 'shops_id'),
            'events_stock'=>array(self::HAS_MANY, 'Eventsstock', 'shops_id'),
        );
    }


    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'id_type' => 'Тип',
            'title' => 'Название',
            'categories_id' => 'Категория',
            'status' => 'Статус',
            'homepage' => 'Домашняя страничка',
            'wswg_body' => 'Текст',
            'discount' => 'Скидка по карте Malloko',
            
        );
    }



    public function search()
    {
        // echo $this->ex_categories_id;die('d');
        $criteria=new CDbCriteria;
		$criteria->compare('id',$this->id);
		$criteria->compare('title',$this->title,true);
		$criteria->compare('categories_id',$this->categories_id);
		$criteria->compare('status',$this->status);
        $criteria->compare('homepage',$this->homepage,true);
        $criteria->compare('path_package',$this->path_package,false);
		$criteria->compare('discount',$this->discount,true);
        return new CActiveDataProvider($this, array(
            'criteria'=>$criteria,
        ));
    }

    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }

    public function afterDelete()
    {
        parent::afterDelete();
        
        AlternativeNamesShop::model()->deleteAll("shops_id = {$this->id}");
        
        return true;
    }

    public static function getShops($n = false, $with_null = false)
    {
        $models = self::model()->findAll( array('condition'=>'status = :status', 'params'=>array(':status'=>self::STATUS_PUBLISH)) );
        $array = array();
        if($with_null) $array[0] = "Не выбран";
        foreach ($models as $model)
        {
            $array[$model->id] = $model->title;
        }
        //$array = CHtml::listData($models, 'id', 'title');

        

        if(is_numeric($n))
                return $array[$n];
            else
                return $array;
    }

    public static function getTypes($n = false)
    {
        
        $array = array(
            "Магазин",
            "Кафе",
            "Салон сотовой связи", 
            "Артека", "Развление", 
            "Продукты питания", 
            "Цветочный магазин", 
            "Парикмахерская", 
            "Продуктовый магазин",
            "Ателье");
        
        

        

        if(is_numeric($n))
                return $array[$n];
            else
                return $array;
    }

    public static function getFolderName( $id, $title )
    {
        $translit_name = SiteHelper::translit($title);
        $translit_name = preg_replace('%[^A-Za-zА-Яа-я0-9]%', '', $translit_name); 
        $folder_name = "{$translit_name}_{$id}";

        return $folder_name;
    }

    public function getPackageUrl()
    {
        $folder_name = self::getFolderName( $this->id, $this->title );
        $prefix = ($this->malloko->discount > 0) ? "dis_" : "";

        $uploadsDirName = self::uploadsDirName;
        $ff = self::folder_name;

        return "{$uploadsDirName}{$ff}/{$folder_name}/{$prefix}{$this->path_package}";
    }

    public static function getPackageUrlStatic( $discount, $id, $title, $path_package )
    {
        $folder_name = self::getFolderName( $id, $title );
        $prefix = ($discount > 0) ? "dis_" : "";
        // return $folder_name;
        // return self::uploadsDirName;

        $uploadsDirName = self::uploadsDirName;
        $ff = self::folder_name;


        return "{$uploadsDirName}{$ff}/{$folder_name}/{$prefix}{$path_package}";
    }

    public function getSmallPackageUrl()
    {
        $folder_name = self::getFolderName( $this->id, $this->title );

        $uploadsDirName = self::uploadsDirName;
        $ff = self::folder_name;

        return "{$uploadsDirName}{$ff}/{$folder_name}/thumbs/{$this->path_package}";
    }


    

    public function afterSave()
    {
        parent::afterSave();
        
        
        if(!$this->isNewRecord)
        {
            if($this->ex_categories_id == $this->categories_id) $this->go_to_make_package = false;
        }

        

        return true;
    }

    public function translition()
    {
        return 'Магазины';
    }

}
