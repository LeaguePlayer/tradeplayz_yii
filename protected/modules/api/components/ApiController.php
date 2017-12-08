<?php


class ApiController extends Controller
{
	public $domain;
	public $user = false;
	public $token = null;
	public $allowed_langs = array('ru','en');

	public function filterTokenControl($filterChain)
	{
		Yii::import('application.modules.api.components.TokenFilter');
		$filter=new TokenFilter;
		$filter->got_user = $this->user;
		$filter->setRules($this->accessRules());
		
		$filter->filter($filterChain);
		// die('s');
		if($filter->got_user) $this->user = Users::model()->findByPk($filter->got_user);
		
	}

	public function filters() {
	       return array(
            
            'tokenControl',
            
        );
	}

	public function init()
	{
		parent::init();

		if(isset($_GET['language']))
			if(in_array($_GET['language'], $this->allowed_langs))
				Yii::app()->language = $_GET['language'];
		
		


		$this->domain = "http://{$_SERVER['HTTP_HOST']}";
		$this->user = $this->getUser();

		return true;
	}

	protected function getUser()
	{
		$got_token = $_GET['token'];
		$this->token = $got_token;

		$tokenModel = TokenUser::model()->find(array( 'condition'=>"token = :token", 'params'=>array( ':token'=>$got_token ) ));
		if($tokenModel===null)
			return false;
		else
			return Users::model()->findByPk($tokenModel->id_user);

	}

	// открывает доступ к экшенам без токена
	// public function accessRules()
	// 	{
	// 		return array(
	// 			array('allow',  // allow all users to perform 'index' and 'view' actions
	// 				'actions'=>array('index','view'),
	// 			),
	// 		);
	// 	}
}