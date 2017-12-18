<?php
/**
 * Controller is the customized base controller class.
 * All controller classes for this application should extend from this base class.
 */
class Controller extends CController
{
    public $ALLOWED_COUNTRIES = array('ru','en'); // если будем расширять языки, надо чтобы и в /config/cron.php, добавить в params

    public static function languageTranslate( $lang )
    {
        $langs = array(
                'ru'=>'Русский язык',
                'en'=>'Английский язык',
            );

        return $langs[$lang];
    }
    /**
     * @var string page name.
     */
    public $title;
    /**
     * @var string the default layout for the controller view. Defaults to '//layouts/column1',
     * meaning using a single column layout. See 'protected/views/layouts/column1.php'.
     */
    public $layout='//layouts/simple';

    /**
     * @var array context menu items. This property will be assigned to {@link CMenu::items}.
     */
    public $menu=array();
    /**
     * @var array the breadcrumbs of the current page. The value of this property will
     * be assigned to {@link CBreadcrumbs::links}. Please refer to {@link CBreadcrumbs::links}
     * for more details on how to specify this property.
     */
    public $breadcrumbs=array();

    //for link in main menu
    public $action = null;

    public $cs;

    protected function preinit()
    {
        parent::preinit();
    }

    public function init(){
        parent::init();
        $this->title = Yii::app()->name;
        $this->cs = Yii::app()->clientScript;
        $this->cs->registerCoreScript('jquery');
        if(Yii::app()->getRequest()->getParam('update_assets')) $this->forceCopyAssets = true;



        // work with lang
        if (!empty($_GET['language']))
        {
            if(!in_array($_GET['language'], $this->ALLOWED_COUNTRIES))
                throw new CHttpException(404, 'No found!');

            Yii::app()->language = $_GET['language'];
            Yii::app()->request->cookies['country'] = new CHttpCookie('country', Yii::app()->language );
        }
        else
        {
            $request_uri = $_SERVER['REQUEST_URI'];
            if(!empty(Yii::app()->request->cookies['country']->value))
                $country = Yii::app()->language = Yii::app()->request->cookies['country']->value;
            else
            {
                function getLocationInfoByIp(){
                    $client  = @$_SERVER['HTTP_CLIENT_IP'];
                    $forward = @$_SERVER['HTTP_X_FORWARDED_FOR'];
                    $remote  = @$_SERVER['REMOTE_ADDR'];
                    $result  = array('country'=>'', 'city'=>'');
                    if(filter_var($client, FILTER_VALIDATE_IP)){
                        $ip = $client;
                    }elseif(filter_var($forward, FILTER_VALIDATE_IP)){
                        $ip = $forward;
                    }else{
                        $ip = $remote;
                    }
                    // echo $ip;die();
                    $ip_data = @json_decode(file_get_contents("http://www.geoplugin.net/json.gp?ip=".$ip));    
                    // var_dump($ip_data);die();
                    if($ip_data && $ip_data->geoplugin_countryName != null){
                        $result['country'] = $ip_data->geoplugin_countryCode;
                        $result['city'] = $ip_data->geoplugin_city;
                    }
                    return $result;
                }




                $country = strtolower(getLocationInfoByIp()['country']);
               
                

                if(!in_array($country, $this->ALLOWED_COUNTRIES))
                {
                    $country = 'en';
                    $request_uri = '';
                }

                Yii::app()->request->cookies['country'] = new CHttpCookie('country', $country);
            }

            // die();

            // function strpos_array($haystack, $needles) {
                    
            //         if ( is_array($needles) ) {
            //             foreach ($needles as $str) {
            //                 if ( is_array($str) ) {
            //                     $pos = strpos_array($haystack, $str);
            //                 } else {
            //                     $pos = strpos($haystack, $str);
            //                 }
            //                 if ($pos !== FALSE) {
            //                     return $pos;
            //                 }
            //             }
            //         } else {
            //             return strpos($haystack, $needles);
            //         }
            //     }
            
            // $exceptions = strpos_array($request_uri, array('sort', 'delete', 'login','user', 'gii', 'gallery', 'TranslatePhpMessage'));

            // if (is_null($exceptions)) {

            //     $pos = strpos($request_uri, $country);
            //     if ($pos === false) {
            //         // die('dsds');
            //        $this->redirect("/{$country}{$request_uri}");
            //     }

            // }
                // echo $request_uri;die();
                // $this->redirect("{$request_uri}");
            // }
            
        }
    }




    //Get Clip
    public function getClip($name){
        if (isset($this->clips[$name])) return $this->clips[$name];
        return '';
    }

    //Check home page
    public function is_home(){
        return $this->route == 'site/index';
    }

	protected $assetsUrl;
	protected $assetsMap = array();
	protected $forceCopyAssets = true;
	public function getAssetsUrl($moduleName = false)
	{
		if ( $moduleName ) {
			if ( !isset($this->assetsMap[$moduleName]) ) {
				if ( $moduleName === 'application' and isset(Yii::app()->theme) ) {
					$assetsPath = Yii::app()->theme->getBasePath().DIRECTORY_SEPARATOR.'assets';
				} else {
					$assetsPath = Yii::getPathOfAlias($moduleName.'.assets');
				}
				$this->assetsMap[$moduleName] = Yii::app()->assetManager->publish($assetsPath, false, -1, $this->forceCopyAssets);
			}
			return $this->assetsMap[$moduleName];
		}

		if ( !isset($this->assetsUrl) )
		{
			if ( $this->module ) {
				$assetsPath = Yii::getPathOfAlias($this->module->name.'.assets');
			} else if ( isset(Yii::app()->theme) ) {
				$assetsPath = Yii::app()->theme->getBasePath().DIRECTORY_SEPARATOR.'assets';
			} else {
				$assetsPath = Yii::getPathOfAlias('application.assets');
			}
			$this->assetsUrl = Yii::app()->assetManager->publish($assetsPath, false, -1, $this->forceCopyAssets);
		}
		return $this->assetsUrl;
	}

    public function beforeRender($view)
    {
        return parent::beforeRender($view);
    }

    /**
     * Loads the requested data model.
     * @param string the model class name
     * @param integer the model ID
     * @param array additional search criteria
     * @param boolean whether to throw exception if the model is not found. Defaults to true.
     * @return CActiveRecord the model instance.
     * @throws CHttpException if the model cannot be found
     */
    protected function loadModel($class, $id, $criteria = array(), $exceptionOnNull = true)
    {
        if (empty($criteria)) {
            $model = CActiveRecord::model($class)->findByPk($id);
        } else {
            $finder = CActiveRecord::model($class);
            $c = new CDbCriteria($criteria);
            $c->mergeWith(array(
                'condition' => $finder->tableSchema->primaryKey . '=:id',
                'params' => array(':id' => $id),
            ));
            $model = $finder->find($c);
        }
        if (isset($model))
            return $model;
        else if ($exceptionOnNull)
            throw new CHttpException(404, 'Unable to find the requested object.');
    }

    /**
     * Performs the AJAX validation.
     * @param CModel the model to be validated
     */
    protected function performAjaxValidation($model)
    {
        if (isset($_POST['ajax']) && $_POST['ajax'] === $model->formId)
        {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

    /**
     * Outputs (echo) json representation of $data, prints html on debug mode.
     * NOTE: json_encode exists in PHP > 5.2, so it's safe to use it directly without checking
     * @param array $data the data (PHP array) to be encoded into json array
     * @param int $opts Bitmask consisting of JSON_HEX_QUOT, JSON_HEX_TAG, JSON_HEX_AMP, JSON_HEX_APOS, JSON_FORCE_OBJECT.
     */
    public function renderJson($data, $opts=null)
    {
        if(YII_DEBUG && isset($_GET['debug']) && is_array($data))
        {
            foreach($data as $type => $v)
                printf('<h1>%s</h1>%s', $type, is_array($v) ? json_encode($v, $opts) : $v);
        }
        else
        {
            header('Content-Type: application/json; charset=UTF-8');
            echo json_encode($data, $opts);
        }
    }

    /**
     * Utility function to ensure the base url.
     * @param $url
     * @return string
     */
    public function baseUrl( $url = '' )
    {
        static $baseUrl;
        if ($baseUrl === null)
            $baseUrl = Yii::app()->request->baseUrl;
        return $baseUrl . '/' . ltrim($url, '/');
    }


	protected function disableLogRoutes()
	{
		foreach (Yii::app()->log->routes as $route)
		{
			if ($route instanceof CLogRoute)
			{
				$route->enabled = false;
			}
		}
	}
}