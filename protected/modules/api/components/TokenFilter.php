<?php


class TokenFilter extends CAccessControlFilter {

	public $got_user = false;
	/**
	 * Performs the pre-action filtering.
	 * @param CFilterChain $filterChain the filter chain that the filter is on.
	 * @return boolean whether the filtering process should continue and the action
	 * should be executed.
	 */
	protected function preFilter($filterChain)
	{

		$app=Yii::app();
		$request=$app->getRequest();
		$user=$app->getUser();
		$verb=$request->getRequestType();
		$ip=$request->getUserHostAddress();


		foreach($this->getRules() as $rule)
		{
			// var_dump($this->getRules());
			// var_dump($user);


			if(($allow=$rule->isUserAllowed($user,$filterChain->controller,$filterChain->action,$ip,$verb))>0)
			{
				return true;
			}
			// die('sdass');\
			// die('sdass');
		}


	
		$json = new JsonModel;
		
		

		
		if(!$this->got_user)
		{
			$json->returnError(JsonModel::INVALID_TOKEN);
			return false;
		}
		else return true;
		
	}

}