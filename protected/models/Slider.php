<?php

/**
* This is the model class for table "{{slider}}".
*
* The followings are the available columns in table '{{slider}}':
    * @property integer $id
    * @property string $post_type
    * @property integer $post_id
    * @property string $title
    * @property string $sub_title
    * @property string $img_preview
    * @property integer $status
    * @property integer $sort
    * @property string $create_time
    * @property string $update_time
*/
class Slider extends EActiveRecord
{
    public function tableName()
    {
        return '{{slider}}';
    }

    // Статусы в базе данных
    const POST_EVENT = 'events';
    const POST_SHOPS = 'shops';
    const POST_STOCKS = 'stocks';
    const POST_BONUS = 'bonus';
    const POST_LOOKS = 'looks';

    

    public static function getPostType($status = false)
    {
        $aliases = array(
            self::POST_SHOPS => 'Магазин',
            self::POST_EVENT => 'Событие',
            self::POST_STOCKS => 'Акция',
            self::POST_BONUS => 'Бонус',
            self::POST_LOOKS => 'Лук',
        );

        if (is_numeric($status))
            return $aliases[$status];
        else
            return $aliases;
    }


    public function rules()
    {
        return array(
            array('post_id, status, sort', 'numerical', 'integerOnly'=>true),
            array('post_type, title, sub_title, img_preview', 'length', 'max'=>255),
            array('create_time, update_time', 'safe'),
            // The following rule is used by search().
            array('id, post_type, post_id, title, sub_title, img_preview, status, sort, create_time, update_time', 'safe', 'on'=>'search'),
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
            'post_type' => 'POST_TYPE',
            'post_id' => 'POST_ID',
            'title' => 'Заголовок (верхний)',
            'sub_title' => 'Подзагловок (нижний)',
            'img_preview' => 'Изображение на слайдер',
            'status' => 'Статус',
            'sort' => 'Вес для сортировки',
            'create_time' => 'Дата создания',
            'update_time' => 'Дата последнего редактирования',
        );
    }


    public function behaviors()
    {
        return CMap::mergeArray(parent::behaviors(), array(
			'imgBehaviorPreview' => array(
				'class' => 'application.behaviors.UploadableImageBehavior',
                'fakePath'=>true,
                'its_slider'=>true,
				'attributeName' => 'img_preview',
				'versions' => array(
					'icon' => array(
						'centeredpreview' => array(90, 90),
					),
					'iphone' => array(
						'adaptiveResize' => array(320, 181),
					),
                    'iphoneRetina' => array(
                        'adaptiveResize' => array(640, 362),
                    ),

				),
			),
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
		$criteria->compare('post_type',$this->post_type,true);
		$criteria->compare('post_id',$this->post_id);
		$criteria->compare('title',$this->title,true);
		$criteria->compare('sub_title',$this->sub_title,true);
		$criteria->compare('img_preview',$this->img_preview,true);
		$criteria->compare('status',$this->status);
		$criteria->compare('sort',$this->sort);
		$criteria->compare('create_time',$this->create_time,true);
		$criteria->compare('update_time',$this->update_time,true);
        $criteria->order = 'sort';
        return new CActiveDataProvider($this, array(
            'criteria'=>$criteria,
        ));
    }

    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }

    public function saveImage($base64img)
    {
        // echo $type;die();
        // echo $base64img;

        // $file = '../../../../tmp_imgs/3.jpg';

        // $exif = exif_read_data($file, 0, true);
        // echo "{$file}<br />\n";
        // foreach ($exif as $key => $section) {
        //     foreach ($section as $name => $val) {
        //         echo "$key.$name: $val<br />\n";
        //     }
        // }

        // die();

        if (!is_dir("tmp_imgs/"))
            mkdir("tmp_imgs/");

        if (!is_dir("tmp_imgs/{$this->id}"))
            mkdir("tmp_imgs/{$this->id}");

        define('UPLOAD_DIR', "tmp_imgs/{$this->id}/");
        $base64img = str_replace("data:image/png;base64,", '', $base64img);
        $data = base64_decode($base64img);

        // $format = explode('/',$file_type);

        // var_dump($format);
        $file = UPLOAD_DIR . 'slide.png';//. $format[1];

        file_put_contents($file, $data);

        


        // var_dump(Yii::getAlias('@webroot'));
        return $file;
    }

    public function translition()
    {
        return 'Слайдер';
    }

}
