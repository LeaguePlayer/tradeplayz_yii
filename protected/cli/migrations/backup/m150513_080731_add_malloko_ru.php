<?php
/**
 * Миграция m150513_080731_add_malloko_ru
 *
 * @property string $prefix
 */
 
class m150513_080731_add_malloko_ru extends CDbMigration
{
    
 
    public function safeUp()
    {
        $this->insert("{{config}}", array("param"=>"app.web.malloko", "value"=>"http://malloko.ru/", "default"=>"", "label"=>"Ссылка на сайт программы Malloko", "type"=>"string", "variants"=>"") );
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