<?php

class v2_ImportXls
{
	public $file = false;
	public $mall = null;
	public $all_categories = array();


	//settings
	protected $begin_row = 1;
	const DEFAULT_CATEGORY_ID = 18; // по умолчанию будет присваиваться эта категория

	// cells
	const CELL_NAME_SHOP = 'A';
	const CELL_SHOP_GROUP = 'B';
	const CELL_EMAIL_SHOP = 'C';
	const CELL_PHONE_SHOP = 'D';
	const CELL_SITE_SHOP = 'E';
	const CELL_DISCOUNT_MALLOKO_SHOP = 'F';




	

	

// ^((8|\+7)[\- ]?)?(\(?\d{3}\)?[\- ]?)?[\d\- ]{7,10}$

	protected function validPhone($s)
	{
		$re = "/(.+\\w+)/"; 
		$str = $s; 
		 
		preg_match_all($re, $str, $matches);
		// SiteHelper::mpr($matches);
		return $matches[0][0];
	}

	protected function checkAllowPhone($validPhone)
	{
		$phone = str_replace(array("-"," ","−",")","(","‒", chr(160)), '', $validPhone);
				$phone = iconv( "WINDOWS-1251","UTF-8", $phone);
				$phone = str_replace(array("В"), '', $phone);
				return (is_numeric($phone)) ? true : false;
	}
	
	public function begin()
	{
// 		ini_set('error_reporting', E_ALL);
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);

		$dublicate_check = array();
		// set_time_limit(0);
		$all_shops_in_mall = array();
		$all_shops = array();
		
		$result = array();

		$all_places_in_mall = Place::model()->findAll("malls_id = :malls_id", array(':malls_id'=>$this->mall->id));
		$all_shops_models = Shops::model()->findAll();
		

		foreach($all_places_in_mall as $place)
			$all_shops_in_mall[$place->shop->title] = $place->shop->id;

		foreach($all_shops_models as $shop)
			$all_shops[$shop->title] = $shop;

// 		SiteHelper::mpr($all_shops_in_mall);
// // var_dump($all_shops_in_mall['Kari Kids']);
// 		die();

		// SiteHelper::mpr($this->all_categories);die();


		$sheet_array = Yii::app()->yexcel->readActiveSheet($this->file);


		

			foreach( $sheet_array as $index => $row ) {
				if($index <= $this->begin_row) continue;
				$name_shop = trim($row[self::CELL_NAME_SHOP]);

				// if($name_shop=='Kari Kids')
				// {
				// 	echo $name_shop;
				// 	var_dump((is_numeric($all_shops_in_mall[$name_shop])));
				// 	// die();
				// }

				if(empty($name_shop)) continue;
				if($dublicate_check[$name_shop]) continue;

				// по умолчанию
				$id_category = self::DEFAULT_CATEGORY_ID;
				$id_type = 0;

				// ищим товарную категорию
				if(is_numeric($this->all_categories[$row[self::CELL_SHOP_GROUP]]))
				{
					$exist_category = true;
					$id_category = $this->all_categories[$row[self::CELL_SHOP_GROUP]];
				}
				else 
					$exist_category = false;

				// ищим магазины
				$id_shop = null;
				$exist_shop = null;
				if(is_numeric($all_shops_in_mall[$name_shop]))
				{
					$exist_shop = 'exist';
					$shop_model = $all_shops[$name_shop];
					$id_shop = $shop_model->id;
					$id_category = $shop_model->categories_id;
					$id_type = $shop_model->id_type;
					$exist_category = true;
					unset($all_shops_in_mall[$name_shop]);
				}
				else 
				{
					$shop_model = $all_shops[$name_shop];
					if($shop_model)
					{
						$id_shop = $shop_model->id;
						$id_category = $shop_model->categories_id;
						$id_type = $shop_model->id_type;
						$exist_category = true;
						$exist_shop = 'havnt_but_in_db';
					}
					else
					{
						$exist_shop = 'havnt';
					}
					
				}



				
					
				

				$phoneValid = $this->validPhone($row[self::CELL_PHONE_SHOP]);
				

				$dublicate_check[$name_shop] = true;
				$result[] = array(
						'exist_shop'=>$exist_shop,
						'shop_name'=>$name_shop,
						'shop_group'=>$id_category,
						'shop_email'=>$row[self::CELL_EMAIL_SHOP],
						'shop_phone'=>$phoneValid,
						'shop_group_allow'=>$exist_category,
						'shop_phone_allow'=>$this->checkAllowPhone($phoneValid),
						'shop_site'=>$row[self::CELL_SITE_SHOP],
						'shop_discount'=>(int)$row[self::CELL_DISCOUNT_MALLOKO_SHOP],
						'id_shop'=>$id_shop,
						'id_type'=>$id_type,
					);


			}

			foreach($all_shops_in_mall as $shop_name => $id_shop)
			{
				$result[] = array(
						'id_shop'=>$id_shop,
						'shop_name'=>$shop_name,
						'exist_shop'=>'remove',
					);
					
				
			}
			// SiteHelper::mpr($result);
			
		// die('finished');

		return $result;
	}



}