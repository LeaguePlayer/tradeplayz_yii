<?php
/**
 * Миграция m141206_150644_create_rows_page
 *
 * @property string $prefix
 */
 
class m141206_150644_create_rows_page extends CDbMigration
{
    // таблицы к удалению, можно использовать '{{table}}'
	
 
    public function safeUp()
    {
        $this->insert("{{apppages}}", array('title'=>"Положение", 'meta_alias'=>'polojenie', 'create_time'=>date('Y-m-d H:i'), 'update_time'=>date('Y-m-d H:i')) );
        
        $this->insert("{{apppages}}", array('title'=>"Получить карту", 'meta_alias'=>'get_card', 'create_time'=>date('Y-m-d H:i'), 'update_time'=>date('Y-m-d H:i')) );
        
     }
 
    public function safeDown()
    {
       
    }
 
    /**
     * Удаляет таблицы, указанные в $this->dropped из базы.
     * Наименование таблиц могут сожержать двойные фигурные скобки для указания
     * необходимости добавления префикса, например, если указано имя {{table}}
     * в действительности будет удалена таблица 'prefix_table'.
     * Префикс таблиц задается в файле конфигурации (для консоли).
     */
   
 
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