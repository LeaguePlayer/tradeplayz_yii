<?php

/**
* This is the model class for table "recovery_passwords".
*
* The followings are the available columns in table 'recovery_passwords':
    * @property string $time_request
    * @property string $mail
    * @property string $token
    * @property integer $status
*/
class RecoveryPasswords extends EActiveRecord
{
    public function tableName()
    {
        return 'recovery_passwords';
    }


     // Статусы в базе данных
    const STATUS_CREATED = 0;
    const STATUS_ACTIVATED = 1;
    const STATUS_USED = 2;



    public static function getStatusAliases($status = -1)
    {
        $aliases = array(
            self::STATUS_CREATED => Yii::t('main','status_recovery_created'),
            self::STATUS_ACTIVATED => Yii::t('main','status_recovery_activated'),
            self::STATUS_USED => Yii::t('main','status_recovery_used'),
        );

        if ($status > -1)
            return $aliases[$status];

        return $aliases;
    }


    public function rules()
    {
        return array(
            array('mail', 'required'),
            array('status', 'numerical', 'integerOnly'=>true),
            array('time_request, token', 'safe'),
            // The following rule is used by search().
            array('id, time_request, mail, token, status', 'safe', 'on'=>'search'),
        );
    }


    public function relations()
    {
        return array(
            // 'user'  => array(self::HAS_ONE, 'Users', '', 'on'=>"user.login = t.mail"),
        );
    }


    public function attributeLabels()
    {
        return array(
            'time_request' => 'Time Request',
            'mail' => 'Mail',
            'token' => 'Token',
            'status' => 'Status',
        );
    }



    public function search()
    {
        $criteria=new CDbCriteria;
		$criteria->compare('time_request',$this->time_request,true);
		$criteria->compare('mail',$this->mail,true);
		$criteria->compare('token',$this->token,true);
		$criteria->compare('status',$this->status);
        return new CActiveDataProvider($this, array(
            'criteria'=>$criteria,
        ));
    }

    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }

    public function getActiveLink()
    {
        $domain = 'http://dev.tradeplayz.com';
        // $domain = 'http://tpz.server.loc';
        $url = "{$domain}/site/RecoveryPassword/?active_key={$this->token}";

        return $url;
    }


}
