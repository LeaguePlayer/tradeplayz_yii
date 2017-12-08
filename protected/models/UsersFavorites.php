<?php

/**
* This is the model class for table "users_favorites".
*
* The followings are the available columns in table 'users_favorites':
    * @property integer $id
    * @property integer $users_id
    * @property string $post_type
    * @property integer $post_id
*/
class UsersFavorites extends EActiveRecord
{
    public function tableName()
    {
        return 'users_favorites';
    }


    public function rules()
    {
        return array(
            array('users_id, post_id, post_type', 'required'),
            array('users_id, post_id', 'numerical', 'integerOnly'=>true),
            array('post_type', 'length', 'max'=>45),
            // The following rule is used by search().
            array('id, users_id, post_type, post_id', 'safe', 'on'=>'search'),
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
            'users_id' => 'Users',
            'post_type' => 'Post Type',
            'post_id' => 'Post',
        );
    }



    public function search()
    {
        $criteria=new CDbCriteria;
		$criteria->compare('id',$this->id);
		$criteria->compare('users_id',$this->users_id);
		$criteria->compare('post_type',$this->post_type,true);
		$criteria->compare('post_id',$this->post_id);
        return new CActiveDataProvider($this, array(
            'criteria'=>$criteria,
        ));
    }

    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }


    const LOOKS = "looks";
    const SHOPS = "shops";
    const EVENTS = "events";
    const STOCKS = "stocks";
    const BONUS = "bonus";

    public static function returnFavoriteRusName($n = false)
    {
        $array = [
            self::LOOKS=>'Луки',
            self::SHOPS=>'Магазины',
            self::EVENTS=>'События',
            self::STOCKS=>'Акции',
            self::BONUS=>'Бонусы',
        ];

        if(array_key_exists($n,$array))
            return $array[$n];
        else
            return $array;
    }


    public static function getClassByPostTypeAndPostId($post_type, $post_id)
    {
        $array = array();
        $post_type = strtolower($post_type);

        $criteria = new CDbCriteria(array(
                                            'condition'=>'id = :post_id',
                                            'params'=>array(
                                                            ':post_id'=>$post_id,
                                                            ),
                                        ));

        $today = date('Y-m-d H:i');


        switch($post_type)
        {
            case self::SHOPS:
                $criteria->addCondition("status = :status");
                $criteria->params[':status'] = Shops::STATUS_PUBLISH;
                $array[self::SHOPS] = Shops::model()->find($criteria);
                $obj = Shops::model()->find($criteria);
            break;

            case self::LOOKS:
                $criteria->addCondition("status = :status");
                $criteria->params[':status'] = Looks::STATUS_PUBLISH;
                $array[self::LOOKS] = Looks::model()->find($criteria);
                $obj = Looks::model()->find($criteria);
            break;

            case self::EVENTS:
                $criteria->addCondition("status = :status and id_type = :id_type and ( dttm_date_start <= :today and (:today <= dttm_date_finish or :today <= dttm_date_hide) )");
                $criteria->params[':status'] = Eventsstock::STATUS_PUBLISH;
                $criteria->params[':today'] = $today;
                $criteria->params[':id_type'] = Eventsstock::ES_EVENTS;
                
                $array[self::EVENTS] = Eventsstock::model()->find($criteria);
                $obj = Eventsstock::model()->find($criteria);
            break;

            case self::STOCKS:
                $criteria->addCondition("status = :status and id_type = :id_type and ( dttm_date_start <= :today and (:today <= dttm_date_finish or :today <= dttm_date_hide) )");
                $criteria->params[':status'] = Eventsstock::STATUS_PUBLISH;
                $criteria->params[':today'] = $today;
                $criteria->params[':id_type'] = Eventsstock::ES_STOCKS;
                $array[self::STOCKS] = Eventsstock::model()->find($criteria);
                $obj = Eventsstock::model()->find($criteria);
            break;

            case self::BONUS:
                $criteria->addCondition("status = :status and id_type = :id_type and ( dttm_date_start <= :today and (:today <= dttm_date_finish or :today <= dttm_date_hide) )");
                $criteria->params[':status'] = Eventsstock::STATUS_PUBLISH;
                $criteria->params[':today'] = $today;
                $criteria->params[':id_type'] = Eventsstock::ES_BONUS;
                $array[self::BONUS] = Eventsstock::model()->find($criteria);
                $obj = Eventsstock::model()->find($criteria);
            break;
        }

        // $array = array(
        //                 $post_id => $obj,

        //                 );

        return $array[$post_type];
    }

    public static function getFavoritesRowsByType($type, $user_id)
    {
            $favorites = UsersFavorites::model()->findAll( array(
                                                            'condition'=>'post_type = :post_type and users_id = :id_user',
                                                            'params'=>array(
                                                                        ':post_type'=>$type,
                                                                        ':id_user'=>$user_id,
                                                                        ),
                                                            ) );
            $favorites_array = CHtml::listData($favorites, 'id', 'post_id');
            $favorites_string  = implode(', ', $favorites_array);

            return $favorites_string;
    }


}
