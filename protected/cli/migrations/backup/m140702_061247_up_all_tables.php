<?php
/**
 * Миграция m140702_061247_up_all_tables
 *
 * @property string $prefix
 */
 
class m140702_061247_up_all_tables extends CDbMigration
{
    // таблицы к удалению, можно использовать '{{table}}'

 
    public function safeUp()
    {
       
        
        $sql = "SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;

SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;

SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';



-- -----------------------------------------------------

-- Table `malls`

-- -----------------------------------------------------

CREATE TABLE IF NOT EXISTS `malls` (

`id` INT NOT NULL AUTO_INCREMENT,

`id_type` INT NULL,

`title` VARCHAR(255) NULL,

`img_logo` VARCHAR(255) NULL,

PRIMARY KEY (`id`))

ENGINE = MyISAM;

-- -----------------------------------------------------

-- Table `categories`

-- -----------------------------------------------------

CREATE TABLE IF NOT EXISTS `categories` (

`id` INT NOT NULL AUTO_INCREMENT,

`title` VARCHAR(255) NULL,

`img_preview` VARCHAR(255) NULL,

`img_discount_preview` VARCHAR(255) NULL,

`img_category` VARCHAR(255) NULL,

`color_rgb` VARCHAR(255) NULL,

`status` TINYINT NULL,

PRIMARY KEY (`id`))

ENGINE = MyISAM;

-- -----------------------------------------------------

-- Table `shops`

-- -----------------------------------------------------

CREATE TABLE IF NOT EXISTS `shops` (

`id` INT NOT NULL AUTO_INCREMENT,

`title` VARCHAR(255) NULL,

`path_package` VARCHAR(255) NULL,

`categories_id` INT NOT NULL,

`status` TINYINT NULL,

`homepage` VARCHAR(255) NULL,

PRIMARY KEY (`id`),

INDEX `fk_shops_categories_idx` (`categories_id` ASC),

CONSTRAINT `fk_shops_categories`

FOREIGN KEY (`categories_id`)

REFERENCES `categories` (`id`)

ON DELETE NO ACTION

ON UPDATE NO ACTION)

ENGINE = MyISAM;

-- -----------------------------------------------------

-- Table `place`

-- -----------------------------------------------------

CREATE TABLE IF NOT EXISTS `place` (

`id` INT NOT NULL AUTO_INCREMENT,

`street` VARCHAR(255) NULL,

`shops_id` INT NOT NULL,

`malls_id` INT NOT NULL,

`status` TINYINT NULL,

PRIMARY KEY (`id`),

INDEX `fk_place_shops1_idx` (`shops_id` ASC),

INDEX `fk_place_malls1_idx` (`malls_id` ASC),

CONSTRAINT `fk_place_shops1`

FOREIGN KEY (`shops_id`)

REFERENCES `shops` (`id`)

ON DELETE NO ACTION

ON UPDATE NO ACTION,

CONSTRAINT `fk_place_malls1`

FOREIGN KEY (`malls_id`)

REFERENCES `malls` (`id`)

ON DELETE NO ACTION

ON UPDATE NO ACTION)

ENGINE = MyISAM;

-- -----------------------------------------------------

-- Table `place_phone`

-- -----------------------------------------------------

CREATE TABLE IF NOT EXISTS `place_phone` (

`id` INT NOT NULL AUTO_INCREMENT,

`phone` VARCHAR(255) NULL,

`place_id` INT NOT NULL,

PRIMARY KEY (`id`),

INDEX `fk_place_phone_place1_idx` (`place_id` ASC),

CONSTRAINT `fk_place_phone_place1`

FOREIGN KEY (`place_id`)

REFERENCES `place` (`id`)

ON DELETE NO ACTION

ON UPDATE NO ACTION)

ENGINE = MyISAM;

-- -----------------------------------------------------

-- Table `party_malloko`

-- -----------------------------------------------------

CREATE TABLE IF NOT EXISTS `party_malloko` (

`id` INT NOT NULL AUTO_INCREMENT,

`shops_id` INT NOT NULL,

`discount` DECIMAL(10,2) NULL,

PRIMARY KEY (`id`),

INDEX `fk_party_malloko_shops1_idx` (`shops_id` ASC),

CONSTRAINT `fk_party_malloko_shops1`

FOREIGN KEY (`shops_id`)

REFERENCES `shops` (`id`)

ON DELETE NO ACTION

ON UPDATE NO ACTION)

ENGINE = MyISAM;

-- -----------------------------------------------------

-- Table `events_stock`

-- -----------------------------------------------------

CREATE TABLE IF NOT EXISTS `events_stock` (

`id` INT NOT NULL AUTO_INCREMENT,

`id_type` INT NULL,

`title` VARCHAR(255) NULL,

`description` TEXT NULL,

`dttm_date_start` DATETIME NULL,

`dttm_date_finish` DATETIME NULL,

`dttm_date_hide` DATETIME NULL,

`status` TINYINT NULL,

`shops_id` INT NOT NULL,

PRIMARY KEY (`id`),

INDEX `fk_events_stock_shops1_idx` (`shops_id` ASC),

CONSTRAINT `fk_events_stock_shops1`

FOREIGN KEY (`shops_id`)

REFERENCES `shops` (`id`)

ON DELETE NO ACTION

ON UPDATE NO ACTION)

ENGINE = MyISAM;

-- -----------------------------------------------------

-- Table `mall_plan`

-- -----------------------------------------------------

CREATE TABLE IF NOT EXISTS `mall_plan` (

`id` INT NOT NULL AUTO_INCREMENT,

`malls_id` INT NOT NULL,

`floor_room` INT NULL,

`img_map` VARCHAR(255) NULL,

`json_areas` TEXT NULL,

`status` TINYINT NULL,

PRIMARY KEY (`id`),

INDEX `fk_mall_plan_malls1_idx` (`malls_id` ASC),

CONSTRAINT `fk_mall_plan_malls1`

FOREIGN KEY (`malls_id`)

REFERENCES `malls` (`id`)

ON DELETE NO ACTION

ON UPDATE NO ACTION)

ENGINE = MyISAM;

-- -----------------------------------------------------

-- Table `looks`

-- -----------------------------------------------------

CREATE TABLE IF NOT EXISTS `looks` (

`id` INT NOT NULL AUTO_INCREMENT,

`user_id` INT NOT NULL,

`dttm_date_create` DATETIME NULL,

`img_look` VARCHAR(255) NULL,

`status` TINYINT NULL,

PRIMARY KEY (`id`))

ENGINE = MyISAM;

-- -----------------------------------------------------

-- Table `looks_place`

-- -----------------------------------------------------

CREATE TABLE IF NOT EXISTS `looks_place` (

`id` INT NOT NULL AUTO_INCREMENT,

`shops_id` INT NOT NULL,

`looks_id` INT NOT NULL,

PRIMARY KEY (`id`),

INDEX `fk_looks_place_shops1_idx` (`shops_id` ASC),

INDEX `fk_looks_place_looks1_idx` (`looks_id` ASC),

CONSTRAINT `fk_looks_place_shops1`

FOREIGN KEY (`shops_id`)

REFERENCES `shops` (`id`)

ON DELETE NO ACTION

ON UPDATE NO ACTION,

CONSTRAINT `fk_looks_place_looks1`

FOREIGN KEY (`looks_id`)

REFERENCES `looks` (`id`)

ON DELETE NO ACTION

ON UPDATE NO ACTION)

ENGINE = MyISAM;

-- -----------------------------------------------------

-- Table `users`

-- -----------------------------------------------------

CREATE TABLE IF NOT EXISTS `users` (

`id` INT NOT NULL AUTO_INCREMENT,

`firstname` VARCHAR(100) NULL,

`lastname` VARCHAR(100) NULL,

`img_avatar` VARCHAR(255) NULL,

`status` TINYINT NULL,

PRIMARY KEY (`id`))

ENGINE = MyISAM;

-- -----------------------------------------------------

-- Table `comments`

-- -----------------------------------------------------

CREATE TABLE IF NOT EXISTS `comments` (

`id` INT NOT NULL AUTO_INCREMENT,

`looks_id` INT NOT NULL,

`comment_text` TEXT NULL,

`date_create` DATETIME NULL,

`users_id` INT NOT NULL,

`status` TINYINT NULL,

PRIMARY KEY (`id`),

INDEX `fk_comments_looks1_idx` (`looks_id` ASC),

INDEX `fk_comments_users1_idx` (`users_id` ASC),

CONSTRAINT `fk_comments_looks1`

FOREIGN KEY (`looks_id`)

REFERENCES `looks` (`id`)

ON DELETE NO ACTION

ON UPDATE NO ACTION,

CONSTRAINT `fk_comments_users1`

FOREIGN KEY (`users_id`)

REFERENCES `users` (`id`)

ON DELETE NO ACTION

ON UPDATE NO ACTION)

ENGINE = MyISAM;

-- -----------------------------------------------------

-- Table `likes`

-- -----------------------------------------------------

CREATE TABLE IF NOT EXISTS `likes` (

`id` INT NOT NULL AUTO_INCREMENT,

`looks_id` INT NOT NULL,

`date_create` DATETIME NULL,

`users_id` INT NOT NULL,

PRIMARY KEY (`id`),

INDEX `fk_likes_looks1_idx` (`looks_id` ASC),

INDEX `fk_likes_users1_idx` (`users_id` ASC),

CONSTRAINT `fk_likes_looks1`

FOREIGN KEY (`looks_id`)

REFERENCES `looks` (`id`)

ON DELETE NO ACTION

ON UPDATE NO ACTION,

CONSTRAINT `fk_likes_users1`

FOREIGN KEY (`users_id`)

REFERENCES `users` (`id`)

ON DELETE NO ACTION

ON UPDATE NO ACTION)

ENGINE = MyISAM;

-- -----------------------------------------------------

-- Table `users_follows`

-- -----------------------------------------------------

CREATE TABLE IF NOT EXISTS `users_follows` (

`id` INT NOT NULL AUTO_INCREMENT,

`users_id` INT NOT NULL,

`users_follow_id` INT NOT NULL,

PRIMARY KEY (`id`),

INDEX `fk_users_follows_users1_idx` (`users_id` ASC),

INDEX `fk_users_follows_users2_idx` (`users_follow_id` ASC),

CONSTRAINT `fk_users_follows_users1`

FOREIGN KEY (`users_id`)

REFERENCES `users` (`id`)

ON DELETE NO ACTION

ON UPDATE NO ACTION,

CONSTRAINT `fk_users_follows_users2`

FOREIGN KEY (`users_follow_id`)

REFERENCES `users` (`id`)

ON DELETE NO ACTION

ON UPDATE NO ACTION)

ENGINE = MyISAM;

-- -----------------------------------------------------

-- Table `users_favorites`

-- -----------------------------------------------------

CREATE TABLE IF NOT EXISTS `users_favorites` (

`id` INT NOT NULL AUTO_INCREMENT,

`users_id` INT NOT NULL,

`post_type` VARCHAR(45) NULL,

`post_id` INT NULL,

PRIMARY KEY (`id`),

INDEX `fk_users_favorites_users1_idx` (`users_id` ASC),

CONSTRAINT `fk_users_favorites_users1`

FOREIGN KEY (`users_id`)

REFERENCES `users` (`id`)

ON DELETE NO ACTION

ON UPDATE NO ACTION)

ENGINE = MyISAM;

-- -----------------------------------------------------

-- Table `hashtag_day`

-- -----------------------------------------------------

CREATE TABLE IF NOT EXISTS `hashtag_day` (

`id` INT NOT NULL AUTO_INCREMENT,

`dt_date_begin` DATETIME NULL,

`dt_date_finish` DATETIME NULL,

`title` VARCHAR(255) NULL,

`malls_id` INT NOT NULL,

`status` TINYINT NULL,

PRIMARY KEY (`id`),

INDEX `fk_hashtag_day_malls1_idx` (`malls_id` ASC),

CONSTRAINT `fk_hashtag_day_malls1`

FOREIGN KEY (`malls_id`)

REFERENCES `malls` (`id`)

ON DELETE NO ACTION

ON UPDATE NO ACTION)

ENGINE = MyISAM;

-- -----------------------------------------------------

-- Table `users_provider`

-- -----------------------------------------------------

CREATE TABLE IF NOT EXISTS `users_provider` (

`id` INT NOT NULL AUTO_INCREMENT,

`users_id` INT NOT NULL,

`loginProvider` VARCHAR(50) NULL,

`loginProviderIdentifier` VARCHAR(100) NULL,

PRIMARY KEY (`id`),

INDEX `fk_users_provider_users1_idx` (`users_id` ASC),

CONSTRAINT `fk_users_provider_users1`

FOREIGN KEY (`users_id`)

REFERENCES `users` (`id`)

ON DELETE NO ACTION

ON UPDATE NO ACTION)

ENGINE = MyISAM;

-- -----------------------------------------------------

-- Table `orders`

-- -----------------------------------------------------

CREATE TABLE IF NOT EXISTS `orders` (

`id` INT NOT NULL AUTO_INCREMENT,

`firstname` VARCHAR(200) NULL,

`phone` VARCHAR(45) NULL,

PRIMARY KEY (`id`))

ENGINE = MyISAM;

-- -----------------------------------------------------

-- Table `user_devices`

-- -----------------------------------------------------

CREATE TABLE IF NOT EXISTS `user_devices` (

`id` INT NOT NULL AUTO_INCREMENT,

`model_phone` VARCHAR(45) NULL,

`deviceToken` VARCHAR(255) NULL,

`users_id` INT NOT NULL,

`id_os` VARCHAR(255) NULL,

PRIMARY KEY (`id`),

INDEX `fk_user_devices_users1_idx` (`users_id` ASC),

CONSTRAINT `fk_user_devices_users1`

FOREIGN KEY (`users_id`)

REFERENCES `users` (`id`)

ON DELETE NO ACTION

ON UPDATE NO ACTION)

ENGINE = MyISAM;

-- -----------------------------------------------------

-- Table `alternative_names_shop`

-- -----------------------------------------------------

CREATE TABLE IF NOT EXISTS `alternative_names_shop` (

`id` INT NOT NULL AUTO_INCREMENT,

`shops_id` INT NOT NULL,

`title` VARCHAR(255) NULL,

PRIMARY KEY (`id`),

INDEX `fk_alternative_names_shop_shops1_idx` (`shops_id` ASC),

CONSTRAINT `fk_alternative_names_shop_shops1`

FOREIGN KEY (`shops_id`)

REFERENCES `shops` (`id`)

ON DELETE NO ACTION

ON UPDATE NO ACTION)

ENGINE = MyISAM;

SET SQL_MODE=@OLD_SQL_MODE;

SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;

SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;


ALTER TABLE `categories`
  ADD CONSTRAINT `advertisers_ibfk_1` FOREIGN KEY (`advertiser_id`) 
      REFERENCES `jobs` (`advertiser_id`);
  
 ";
        
         $command = Yii::app()->db;
         $command->createCommand($sql)->execute();
    }
 

 
    public function safeDown()
    {
        $sql = "DROP TABLE alternative_names_shop
, categories
, comments
, events_stock
, hashtag_day
, likes
, looks
, looks_place
, malls
, mall_plan
, orders
, party_malloko
, place
, place_phone
, shops
, users
, users_favorites
, users_follows
, users_provider
, user_devices";
       $command = Yii::app()->db;
         $command->createCommand($sql)->execute();
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