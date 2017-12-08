<?php

/**
 * This is the model class for table "deviceTokens".
 *
 * The followings are the available columns in table 'deviceTokens':
 * @property string $id
 * @property integer $id_application
 * @property string $deviceToken
 */
class DeviceTokens extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return DeviceTokens the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{devicetoken}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('deviceToken', 'required'),
			array('deviceToken', 'length', 'max'=>255),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, deviceToken', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'deviceToken' => 'Device Token',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id,true);
		$criteria->compare('deviceToken',$this->deviceToken,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}