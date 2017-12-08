<?php
/**
 * Миграция m150302_124422_settings_add
 *
 * @property string $prefix
 */
 
class m150302_124422_settings_add extends CDbMigration
{
    // таблицы к удалению, можно использовать '{{table}}'
	
 
    public function safeUp()
    {
        $this->insert("{{config}}", array("param"=>"app.vk", "value"=>"http://vk.com/malloko", "default"=>"", "label"=>"Ссылка на группу в Vkontakte", "type"=>"string", "variants"=>"") );
        $this->insert("{{config}}", array("param"=>"app.fb", "value"=>"https://m.facebook.com/mallokotmn", "default"=>"", "label"=>"Ссылка на группу в Facebook", "type"=>"string", "variants"=>"") );
        $this->insert("{{config}}", array("param"=>"app.tw", "value"=>"https://twitter.com/MallokoDiscount", "default"=>"", "label"=>"Ссылка на группу в Twitter", "type"=>"string", "variants"=>"") );
        $this->insert("{{config}}", array("param"=>"app.insta", "value"=>"https://instagram.com/malloko/", "default"=>"", "label"=>"Ссылка на группу в Instgram", "type"=>"string", "variants"=>"") );
        $this->insert("{{config}}", array("param"=>"app.youtube", "value"=>"http://www.youtube.com/user/MallokoDiscount", "default"=>"", "label"=>"Ссылка на группу в YouTube", "type"=>"string", "variants"=>"") );
    
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