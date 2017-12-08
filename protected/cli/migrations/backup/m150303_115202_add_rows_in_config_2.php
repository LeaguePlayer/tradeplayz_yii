<?php
/**
 * Миграция m150303_115202_add_rows_in_config_2
 *
 * @property string $prefix
 */
 
class m150303_115202_add_rows_in_config_2 extends CDbMigration
{
    // таблицы к удалению, можно использовать '{{table}}'
	public function safeUp()
    {
        $this->insert("{{config}}", array("param"=>"app.ok", "value"=>"http://ok.ru/malloko", "default"=>"", "label"=>"Ссылка на группу в Одноклассниках", "type"=>"string", "variants"=>"") );
       
    
    }
 
    public function safeDown()
    {
        
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