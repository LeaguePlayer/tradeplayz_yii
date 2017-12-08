<?php

/**
* This is the model class for table "{{malloko_cards}}".
*
* The followings are the available columns in table '{{malloko_cards}}':
    * @property integer $id
    * @property string $card_number
*/
class Mallokocards extends EActiveRecord
{
    public function tableName()
    {
        return '{{malloko_cards}}';
    }


    public function rules()
    {
        return array(
            array('card_number', 'length', 'max'=>255),
            // The following rule is used by search().
            array('id, card_number', 'safe', 'on'=>'search'),
        );
    }


    public function relations()
    {
        return array(
            'holder_relation' => array(self::HAS_ONE, 'Usersholderscard', 'id_card'),
        );
    }


    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'card_number' => 'Номер карты',
        );
    }



    public function search()
    {
        $criteria=new CDbCriteria;
        $criteria->addCondition("id_card is null");
		$criteria->compare('id',$this->id);
		$criteria->compare('card_number',$this->card_number,true);
        return new CActiveDataProvider($this, array(
            'criteria'=>$criteria,
        ));
    }

    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }

    public static function checkHolderCardByNumber( $card_number )
    {
        $model = self::model()->find( "card_number = :card_number", array(':card_number'=>$card_number) );


        return (empty($model->holder_relation)) ? $card_number : "{$card_number} <div class='have_another_card'>( Эта карта уже привязана )</div>";
    }

    public function getFormatedCardNumber()
    {
        $result = "";
        $str = $this->holder_relation->card_number_for_user;

        for ($x=0; $x<strlen($str); $x++) 
            $result .= (($x+1)%3==0) ? $str[$x].' ' : $str[$x];
         

        return $result;
    }

    public static function convertCardNumber( $card_number )
    {
        $remove_symbols = array(0,3,4);

        $result = "";
        for ($x=0; $x<strlen($card_number); $x++) 
            $result .= (in_array($x, $remove_symbols)) ? '' : $card_number[$x];
         
        return "M{$result}";

    }
}
