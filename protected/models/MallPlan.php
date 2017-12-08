<?php

/**
* This is the model class for table "mall_plan".
*
* The followings are the available columns in table 'mall_plan':
    * @property integer $id
    * @property integer $malls_id
    * @property integer $floor_room
    * @property string $img_map
    * @property string $json_areas
    * @property integer $status
*/
class MallPlan extends EActiveRecord
{
    public function tableName()
    {
        return 'mall_plan';
    }


    public function rules()
    {
        return array(
            array('malls_id, floor_name, floor_room', 'required'),
            array('malls_id, floor_room, status', 'numerical', 'integerOnly'=>true),
            array('img_map, floor_name', 'length', 'max'=>255),
            array('json_areas', 'safe'),
            // The following rule is used by search().
            array('id, malls_id, floor_room, img_map, json_areas, status', 'safe', 'on'=>'search'),
        );
    }


    public function relations()
    {
        return array(
            'mall'=>array(self::BELONGS_TO, 'Malls', 'malls_id'),
        );
    }


    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'malls_id' => 'ТРЦ',
            'floor_name' => 'Название этажа',
            'floor_room' => 'Номер этажа',
            'img_map' => 'Картинка этажа',
            'json_areas' => 'Json Areas',
            'status' => 'Статус',
        );
    }


    public function behaviors()
    {
        return CMap::mergeArray(parent::behaviors(), array(
			'imgBehaviorMap' => array(
				'class' => 'application.behaviors.UploadableImageBehavior',
				'attributeName' => 'img_map',
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
		$criteria->compare('malls_id',$this->malls_id);
		$criteria->compare('floor_room',$this->floor_room);
		$criteria->compare('img_map',$this->img_map,true);
		$criteria->compare('json_areas',$this->json_areas,true);
		$criteria->compare('status',$this->status);
        return new CActiveDataProvider($this, array(
            'criteria'=>$criteria,
        ));
    }

    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }

    public $favorites = false;
    public function coordinatesXY()
    {
        $array_vector_coordinates = unserialize($this->json_areas);
        $result = array();
        $myFavoritesArray = array();


        
        //получаем избранное
        
        foreach($this->favorites as $id_favorite => $post_id_favorite)
            $myFavoritesArray[$id_favorite] = $post_id_favorite;
        
        $z = 0;
        $all_binds = BindsShopArea::model()->findAll("shops_id is not null and id_plan = :id_plan", [':id_plan'=>$this->id]);
        

        foreach($all_binds as $bind)
        {
            if(is_null($bind->shop->id)) continue;
            
            if(!empty($array_vector_coordinates[$bind->area_id]))
            {
                $v_coords = $array_vector_coordinates[$bind->area_id];
                $coords_area = array();
                $v_coords = str_replace(['M', 'Z'], '', $v_coords);
                $points = explode('L', $v_coords);
                $n = 0;
                foreach($points as $coord_point)
                {
                    $xy = explode(',', $coord_point);
                    
                    $coords_area[$n]['x'] = $xy[0];
                    $coords_area[$n]['y'] = $xy[1];
                    $n++;
                }
                // проверяем магазин в избранном или нет
                    if(!empty($myFavoritesArray))
                    {
                        if(in_array($bind->shop->id, $myFavoritesArray))
                            $is_favorite = true;
                        else
                            $is_favorite = false;
                    }
                    else 
                        $is_favorite = false;

                $result[$z]['shop']['id'] = $bind->shop->id;
                $result[$z]['shop']['title'] = $bind->shop->title;
                $result[$z]['shop']['category'] = $bind->shop->category->title;
                $result[$z]['shop']['is_favorite'] = $is_favorite;
                $result[$z]['id_area'] = $bind->area_id;
                $result[$z]['coords'] = $coords_area;
                $z++;
            }
            
        }
        

        
        return $result;
    }

    public function translition()
    {
        return 'Этаж';
    }

    // public function beforeDelete()
    // {
    //     // parent::beforeDelete();

    //     foreach($this->imgBehaviorMap->versions as $version_name => $attrs)
    //     {
    //         // var_dump(dirname(__FILE__));
    //         $filePath = dirname(__FILE__)."".$this->imgBehaviorMap->getImageUrl($version_name);
    //         var_dump(is_file($filePath));
    //         var_dump(is_file('/Applications/MAMP/htdocs/malloko.loc/media/images/mallplan/thumbs/icon_77b0eada4.jpg'));
    //         var_dump($filePath);
    //         die();
    //     }

    //     die('X');

    //     die('dd');

    //     return true;
    // }
}
