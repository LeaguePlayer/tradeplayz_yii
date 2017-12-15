<?php

/**
* This is the model class for table "users".
*
* The followings are the available columns in table 'users':
    * @property integer $id
    * @property string $firstname
    * @property string $lastname
    * @property string $img_avatar
    * @property integer $status
    * @property string $balance
    * @property string $login
    * @property string $password
    * @property integer $rating
    * @property string $address
    * @property string $zipcode
    * @property string $email
    * @property integer $currency
*/
class Users extends EActiveRecord
{
    public function tableName()
    {
        return 'users';
    }


    // Статусы в базе данных
    const STATUS_BANNED = 0;
    const STATUS_PUBLISH = 1;
    const STATUS_REMOVED = 3;
    const STATUS_DEFAULT = self::STATUS_PUBLISH;


    public static function getStatusAliases($status = -1)
    {
        $aliases = array(
            self::STATUS_BANNED => 'Забанен',
            self::STATUS_PUBLISH => 'Опубликовано',
            self::STATUS_REMOVED => 'Удален',
        );

        if ($status > -1)
            return $aliases[$status];

        return $aliases;
    }


    public function rules()
    {
        return array(
            // array('id', 'required'),
            array('id, status, rating, currency', 'numerical', 'integerOnly'=>true),
            array('firstname, lastname, img_avatar, login, password, address, email, phone', 'length', 'max'=>255),
            array('balance', 'length', 'max'=>8),
            array('zipcode', 'length', 'max'=>25),
            // The following rule is used by search().
            array('id, firstname, lastname, img_avatar, status, balance, login, password, rating, address, zipcode, email, currency', 'safe', 'on'=>'search'),
        );
    }


    public function relations()
    {
        return array(
            // 'profiles' => array(self::HAS_ONE, 'Profiles', 'user_id'),
            'provider' => array(self::HAS_ONE, 'UsersProvider', 'id_user'),
            'device' => array(self::HAS_ONE, 'UserDevices', 'id_user'),
            'active_participant' => array(self::HAS_ONE, 'Participants', 'id_client', "condition"=>"status = :status","params"=>array(
                    ':status'=>Participants::STATUS_STILL_PLAY,
                )),
        );
    }

    // public function beforeSave()
    // {
    //     parent::beforeSave();

    //     foreach($this->attributes as $attr => $val)
    //         {
    //             if(is_null($val))
    //                 $this->$attr = "";
    //         }

    //     return true;
    // }


    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'firstname' => 'Имя',
            'lastname' => 'Фамилия',
            'img_avatar' => 'Img Avatar',
            'status' => 'Status',
            'balance' => 'Balance',
            'login' => 'Login',
            'password' => 'Password',
            'rating' => 'Rating',
            'address' => 'Address',
            'phone' => 'Phone',
            'zipcode' => 'Zipcode',
            'email' => 'Email',
            'currency' => 'Currency',
        );
    }


    public function behaviors()
    {
        return CMap::mergeArray(parent::behaviors(), array(
			'imgBehaviorAvatar' => array(
				'class' => 'application.behaviors.UploadableImageBehavior',
				'attributeName' => 'img_avatar',
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
		$criteria->compare('firstname',$this->firstname,true);
		$criteria->compare('lastname',$this->lastname,true);
		$criteria->compare('img_avatar',$this->img_avatar,true);
		$criteria->compare('status',$this->status);
		$criteria->compare('balance',$this->balance,true);
        $criteria->compare('login',$this->login,true);
		$criteria->compare('phone',$this->phone,true);
		$criteria->compare('password',$this->password,true);
		$criteria->compare('rating',$this->rating);
		$criteria->compare('address',$this->address,true);
		$criteria->compare('zipcode',$this->zipcode,true);
		$criteria->compare('email',$this->email,true);
		$criteria->compare('currency',$this->currency);
        return new CActiveDataProvider($this, array(
            'criteria'=>$criteria,
        ));
    }

    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }


}
