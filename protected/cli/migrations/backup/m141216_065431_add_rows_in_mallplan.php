<?php
/**
 * Миграция m141216_065431_add_rows_in_mallplan
 *
 * @property string $prefix
 */
 
class m141216_065431_add_rows_in_mallplan extends CDbMigration
{
    // таблицы к удалению, можно использовать '{{table}}'
	
    public function safeUp()
    {
         $this->addColumn('tbl_binds_shop_area', 'id_mall', 'integer');
    }
 
    public function safeDown()
    {
        $this->dropColumn('tbl_binds_shop_area', 'id_mall');
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