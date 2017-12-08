<?php
/**
 * Миграция m140224_105014_config
 *
 * @property string $prefix
 */
 
class m140224_105014_config extends CDbMigration
{
    // таблицы к удалению, можно использовать '{{table}}'
	private $dropped = array('{{config_go}}');
 
    public function safeUp()
    {
        $this->_checkTables();
 
        $this->createTable('go_config', array(
            'id' => 'pk', // auto increment
			'param' => "string not null unique COMMENT 'Уникальный идентификатор параметра'",
			'value' => "text not null COMMENT 'Значение'",
			'default' => "text not null COMMENT 'Значение по-умолчанию'",
			'label' => "string not null COMMENT 'Заголовок'",
			'type' => "varchar(128) not null default 'string' COMMENT 'Тип поля'",
			'variants' => "text not null COMMENT 'Перечисляемые значения'",
        ),
        'ENGINE=MyISAM DEFAULT CHARACTER SET = utf8 COLLATE = utf8_general_ci');

        // $this->insert('{{config}}', array(
        //     'param' => 'app.name',
        //     'value' => 'Каркас приложения',
        //     'label' => 'Название сайта',
        //     'type' => 'string'
        // ));

        // $this->insert('{{config}}', array(
        //     'param' => 'app.description',
        //     'value' => 'Это стартовый каркас',
        //     'label' => 'Описание приложения',
        //     'type' => 'text'
        // ));

        // $this->insert('{{config}}', array(
        //     'param' => 'temp.select',
        //     'value' => '1',
        //     'label' => 'Выпадающий список',
        //     'type' => 'select',
        //     'variants' => '1:Это тестовый пример | 2:Удали эту настройку из БД'
        // ));

        // $this->insert('{{config}}', array(
        //     'param' => 'temp.radio',
        //     'value' => '1',
        //     'label' => 'Радиокнопки',
        //     'type' => 'radio',
        //     'variants' => '1:Это тестовый пример | 2:Удали эту настройку из БД'
        // ));

        // $this->insert('{{config}}', array(
        //     'param' => 'temp.checkbox',
        //     'value' => '1',
        //     'label' => 'Пример чекбокса (правильно, его тоже удали)',
        //     'type' => 'checkbox',
        // ));
    }
 
    public function safeDown()
    {
        $this->_checkTables();
    }
 
    /**
     * Удаляет таблицы, указанные в $this->dropped из базы.
     * Наименование таблиц могут сожержать двойные фигурные скобки для указания
     * необходимости добавления префикса, например, если указано имя {{table}}
     * в действительности будет удалена таблица 'prefix_table'.
     * Префикс таблиц задается в файле конфигурации (для консоли).
     */
    private function _checkTables ()
    {
        if (empty($this->dropped)) return;
 
        $table_names = $this->getDbConnection()->getSchema()->getTableNames();
        foreach ($this->dropped as $table) {
            if (in_array($this->tableName($table), $table_names)) {
                $this->dropTable($table);
            }
        }
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