<?php

class ShopsController extends AdminController
{
    

    public function init()
    {
        parent::init();
        
            Yii::app()->clientScript->registerScriptFile($this->getAssetsUrl().'/js/shops.js', CClientScript::POS_END);
            Yii::app()->clientScript->registerCssFile($this->getAssetsUrl().'/css/shops.css');
        
        return true;
    }

    public function actionPublicAll($type=false)
    {
        $criteria = new CDbCriteria;
        if($type!='public')
        {
            $array_without_files = array();
            $all_shops = Shops::model()->findAll();
            foreach($all_shops as $shop)
           {
                $file =  dirname(__FILE__)."/../../../../".$shop->getSmallPackageUrl();
                if(!is_file($file))
                    $array_without_files[] = $shop->id;
           }

            $criteria->addCondition(" path_package = '' ");
            if(!empty($array_without_files))
                $criteria->addInCondition("id", $array_without_files, "OR");
        }

        $all_models_without_packets = Shops::model()->findAll($criteria);
        // var_dump($type);die();
        foreach($all_models_without_packets as $model)
        {
            $model->status = ($type=='public') ?  Shops::STATUS_PUBLISH : Shops::STATUS_CLOSED;
            $model->update();
        }

        $redirect_uri = ($type=='public') ?  '/admin/shops/list' : '/admin/shops/list/type/without_packets';
        $this->redirect( $redirect_uri );
    }

    public function actionHideMalloko()
    {
        $models = PartyMalloko::model()->findAll(
            array(
                    'condition'=> "discount = 0",
                    'group'=> "shops_id",
                )
            );
        foreach($models as $model)
        {
            // 
            $shop = Shops::model()->findByPk($model->shops_id);
            // var_dump($model->shops_id);
            if(!empty($shop))
            {
                $shop->status = Shops::STATUS_CLOSED;
                $shop->update();
            }
            
        }

        $this->redirect( '/admin/shops/list' );
    }

	public function actionCreate()
    {
        
    	
            $model = new Shops();
            if($model->malloko===null) $model->malloko = new PartyMalloko;
            $data = array();
            $array_places = array();
    
    		$validate = true;
    		$make_free_slot = true;
            $make_free_slot_places = true;
            
             
    		if(isset($_POST['AlternativeNamesShop']))
            {
    			foreach( $_POST['AlternativeNamesShop'] as $id_slot => $post_altname )
    			{
    				if( !empty ( $post_altname['title'] ) )
    				{
    					$array_altnames[$id_slot] = new AlternativeNamesShop;
    					$array_altnames[$id_slot]->attributes = $post_altname;
    					$validate = $array_altnames[$id_slot]->validate() && $validate;
    
    					
    				}
    
    
    			
    			}
                $make_free_slot = false;
            }



            if(isset($_POST['Place']))
        {

             
             
            foreach( $_POST['Place'] as $id_slot => $post_place )
            {
                
                if( (!empty ( $post_place['street'] ) && $post_place['malls_id'] == 0) || $post_place['malls_id'] != 0 )
                {
                     
                    $array_places[$id_slot] = (!empty($post_place['id'])) ? Place::model()->findByPk($post_place['id']) : new Place;
                    $array_places[$id_slot]->attributes = $post_place;
                    $validate = $array_places[$id_slot]->validate() && $validate;
                    
                    
                  
                    if( isset( $post_place['phone'] ) )
                    {

                       $array_phones = array();
                      // SiteHelper::mpr($post_place['phone']);
                        foreach( $post_place['phone']['number'] as $id_place_phone => $post_phone )
                        {
                             $id_place_phone_String = $post_place['phone']['id'][$id_place_phone];
                             $model_phone = PlacePhone::model()->findByPk($id_place_phone_String);
                             if($model_phone===null) $model_phone = new PlacePhone;
                             
                             
                             $model_phone->phone = $post_phone;
                             
                             
                            
                            $validate = $model_phone->validate() && $validate;
                            
                            $array_phones[] = $model_phone;
                            
                            
                            if( isset($model_phone->id) ) $array_exist_id_phones[] = $model_phone->id;
                        }
                           $array_places[$id_slot]->phones = $array_phones; 
                           

                    }
    
                    if( isset($array_places[$id_slot]->id) ) $array_exist_id_places[] = $array_places[$id_slot]->id;

                    $make_free_slot_places = false;
                }


                
            }

        }
        else
        {
        
            if( count($model->places) > 0 )
            {
                foreach ( $model->places as $obj )
                {
                    $array_places[$obj->id] = $obj;
                    
                    
                    $array_places[$obj->id]->phones = (count($obj->phones) > 0) ? $obj->phones : array( 1 => new PlacePhone);
                      
                    
                    
                }

                $make_free_slot_places = false;
            }
            
        }
     

    
    
    
            if(isset($_POST['Shops']))
            {
    
                $model->attributes = $_POST['Shops'];
                $validate = $model->validate() && $validate;
            }
    
    		if( $validate && isset($_POST['Shops']) && isset($_POST['AlternativeNamesShop'])  && isset($_POST['Place']) )
    		{
                
                
               if($model->save(false))
                    {
                        $model->malloko->attributes = $_POST['PartyMalloko'];

                        $model->malloko->shops_id = $model->id;
                        $model->malloko->save();
                    }
    
    
    				if( count($array_altnames) > 0 )
    				{
    					foreach( $array_altnames as $object )
    					{
    						$object->shops_id = $model->id;
    						$object->save(false);
    					}
    				}


                    if( count($array_places) > 0 )
                    {
                        foreach( $array_places as $object )
                        {
                           
                            $object->shops_id = $model->id;
                            
                            
                            
                            if($object->save(false))
                            {
                                foreach($object->phones as $phoneplace) 
                                {
                                    $phoneplace->place_id = $object->id;
                                    $phoneplace->save(false);
                                }
                            }
                        }
                    }
                    if($model->go_to_make_package)
    				    $this->redirect(array("/admin/shops/buildpackage/id_shop/{$model->id}"));
                    else
                        $this->redirect(array("/admin/shops/"));
    		}
    
    
    		if($make_free_slot) $array_altnames = array( 0 => new AlternativeNamesShop );
            if($make_free_slot_places) 
            {
                $array_places = array( 1 => new Place );
                $array_places[1]->phones = array( 1 => new PlacePhone);
                
               
            }
            
            $data['array_altnames'] = $array_altnames;
            $data['array_places'] = $array_places;
            
            
            
            
            $this->render('create', array('model' => $model, 'data'=>$data));
    }
    
    public function actionUpdate($id, $Shops_page=false)
    {
        
        
        $model = $this->loadModel('Shops', $id);
        $model->ex_categories_id = $model->categories_id;

        if($model->malloko===null) $model->malloko = new PartyMalloko;
        $validate = true;
		$make_free_slot_altnames = true;
        $make_free_slot_places = true;

		$array_altnames = array();
        $array_places = array();
		$array_exist_id_altnames = array();
        $array_exist_id_place = array();
        $array_exist_id_phones = array();
       

		if(isset($_POST['AlternativeNamesShop']))
        {
             
            
			foreach( $_POST['AlternativeNamesShop'] as $id_slot => $post_altname )
			{
				if( !empty ( $post_altname['title'] ) )
				{
					$array_altnames[$id_slot] = (!empty($post_altname['id'])) ? AlternativeNamesShop::model()->findByPk($post_altname['id']) : new AlternativeNamesShop;
					$array_altnames[$id_slot]->attributes = $post_altname;
                    
					$validate = $array_altnames[$id_slot]->validate() && $validate;

					if( isset($array_altnames[$id_slot]->id) ) $array_exist_id_altnames[] = $array_altnames[$id_slot]->id;

					$make_free_slot_altnames = false;
				}


				
			}

        }
		else
		{
		  
			if( count($model->altnames) > 0 )
			{
				foreach ( $model->altnames as $obj )
				{
					$array_altnames[$obj->id] = $obj;
				
				}

				$make_free_slot_altnames = false;
			}
		}
  
        
        
        if(isset($_POST['Place']))
        {
            
            // SiteHelper::mpr($_POST['Place']);die();
             
			foreach( $_POST['Place'] as $id_slot => $post_place )
			{
               
				if( (!empty ( $post_place['street'] ) && $post_place['malls_id'] == 0) || $post_place['malls_id'] != 0 )
				{
					$array_places[$id_slot] = (!empty($post_place['id'])) ? Place::model()->findByPk($post_place['id']) : new Place;
					$array_places[$id_slot]->attributes = $post_place;
                    $validate = $array_places[$id_slot]->validate() && $validate;
                    
                    
                   
                    if( isset( $post_place['phone'] ) )
                    {

                       $array_phones = array();
                       $array_phones_for_delete = array();
                       // $array_for_insert
                      
                        foreach( $post_place['phone']['number'] as $id_place_phone => $post_phone )
            			{
                            
                            if(!empty($post_phone))
                            {

                                 $id_place_phone_String = $post_place['phone']['id'][$id_place_phone];
                                 $model_phone = PlacePhone::model()->findByPk($id_place_phone_String);
                                     if($model_phone===null) $model_phone = new PlacePhone;
                                     
                                     
                                     $model_phone->phone = $post_phone;
                                     
                                     
                                    
                                    $validate = $model_phone->validate() && $validate;
                                    
                                    // if(isset($model_phone->id))
                                        $array_phones[] = $model_phone;
                                    
                                    
                                    if( isset($model_phone->id) ) $array_exist_id_phones[$array_places[$id_slot]->id][] = $model_phone->id;
                            }

                            // if(is_numeric($id_place_phone_String) && empty($post_phone))
                            //     $array_phones_for_delete[] = $id_place_phone_String;
                            // else
                            // {
                                
                            // }

            			     
                        }
                           $array_places[$id_slot]->phones = $array_phones; 
                           
                           // var_dump($array_phones);die('d');
                    }
                    
    //          echo count($array_places[$id_slot]->phones);       
    // die('');
					if( isset($array_places[$id_slot]->id) ) $array_exist_id_places[$array_places[$id_slot]->id] = $array_places[$id_slot]->phones;

					$make_free_slot_places = false;
				}


				//print_r($array_places[$id_slot]->phones);
			}

        }
		else
		{
		
			if( count($model->places) > 0 )
			{
				foreach ( $model->places as $obj )
				{
					$array_places[$obj->id] = $obj;
                    
                    
                    $array_places[$obj->id]->phones = (count($obj->phones) > 0) ? $obj->phones : array( 1 => new PlacePhone);
                      
                    
				    
				}

				$make_free_slot_places = false;
			}
            
		}




        if(isset($_POST['Shops']))
            {
    
                $model->attributes = $_POST['Shops'];
                $validate = $model->validate() && $validate;
            }
    
        
        
  		if( $validate && isset($_POST['Shops']) && isset($_POST['AlternativeNamesShop']) && isset($_POST['Place']) )
		{
	
				 
                
               if($model->save(false))
                    {
                         $model->malloko->attributes = $_POST['PartyMalloko'];

                        $model->malloko->shops_id = $model->id;
                        $model->malloko->save();
                    }

				// óäàëÿåì àëüòåðíàòèâíûå èìåíà
				if( count($array_exist_id_altnames) > 0 )
                {
                    $array_id_exist_string = implode(", ", $array_exist_id_altnames);

                    AlternativeNamesShop::model()->deleteAll("id not in ({$array_id_exist_string}) and shops_id = {$model->id}");
                }
                else AlternativeNamesShop::model()->deleteAll("shops_id = {$model->id}");

// SiteHelper::mpr($array_exist_id_phones);die();
    //             if( count($array_exist_id_phones) > 0 )
				// {
    //                 foreach ($array_exist_id_phones as $id_place => $array_ids) {
    //                     if(!empty($array_ids))
    //                     {
    //                         $array_id_exist_string = implode(", ", $array_ids);

    //                         PlacePhone::model()->deleteAll("id not in ({$array_id_exist_string}) and place_id = {$id_place}");
    //                     }
    //                     else PlacePhone::model()->deleteAll("place_id = {$id_place}");
    //                 }
					
				// }
                
                
                // if(!empty($array_phones_for_delete))
                // {

                //     $array_phones_for_delete_string = implode(", ", $array_phones_for_delete);
                    
                //     PlacePhone::model()->deleteAll("id in ({$array_phones_for_delete_string})");
                // }
                
                // óäàëÿåì àäðåñà
             //   echo count(count($array_exist_id_places));
				if( count($array_exist_id_places) > 0 )
				{
                    $ids_places_exist = array();
                    // SiteHelper::mpr($array_exist_id_places);
                    // die();
                     foreach($array_exist_id_places as $id_place => $array_ids_place_phone)
                     {
                        // var_dump($array_ids_place_phone);die();
                        $ids_places_exist[] = $id_place;
                        
                        if(!empty($array_ids_place_phone))
                        {

                            // $ids_places_places_exist = array();
                            // var_dump($ids_places_places_exist);
                            foreach ($array_ids_place_phone as $obj_place_phones) {
                                if(isset($obj_place_phones->id) && $obj_place_phones->id!="")
                                    {
                                        $ids_places_places_exist[] = $obj_place_phones->id;
                                    }
                                
                            }
                            // var_dump($ids_places_places_exist);die();
                           if(!empty($ids_places_places_exist))
                           {
                            $array_id_exist_string = implode(", ", $ids_places_places_exist);
                            PlacePhone::model()->deleteAll("id not in ({$array_id_exist_string}) and place_id = {$id_place}");
                           }else 
                            PlacePhone::model()->deleteAll("place_id = {$id_place}");
                            
                        }else 
                            PlacePhone::model()->deleteAll("place_id = {$id_place}");
                        

                     }

					$array_id_exist_string = implode(", ", $ids_places_exist);
					$places_for_del = Place::model()->findAll("id not in ({$array_id_exist_string}) and shops_id = {$model->id}");

                    
				}
                else{
                    $places_for_del = Place::model()->findAll("shops_id = {$model->id}");
                }

                foreach($places_for_del as $place_for_del) $place_for_del->delete();
                
                
                //Ñîõðàíÿåì àëüòåðíàòèâíûå èìåíà
				if( count($array_altnames) > 0 )
				{
					foreach( $array_altnames as $object )
					{
						$object->shops_id = $model->id;
						
						$object->save(false);
					}
				}
               // SiteHelper::mpr($array_places);die();

                //Ñîõðàíÿåì àäðåñà
				if( count($array_places) > 0 )
				{

					foreach( $array_places as $object )
					{
                        // SiteHelper::mpr($object);die();
						$object->shops_id = $model->id;
                        
                        
						
						if($object->save(false))
                        {
                            foreach($object->phones as $phoneplace) 
                            {
                                $phoneplace->place_id = $object->id;
                                $phoneplace->save(false);
                            }
                        }
					}
				}
                
                



				if($model->go_to_make_package)
                        $this->redirect(array("/admin/shops/buildpackage/id_shop/{$model->id}?changecategory=yes&Shops_page={$Shops_page}"));
                    else
                        $this->redirect(array("/admin/shops/list",'Shops_page'=>$Shops_page));

		}


		if($make_free_slot_altnames) $array_altnames = array( 1 => new AlternativeNamesShop );
        if($make_free_slot_places) 
        {
            $array_places = array( 1 => new Place );
            $array_places[1]->phones = array( 1 => new PlacePhone);
            
           
        }
        
        $data['array_altnames'] = $array_altnames;
        $data['array_places'] = $array_places;
        
        

       

        $this->render('update', array('model' => $model, 'data'=>$data, 'Shops_page'=>$Shops_page));
    }


    public function actionBuildPackage($id_shop, $Shops_page=false)
    {

        $model= Shops::model()->with('category')->findByPk($id_shop);
        if($model === NULL) throw new CHttpException(404,'Такой магазин не существует');

        $cs = Yii::app()->clientScript;
        Yii::app()->getClientScript()->registerCoreScript('jquery');
        Yii::app()->getClientScript()->registerCoreScript( 'jquery.ui' );
        $cs->registerCssFile($cs->getCoreScriptUrl().'/jui/css/base/jquery-ui.css', 'screen');
        //Include fancybox.js for Canvas
     
        $cs->registerScriptFile($this->getAssetsUrl().'/js/fancybox/lib/jquery.mousewheel-3.0.6.pack.js', CClientScript::POS_HEAD);
        $cs->registerScriptFile($this->getAssetsUrl().'/js/fancybox/source/jquery.fancybox.js', CClientScript::POS_HEAD);
        //Include Fabric.js for Canvas
       
        $cs->registerScriptFile($this->getAssetsUrl().'/js/fabricjs/all.js', CClientScript::POS_HEAD);
        //Include my js file
        $cs->registerScriptFile($this->getAssetsUrl().'/js/builder.js', CClientScript::POS_END);
        //Include color-picker
        $cs->registerCssFile($this->getAssetsUrl().'/js/colorpicker/css/colorpicker.css', 'screen');
        $cs->registerScriptFile($this->getAssetsUrl().'/js/colorpicker/js/colorpicker.js', CClientScript::POS_HEAD);

        $cs->registerCssFile($this->getAssetsUrl().'/js/fancybox/source/jquery.fancybox.css', 'screen');
        $cs->registerCssFile($this->getAssetsUrl().'/css/style.css', 'screen');


     


        $this->render('build', array('model'=>$model,'Shops_page'=>$Shops_page));
    }


    public function actionBuilder(){


        $id_shop = $_POST['Image']['id_shop'];
    

        $model= Shops::model()->findByPk($id_shop);

        $is_new = ($model->path_package == null) ? "new" : "ok";

        if($model === NULL) throw new CHttpException(404,'Такой магазин не существует');
   
        if(isset($_POST['Image']))
        {
            $model->attributes=$_POST['Image'];

            if(!empty($_POST['Image']['path_package'])){
                $img = $_POST['Image']['path_package'];
                $img = str_replace('data:image/png;base64,', '', $img);
                $img = str_replace(' ', '+', $img);
                $data = base64_decode($img);

                $folder = YiiBase::getPathOfAlias('webroot').Shops::uploadsDirName.'tmp/';
                
                if(!is_dir($folder)) @mkdir($folder);

                $file = $folder.'create'.'.png';


                
                if(file_put_contents($file, $data)){
                    $model->path_package = $this->createBuildImage($file, 'png', $model);
                    $model->status = Shops::STATUS_PUBLISH;
                }
                //delete tmp files
                $files = glob($folder.'*'); // get all file names
                foreach($files as $file){ // iterate files
                  if(is_file($file))
                    unlink($file); // delete file
                }
            }

            if(!empty($_POST['Image']['path_package_discount'])){
                $img = $_POST['Image']['path_package_discount'];
                $img = str_replace('data:image/png;base64,', '', $img);
                $img = str_replace(' ', '+', $img);
                $data = base64_decode($img);

                $folder = YiiBase::getPathOfAlias('webroot').Shops::uploadsDirName.'tmp/';
                
                if(!is_dir($folder)) @mkdir($folder);

                $file = $folder.'create2'.'.png';
                
                if(file_put_contents($file, $data)){
                    $this->createBuildImage($file, 'png', $model, true);
                }
                //delete tmp files
                $files = glob($folder.'*'); // get all file names
                foreach($files as $file){ // iterate files
                  if(is_file($file))
                    unlink($file); // delete file
                }
            }

           
            if($model->save()){
                echo $is_new;
            }
                
        }

        Yii::app()->end();
    }

    private function createBuildImage($file, $extension, $model, $discount = false, $block="shops"){
        if($file){
            $uploadsDir =  YiiBase::getPathOfAlias('webroot').Shops::uploadsDirName;
            if(!is_dir($uploadsDir)) @mkdir($uploadsDir);



            $blockDir = $uploadsDir.Shops::folder_name.'/';
            if(!is_dir($blockDir)) @mkdir($blockDir);

            $shopDir = $blockDir.Shops::getFolderName( $model->id, $model->title ).'/';
            if(!is_dir($shopDir)) @mkdir($shopDir);

            $thumbDir = $shopDir.'thumbs/';
            if(!is_dir($thumbDir)) @mkdir($thumbDir);


            $package_file = Yii::app()->phpThumb->create($file);
            $package_file_thumb = Yii::app()->phpThumb->create($file);
            // $thumbRetina = Yii::app()->phpThumb->create($file);

            $prefix = ($discount) ? "dis_" : "";
            $fileName = md5(mktime()).".".$extension;
            $fileName = $prefix.$fileName;
            $size = getimagesize($file);

            $retinaW = $size[0];
            $retinaH = $size[1];

            $defW = floor($size[0]/10);
            $defH = floor($size[1]/10);

            // $thumb->adaptiveResize(100, 100)->save($thumbDir.$fileName);
            $package_file_thumb->resize($defW, $defH)->save($thumbDir.$fileName);
            $package_file->resize($retinaW, $retinaH)->save($shopDir.$fileName);

            return $fileName;
        }
        return '';
    }


    public function actionBindfoursquare($id_shop, $foursquare_id = false, $place_id = false, $action = false, $Shops_page=false)
    {
        if(Yii::app()->request->isAjaxRequest && $foursquare_id && $place_id)
        {
            header('Content-type: application/json');
            $bind = BindFoursqaure::model()->find("id_foursquare = :id_foursquare and id_place = :id_place",
                [':id_foursquare'=>$foursquare_id, ':id_place'=>$place_id]);
            
            if(empty($bind) && $action == 'create')
            {
                $new_bind = new BindFoursqaure;
                $new_bind->id_place = $place_id;
                $new_bind->id_foursquare = $foursquare_id;

                $done = $new_bind->save();
            }
            elseif($action == 'delete')
            {
               $done = $bind->delete();
            }
            else $done = false;
           

            $result = [ 
                'done'=>$done,
             ];
            
            echo CJSON::encode($result);
            Yii::app()->end();
        }

         // $model = Shops::model()->with(['places'=>['condition'=>'places.status=:st','params'=>[':st'=>Place::STATUS_PUBLISH]]])->findByPk($id_shop, "t.status = :status", [':status'=>Shops::STATUS_PUBLISH]);
         $model = Shops::model()->findByPk($id_shop);
         // echo $model->id;die();
         Yii::app()->clientScript->registerScriptFile($this->getAssetsUrl().'/js/foursquare_bind.js', CClientScript::POS_END);

         $this->render('bind_foursquare', array('model'=>$model, 'Shops_page'=>$Shops_page));
    }


    // //Save image
    // private function createImage($file, $extension, $block='shops'){

    //     if($file){

    //         $uploadsDir =  YiiBase::getPathOfAlias('webroot').$this->uploadsDirName;
    //         if(!is_dir($uploadsDir)) @mkdir($uploadsDir);

    //         $blockDir = $uploadsDir.$block.'/';
    //         if(!is_dir($blockDir)) @mkdir($blockDir);

    //         $retinaDir = $blockDir.'retina/';
    //         if(!is_dir($retinaDir)) @mkdir($retinaDir);

    //         $thumbDir = $blockDir.'thumbs/';
    //         if(!is_dir($thumbDir)) @mkdir($thumbDir);

    //         $thumb = Yii::app()->phpThumb->create($file);
    //         $thumbDef = Yii::app()->phpThumb->create($file);
    //         $thumbRetina = Yii::app()->phpThumb->create($file);

    //         $fileName = md5(mktime()).".".$extension;

    //         $thumb->adaptiveResize(100, 100)->save($thumbDir.$fileName);
    //         $thumbDef->resize(320)->save($blockDir.$fileName);
    //         $thumbRetina->resize(640)->save($retinaDir.$fileName);

    //         return $fileName;
    //     }
    //     return '';
    // }


    public function actionGetImage(){
        Yii::import("ext.EAjaxUpload.qqFileUploader");
        $model = new Shops;

        $folder =  YiiBase::getPathOfAlias('webroot').Shops::uploadsDirName.'tmp/';// folder for uploaded files
        if(!is_dir($folder)) @mkdir($folder);

        $allowedExtensions = array("jpg","jpeg","gif","png");//array("jpg","jpeg","gif","exe","mov" and etc...
        $sizeLimit = 4 * 1024 * 1024;// maximum file size in bytes
        $uploader = new qqFileUploader($allowedExtensions, $sizeLimit);
        $result = $uploader->handleUpload($folder);
        echo $result;
        $result=htmlspecialchars(json_encode($result), ENT_NOQUOTES);
        echo $result;// it's array
        Yii::app()->end();
    }

    public function actionImportShops()
    {
        $data = array();
        if(isset($_POST['Shops']))
        {
            $id_mall = $_POST['Shops']['id_mall'];
            foreach($_POST['Shops']['data'] as $postion_import)
            {
                $mall_model = Malls::model()->findByPk($id_mall);
                
               // SiteHelper::mpr($postion_import);
                switch ($postion_import['type']) {
                    case 'exist':
                    // магазин существует, его нужно обновить
                        $shop_model = Shops::model()->findByPk($postion_import['id_shop']);
                        $shop_model->attributes = $postion_import['Shops'];
                        if($shop_model->save())
                        {
                            $part_malloko = (is_null($shop_model->malloko)) ? new PartyMalloko : $shop_model->malloko;
                            $part_malloko->discount = $postion_import['discount'];
                            $part_malloko->save();

                            $found_phone = false;
                            $found_id_place = null;
                            foreach($shop_model->places as $place)
                            {
                                if($place->malls_id == $id_mall)
                                {
                                    $found_id_place = $place->id;
                                    foreach($place->phones as $phone)
                                    {
                                        if($phone->phone == $postion_import['phone'])
                                        {
                                            $found_phone = true;
                                            break;
                                        }
                                    }
                                }
                                
                            }
                            if(!$found_phone)
                            {
                                //  телефон не найдет - добавляем
                                $place_phone = new PlacePhone;
                                $place_phone->phone = $postion_import['phone'];
                                $place_phone->place_id = $found_id_place;
                                $place_phone->save();
                            }
                            
                        }

                        break;
                    
                    case 'havnt':
                    // магазин не существует, будем добавлять
                        $shop_model = new Shops;
                        $shop_model->attributes = $postion_import['Shops'];
                        $shop_model->status = 0;
                        if($shop_model->save())
                        {
                            $part_malloko = new PartyMalloko;
                            $part_malloko->shops_id = $shop_model->id;
                            $part_malloko->discount = $postion_import['discount'];
                            $part_malloko->save();

                            $place = new Place;
                            $place->street = $mall_model->default_street;
                            $place->shops_id = $shop_model->id;
                            $place->malls_id = $mall_model->id;
                            $place->status = 1;
                            if($place->save())
                            {
                                $place_phone = new PlacePhone;
                                $place_phone->phone = $postion_import['phone'];
                                $place_phone->place_id = $place->id;
                                $place_phone->save();
                            }
                        }
                        
                        break;

                    case 'havnt_but_in_db':
                    // магазин не существует в этом ТРЦ, но существует в БД
                        $shop_model = Shops::model()->findByPk($postion_import['id_shop']);
                        // var_dump($shop_model->malloko->attributes);die();
                        $shop_model->attributes = $postion_import['Shops'];
                        if($shop_model->save())
                        {
                            $part_malloko = (is_null($shop_model->malloko)) ? new PartyMalloko : $shop_model->malloko;
                            $part_malloko->discount = $postion_import['discount'];
                            $part_malloko->save();

                            $place = new Place;
                            $place->street = $mall_model->default_street;
                            $place->shops_id = $shop_model->id;
                            $place->malls_id = $mall_model->id;
                            $place->status = 1;
                            if($place->save())
                            {
                                $place_phone = new PlacePhone;
                                $place_phone->phone = $postion_import['phone'];
                                $place_phone->place_id = $place->id;
                                $place_phone->save();
                            }
                        }

                        break;
                    case 'remove':
                    // удаляем этот магазин из этого ТРЦ

                        if(empty($postion_import['id_shop'])) continue;
                        $shop_model = Shops::model()->findByPk($postion_import['id_shop']);
                        // SiteHelper::mpr($postion_import);
                        // echo count($shop_model->places);
                   
                        
                        foreach($shop_model->places as $place)
                        {
                            

                            if($place->malls_id == $id_mall)
                            {
                                // echo $place->malls_id;
                                $area = BindsShopArea::model()->find(
                                    "shops_id = :shops_id and id_mall = :id_mall", 
                                    array(
                                            ':shops_id'=>$shop_model->id,
                                            ':id_mall'=>$id_mall,
                                        )
                                    );
                                // var_dump($area);
                                // echo $area->area_id;
                                if($area)
                                {
                                    $data = unserialize($area->mallplan->json_areas);
                                    // SiteHelper::mpr($data);die();
                                    unset($data[$area->area_id]);
                                    $area->mallplan->json_areas = serialize($data);
                                    if($area->mallplan->update())
                                        $area->delete();
                                }
                                

                               
                                $place->delete();
                                        break;
                                 
                            }
                            
                        }
                             
                            // die('remove');
                        

                        break;
                }
               
            }
             // die();
        }
        elseif(isset($_FILES['Import']))
        {
             $info = pathinfo($_FILES['Import']['name']['xls']);
             $ext = $info['extension']; // get the extension of the file
             $newname = "new_import.{$ext}"; 

             if (!is_dir("xls_import/"))
                    mkdir("xls_import/");

             $target = 'xls_import/'.$newname;
             move_uploaded_file( $_FILES['Import']['tmp_name']['xls'], $target);

             $all_categories_models = Categories::model()->findAll();
             foreach($all_categories_models as $category)
                    $all_categories[$category->title] = $category->id;




            
            $import = new v2_ImportXls;
            $import->file = $target;
            $import->mall = Malls::model()->findByPk($_POST['Import']['id_mall']);
            $import->all_categories = $all_categories;



            $data['prepare'] = $import->begin();
            $data['all_categories'] = array_flip($all_categories);
            $data['all_types'] = Shops::getTypes();
            $data['selected_mall'] = $import->mall;

            // die();
        }
        $malls = Malls::model()->findAll();
        $data['malls'] = CHtml::listData($malls,'id','title');
        $this->render('import', array('data'=>$data));
    }


    public function actionList($type=false)
    {
        // $model = $this->loadModel('Shops', $malls_id);
        $shops = new Shops('search');
        $shops->unsetAttributes();
        if ( isset($_GET['Shops']) ) {
            $shops->attributes = $_GET['Shops'];
        }
        
       
        if($type=='without_packets')
        {
            $array_without_files = array();
            $all_shops = Shops::model()->findAll();
            foreach($all_shops as $shop)
           {
                $file =  dirname(__FILE__)."/../../../../".$shop->getSmallPackageUrl();
                if(!is_file($file))
                    $array_without_files[] = $shop->id;
           }
                
            $shops->id = $array_without_files;
        }

        
        $this->render('list', array(
            // 'model' => $model,
            'model' => $shops,
            'type' => $type,
        )); 
    }
}
