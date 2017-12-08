<?php
/**
 * Миграция m150425_095805_add_rows_in_comments
 *
 * @property string $prefix
 */
 
class m150425_095805_add_rows_in_comments extends CDbMigration
{
    public function safeUp()
    {
        $this->addColumn('comments', 'source_text', 'text');
    }
 
    public function safeDown()
    {
        $this->dropColumn('comments', 'source_text');
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