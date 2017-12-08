<?php
/**
 * Миграция m150423_102943_add_rows_in_stocks
 *
 * @property string $prefix
 */
 
class m150423_102943_add_rows_in_stocks extends CDbMigration
{
    public function safeUp()
    {
        $this->addColumn('events_stock', 'malls_id', 'integer');
    }
 
    public function safeDown()
    {
        $this->dropColumn('events_stock', 'malls_id');
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