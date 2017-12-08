<?php
/**
 * Миграция m150311_095848_add_values_in_config_3
 *
 * @property string $prefix
 */
 
class m150311_095848_add_values_in_config_3 extends CDbMigration
{
    // таблицы к удалению, можно использовать '{{table}}'
	public function safeUp()
    {
        $this->insert("{{config}}", array("param"=>"app.web.solnechniy", "value"=>"http://www.trksunny.ru/", "default"=>"", "label"=>"Ссылка на сайт ТРЦ Солнечный", "type"=>"string", "variants"=>"") );
        $this->insert("{{config}}", array("param"=>"app.web.malahit", "value"=>"http://mallahit.ru/", "default"=>"", "label"=>"Ссылка на сайт ТРЦ Малахит", "type"=>"string", "variants"=>"") );
        $this->insert("{{config}}", array("param"=>"app.web.favorit", "value"=>"http://trkfavorit.ru/", "default"=>"", "label"=>"Ссылка на сайт ТРЦ Фаворит", "type"=>"string", "variants"=>"") );
       
    
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