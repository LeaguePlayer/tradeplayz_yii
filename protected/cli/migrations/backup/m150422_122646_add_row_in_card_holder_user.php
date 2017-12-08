<?php
/**
 * Миграция m150422_122646_add_row_in_card_holder_user
 *
 * @property string $prefix
 */
 
class m150422_122646_add_row_in_card_holder_user extends CDbMigration
{
    // таблицы к удалению, можно использовать '{{table}}'
	
 
    public function safeUp()
    {
        $this->addColumn('{{users_holders_card}}', 'card_number_for_user', 'string');
    }
 
    public function safeDown()
    {
        $this->dropColumn('{{users_holders_card}}', 'card_number_for_user');
    }
 //
   
 

 
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