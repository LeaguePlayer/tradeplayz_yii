<?php

class MallPlanController extends AdminController
{
    public function actionCreate($malls_id)
    {
        $model = new MallPlan();
        $model->malls_id = $malls_id;

        if(isset($_POST['MallPlan']))
        {
            $model->attributes = $_POST['MallPlan'];

            $success = $model->save();
            if( $success ) {
                $this->redirect(array('/admin/mallplan/list', 'malls_id'=>$model->malls_id));
            }
        }
        $this->render('create', array('model' => $model));
    }

    public function actionUpdate($id)
    {
        $model = $this->loadModel('MallPlan', $id);
        if(isset($_POST['MallPlan']))
        {
            $model->attributes = $_POST['MallPlan'];
            $success = $model->save();
            if( $success ) {
                $this->redirect(array('/admin/mallplan/list', 'malls_id'=>$model->malls_id));
            }
        }
        $this->render('update', array('model' => $model));
    }

    public function actionList($malls_id)
    {
        $model = $this->loadModel('Malls', $malls_id);
        $mallplan_finder = new MallPlan('search');
        $mallplan_finder->unsetAttributes();
        if ( isset($_GET['MallPlan']) ) {
            $mallplan_finder->attributes = $_GET['MallPlan'];
        }
        $mallplan_finder->malls_id = $model->id;

        
        $this->render('list', array(
            'model' => $model,
            'mallplan_finder' => $mallplan_finder
        )); 
    }

    public function actionMarking($id)
    {
        $model = $this->loadModel('MallPlan', $id);

        $cs = Yii::app()->clientScript;
        $cs->registerCssFile($this->getAssetsUrl().'/css/fancybox/jquery.fancybox.css');
        $cs->registerScriptFile($this->getAssetsUrl().'/js/jquery.fancybox.js', CClientScript::POS_HEAD);
        $cs->registerScriptFile($this->getAssetsUrl().'/js/raphael.js', CClientScript::POS_HEAD);
        $cs->registerScriptFile($this->getAssetsUrl().'/js/raphael.pan-zoom.min.js', CClientScript::POS_HEAD);
        $cs->registerScriptFile($this->getAssetsUrl().'/js/marking.js', CClientScript::POS_END);
        
        $all_areas = unserialize($model->json_areas);
      

        $this->render('marking', array('model'=>$model, 'all_areas'=>$all_areas));
    }

    public function actionCreateRegion()
    {
        $array_json = array();
        $coords = $_POST['Plots']['coords'];
        $id_mallplan = $_POST['Plots']['image_map_id'];
        $id_mall = $_POST['Plots']['id_mall'];
// SiteHelper::mpr($_POST['Plots']);die();
        $model = MallPlan::model()->findByPk($id_mallplan);

        if($model !== null)
        {
            $array_json = unserialize($model->json_areas);
            
            $key = (unserialize($model->json_areas)) ? end(array_keys($array_json))+1 : 1;


            $array_json[$key] = $_POST['Plots']['coords'];
            
            $model->json_areas = serialize($array_json);
            
            if($model->update())
            {
                $new_bind = new BindsShopArea;
                $new_bind->area_id = $key;
                $new_bind->id_plan = $id_mallplan;
                $new_bind->id_mall = $id_mall;
                // SiteHelper::mpr($new_bind->attributes);die();
                if($new_bind->save())
                     echo $key;
            }

            
            //echo 'ne ravno null';
        }
        //echo "123";
    }

    public function actionViewRegion($id, $id_plan)
    {

        

        $bind = BindsShopArea::model()->find("area_id = :id and id_plan = :id_plan", [':id'=>$id, ':id_plan'=>$id_plan]);



        if(!empty($bind))
        {
            $data = [];

            if(isset($_POST['BindsShopArea']))
            {
                if(isset($_POST['BindsShopArea']['removeArea']) && $_POST['BindsShopArea']['removeArea'] == 'remove')
                    {
                        $areasArray = unserialize($bind->mallplan->json_areas);
                        unset($areasArray[$bind->area_id]);
                        $bind->mallplan->json_areas = serialize($areasArray);
                        $bind->mallplan->update();
                        $bind->delete();
                    }
                else
                {
                    $bind->attributes = $_POST['BindsShopArea'];
                    $bind->update();
                }
                    
                    $this->redirect(['/admin/mallplan/marking/', 'id'=>$id_plan]);
            }

            $mallplan = MallPlan::model()->findByPk($id_plan);

            $get_all_place_by_mall = Place::model()->findAll( array(
                    'select'=>'shops_id',
                    'condition'=>'malls_id = :id_mall and status = :status',
                    'group'=>'shops_id',
                    'params'=>[
                        ':id_mall'=>$mallplan->malls_id,
                        ':status'=>Place::STATUS_PUBLISH,
                    ],
                ) );
            if($get_all_place_by_mall!==null)
            {
                $array_places = [];
                foreach($get_all_place_by_mall as $place_mall)
                    $array_places[]=$place_mall['shops_id'];

                  $string_ids_shops = implode(', ', $array_places);
            }

            

            $shops_models = Shops::model()
                ->findAll( array(
                    'condition'=>"status = :status and id in ({$string_ids_shops})",
                    'order'=>'title ASC',
                    'params'=>[
                        ':status'=>Shops::STATUS_PUBLISH,
                       
                    ],
                ) );

            foreach($shops_models as $shop)
                $data['shops'][$shop->id] = $shop->title;

            $data['id_plan'] = $id_plan;


            $this->renderPartial('_bind_shop_area', array('bind'=>$bind, 'data'=>$data));
        }
        else 
            throw new CHttpException(404,'The specified post cannot be found.');
        
        
    }

    public function actionDelete($id)
    {
        $model = Mallplan::model()->findByPk($id);
        if ( $model )
            $model->delete();
        $this->redirect(array('list','malls_id'=>$model->malls_id));
    }

    
}
