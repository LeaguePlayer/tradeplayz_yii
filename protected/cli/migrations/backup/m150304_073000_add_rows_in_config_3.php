<?php
/**
 * Миграция m150304_073000_add_rows_in_config_3
 *
 * @property string $prefix
 */
 
class m150304_073000_add_rows_in_config_3 extends CDbMigration
{
    // таблицы к удалению, можно использовать '{{table}}'
	public function safeUp()
    {
        $this->insert("{{config}}", array("param"=>"app.sms", "value"=>"Скачал прикольное приложение Malloko! Рекомендую :)", "default"=>"", "label"=>"Текст SMS сообщения для рекомендации", "type"=>"string", "variants"=>"") );
       
    
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