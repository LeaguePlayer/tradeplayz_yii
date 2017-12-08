<?php

/**
* This is the model class for table "comments".
*
* The followings are the available columns in table 'comments':
    * @property integer $id
    * @property integer $looks_id
    * @property string $comment_text
    * @property string $date_create
    * @property integer $users_id
    * @property integer $status
*/
class Comments extends EActiveRecord
{
    public $norm_comment = '';

    public function tableName()
    {
        return 'comments';
    }

    const LIMIT = 10;
    const LIMIT_ON_LOOK = 2;

    // Статусы в базе данных
    const STATUS_IN_REVIEW = 0;
    const STATUS_PUBLISH = 1;
    const STATUS_REMOVED = 3;
    const STATUS_DEFAULT = self::STATUS_PUBLISH;

    public $max_sort;

    public static function getStatusAliases($status = -1)
    {
        $aliases = array(
            self::STATUS_IN_REVIEW => 'На модерации',
            self::STATUS_PUBLISH => 'Опубликовано',
            self::STATUS_REMOVED => 'Удалено',
        );

        if ($status > -1)
            return $aliases[$status];

        return $aliases;
    }

    public function rules()
    {
        return array(
            array('looks_id, users_id, comment_text', 'required', 'message'=>"Поле не может быть пустым"),
            array('looks_id, users_id, status', 'numerical', 'integerOnly'=>true),
            array('comment_text, date_create, source_text', 'safe'),
            // The following rule is used by search().
            array('id, looks_id, comment_text, date_create, users_id, status', 'safe', 'on'=>'search'),
        );
    }


    public function relations()
    {
        return array(
                'user'  => array(self::BELONGS_TO, 'Users', 'users_id'),
        );
    }


    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'looks_id' => 'Лук',
            'comment_text' => 'Текст комментария',
            'norm_comment' => 'Текст комментария',
            'date_create' => 'Дата создания',
            'users_id' => 'Пользователь',
            'status' => 'Статус',
        );
    }

    public function afterFind()
    {
        parent::afterFind();

     


        $a = (array)json_decode('{"t":"'.$this->comment_text.'"}');



        $this->norm_comment =  $a['t'];


    }

    public function search()
    {
        $criteria=new CDbCriteria;
		$criteria->compare('id',$this->id);
		$criteria->compare('looks_id',$this->looks_id);
		$criteria->compare('comment_text',$this->comment_text,true);
		$criteria->compare('date_create',$this->date_create,true);
		$criteria->compare('users_id',$this->users_id);
		$criteria->compare('status',$this->status);
        return new CActiveDataProvider($this, array(
            'criteria'=>$criteria,
            'sort'=>array(
                'defaultOrder'=>'date_create DESC',
              )
        ));
    }

    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }


}
