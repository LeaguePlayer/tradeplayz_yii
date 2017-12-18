<?php

/**
* This is the model class for table "{{chat}}".
*
* The followings are the available columns in table '{{chat}}':
    * @property integer $id
    * @property integer $id_user
    * @property string $message
    * @property string $answer
    * @property integer $status
    * @property string $create_time
    * @property string $update_time
*/
class Chat extends EActiveRecord
{
    public function tableName()
    {
        return 'chats';
    }

    // Статусы в базе данных
    const TYPE_QUESTION = 0; // вопрос только задан
    const TYPE_ANSWER = 1; // получен ответ
    



    // Статусы в базе данных
    const STATUS_NEW = 0;
    const STATUS_ANSWERED = 1;
    const STATUS_VIEWED = 2;

    public $max_sort;

    public static function getStatusAliases($status = -1)
    {
        $aliases = array(
            self::STATUS_NEW => 'Создано',
            self::STATUS_ANSWERED => 'Отвечено',
            self::STATUS_VIEWED => 'Просмотрено',
        );

        if ($status > -1)
            return $aliases[$status];

        return $aliases;
    }


    public function rules()
    {
        return array(
            array('id_user, status', 'numerical', 'integerOnly'=>true),
            array('message, answer, create_time, update_time', 'safe'),
            array('message', 'required'),
            // The following rule is used by search().
            array('id, id_user, message, answer, status, create_time, update_time', 'safe', 'on'=>'search'),
        );
    }


    public function relations()
    {
        return array(
            'user'=>array(self::BELONGS_TO,'User','id_user'),
        );
    }


    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'id_user' => 'Пользователь',
            'message' => 'Сообщение для администратора',
            'answer' => 'Ответ',
            'status' => 'Статус',
            'create_time' => 'Дата создания',
            'update_time' => 'Дата последнего редактирования',
        );
    }

    public function beforeSave()
    {
        parent::beforeSave();
        if($this->isNewRecord)
        {
            $this->status = self::STATUS_NEW;
            $this->create_time = date("Y-m-d H:i:s");
        }

        $this->update_time = date("Y-m-d H:i:s");

        // var_dump($this->attributes);die();

        return true;
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
        // var_dump(Yii::app()->user->getId());die();

        if(!Yii::app()->user->isAdmin())
        {
            $criteria->addCondition("id_user = :id_user");
            $criteria->params = array(
                ':id_user' => Yii::app()->user->getId(),
            ); 
        }
       

        $criteria->order = "create_time DESC";
		$criteria->compare('id',$this->id);
		$criteria->compare('id_user',$this->id_user);
		$criteria->compare('message',$this->message,true);
		$criteria->compare('answer',$this->answer,true);
		$criteria->compare('status',$this->status);
		$criteria->compare('create_time',$this->create_time,true);
		$criteria->compare('update_time',$this->update_time,true);
        return new CActiveDataProvider($this, array(
            'criteria'=>$criteria,
        ));
    }

    public static function getAnsweredChats( $id_user )
    {
        $criteria=new CDbCriteria;
            $criteria->addCondition("id_user = :id_user");
            $criteria->addCondition("status = :status");
            $criteria->params = array(
                ':id_user' => $id_user,
                ':status' => Chat::STATUS_ANSWERED,
            ); 

        return self::model()->count($criteria);
    }

    public function translition()
    {
        return 'Сообщения';
    }

    public static function getNewChats()
    {
        $criteria=new CDbCriteria;
            // $criteria->addCondition("id_user = :id_user");
            $criteria->addCondition("status = :status");
            $criteria->params = array(
                // ':id_user' => Yii::app()->user->getId(),
                ':status' => Chat::STATUS_NEW,
            ); 

        return self::model()->count($criteria);
    }

    public static function setAllViewed( $id_user )
    {
        $criteria=new CDbCriteria;
         $criteria->addCondition("id_user = :id_user");
            $criteria->addCondition("status = :status");
            $criteria->params = array(
                ':id_user' => $id_user,
                ':status' => Chat::STATUS_ANSWERED,
            );  

        $models = self::model()->findAll($criteria);
        foreach($models as $m)
        {
            $m->status = Chat::STATUS_VIEWED;
            $m->update();
        }
        return true;
    }

    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }


}
