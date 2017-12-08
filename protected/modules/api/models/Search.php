<?php

class Search extends CActiveRecord
{
	public static function searchByStr($str,$table)
		{	
			$str='+'.trim($str);
			$str=str_replace(" ", " +", $str);
			$result=Yii::app()->db->createCommand()
				->select('id, name, MATCH (name) AGAINST (:str IN BOOLEAN MODE) as REL')
				->from("tbl_$table")
				->where('MATCH (name) AGAINST (:str IN BOOLEAN MODE) > 0',array(':str'=>$str))
				->order('rel desc')
				->queryAll();
			$criteria=new CDbCriteria;
			$iDs=array();
			foreach ($result as $key => $data) {
					$iDs[]=$data['id'];
			}
			if ($result)
				$criteria->addInCondition('t.id',$iDs);
			else 
				$criteria->addCondition('t.id=-1');
			return new CActiveDataProvider($table,
				array(
					'criteria'=>$criteria,
					'pagination'=>array('pageSize'=>10)
				)
			);
		}
}