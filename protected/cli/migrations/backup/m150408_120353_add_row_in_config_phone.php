<?php
/**
 * Миграция m150408_120353_add_row_in_config_phone
 *
 * @property string $prefix
 */
 
class m150408_120353_add_row_in_config_phone extends CDbMigration
{
    // таблицы к удалению, можно использовать '{{table}}'
	
 
    public function safeUp()
    {
        $this->insert("{{config}}", array("param"=>"app.web.phone", "value"=>"", "default"=>"", "label"=>"Номер телефона администрации Malloko", "type"=>"string", "variants"=>"") );
        $this->insert("{{config}}", array("param"=>"app.error.passbook.doublerequest", "value"=>"Ваш запрос о привязке карты Malloko с passbook еще обрабатывается, мы уведомим Вас о заверешении.", "default"=>"", "label"=>"Текст ошибки, когда пользователь пытается 2ой раз отправить данные о карте malloko", "type"=>"string", "variants"=>"") );
        $this->insert("{{config}}", array("param"=>"app.push.passbook.success", "value"=>"", "default"=>"", "label"=>"Текст push-уведомления для пользователя при успешной привязки карты Malloko с passbook", "type"=>"string", "variants"=>"") );
        $this->insert("{{config}}", array("param"=>"app.push.passbook.fail", "value"=>"", "default"=>"", "label"=>"Текст push-уведомления для пользователя при неудачной привязки карты Malloko с passbook", "type"=>"string", "variants"=>"") );
        
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