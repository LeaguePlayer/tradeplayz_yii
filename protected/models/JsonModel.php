<?php

class JsonModel
{
	
	private $response = array();
	public $error_text = "";
	public $detail_error = false;


	public function registerResponseObject($objectName, $objectValue)
	{
		$this->response[$objectName] = $objectValue;

		return true;
	}


	public function returnJson()
	{
		$result = array();

		$result['result'] = 1;
		$result['response'] = $this->response;

		header('Content-type: application/json');
		echo CJSON::encode($result);
	}




	public function returnError($code)
	{

		$result = array();

		$result = ($code == self::RETURN_NULL) ? $this->getResultNull() : $this->getResult($code);

		
		if($this->detail_error)
			$result['detail_error'] = $this->detail_error;

		header('Content-type: application/json');
		echo CJSON::encode($result);

	}

	public function getResultNull()
	{
		$array  = array();
		$array['result'] = 2;
		$array['message'] = (empty($this->error_text)) ? "Нет результатов" : $this->error_text;
		
		return $array;
	}


	public function getResult($code)
	{
		$array  = array();
		$array['result'] = 0;
		$array['error_code'] = $code;
		$array['error_text'] = (empty($this->error_text)) ? self::getErrorByCode($code) : $this->error_text;
		
		return $array;
	}


	const INVALID_TOKEN = 100;
	const RETURN_NULL = 200;

	const CUSTOM_ERROR = 500;

	protected function getErrorByCode($code)
	{
		$array = array(
						self::INVALID_TOKEN=>Yii::t('main','token_error'),
						self::RETURN_NULL=>'Вернулось 0 значений',

						self::CUSTOM_ERROR=>'Неизвестная ошибка',
					  );


		return $array[$code];
	}

}