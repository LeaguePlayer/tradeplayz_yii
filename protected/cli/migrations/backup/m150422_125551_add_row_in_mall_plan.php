<?php
/**
 * Миграция m150422_125551_add_row_in_mall_plan
 *
 * @property string $prefix
 */
 
class m150422_125551_add_row_in_mall_plan extends CDbMigration
{
    // таблицы к удалению, можно использовать '{{table}}'
	 public function safeUp()
    {
        $this->addColumn('mall_plan', 'floor_name', 'string');
    }
 
    public function safeDown()
    {
        $this->dropColumn('mall_plan', 'floor_name');
    }
 
    /**
     * Добавляет префикс таблицы при необходимости
     * @param $name - имя таблицы, заключенное в скобки, например {{имя}}
     * @return string
     */
    protected function tableName($name)
    {
        if($this->getDbConnection()->tablePrefix!==null && strpos($name,'{{')!==false)
            $realName=preg_replace('/{{(.*?)}}/',$this->getDbConnection()->tablePrefix.'$1',$name);
        else
            $realName=$name;
        return $realName;
    }
 
    /**
     * Получение установленного префикса таблиц базы данных
     * @return mixed
     */
    protected function getPrefix(){
        return $this->getDbConnection()->tablePrefix;
    }
}