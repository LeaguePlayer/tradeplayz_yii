<?php

/**
 * Class ApiEngine
 * @author Rochev Maxim <maxro.kuzmitch@gmail.com>
 *
 * @property $request ApiRequest
 */
class ApiEngine
{
    public $secretKeyVar = 'key';

    private $_request;
    private $_response;
    // private $_secret_key = 'erft556hfdGlsd@56-)=84G';
    private $_secret_key = 'dP!7POznKVH\w6cg9??W8';


    /**
     *
     */
    public function __construct()
    {
        $request = $this->getRequest();
        $response = $this->getResponse();

        if ( ($format = $request->getQueryParam('format', null)) !== null ) {
            $response->format = $format;
        }

        if ( ($debug = $request->getQueryParam('debug', null)) !== null ) {
            $response->format = Response::FORMAT_DEBUG;
        }
    }


    /**
     * @return Request
     */
    public function getRequest()
    {
        if ( $this->_request == null ) {
            $this->_request = new Request();
        }
        return $this->_request;
    }

    /**
     * @return Response
     */
    public function getResponse()
    {
        if ( $this->_response == null ) {
            $this->_response = new Response();
        }
        return $this->_response;
    }


    /**
     * @param $name
     * @return mixed
     *
     * Создает экземпляр api по его имени (напр., test, testApi или TestApi)
     */
    protected function createApiByName($name)
    {
        if ( strrpos($name, 'Api') === false ) {
            $name .= 'Api';
        }
        $name = ucfirst($name);
        return new $name;
    }


    /**
     *
     * @param $name
     * @throws ApiException
     * @return Api
     *
     * Поиск api по его имени (напр., test, testApi или TestApi)
     */
    public function getApi($name = null)
    {
        if ( $name === null ) {
            $request = $this->getRequest();
            $name = $request->getPathInfo();

            if ( empty($name) ) {
                throw new ApiException('Запрос неверен');
            }
            if ( $name[strlen($name) - 1] === '/' ) {
                $name = substr($name, 0, -1);
            }
        }
        return $this->createApiByName($name);
    }


    /**
     * @param Api $api
     * @param $method
     * @param bool $throwException
     * @throws ApiException
     * @return bool
     *
     * Проверка уровня доступа к методу api
     */
    public function checkAllow(Api $api, $method, $throwException = true)
    {
        switch ( $api->getAllow($method) ) {
            case '?':
                $result = true;
                break;
				
            case '@':
			
                $request = $this->getRequest();
                $token = $request->getQueryParam('token');
			
				if ( $this->checkToken( $token ) )
					$result = true;
				else
					$result = false;
					
                break;
            default:
                $result = false;
        }
        if ( !$result && $throwException ) {
            throw new ApiException("У вас недостаточно прав для выполнения действия '{$method}'");
        }
        // $result = true; //Костыль. Функция мешала мне просто запустить свой модуль даже для теста.
        return $result;
    }

    /**
     * @param Api $api
     * @param $method
     * @param bool $throwException
     * @throws ApiException
     * @return bool
     */
    public function checkVerb(Api $api, $method, $throwException = true)
    {
        $request = $this->getRequest();
        switch ( $api->getVerb($method) ) {
            case 'get':
                $result = $request->isGet;
                break;
            case 'post':
                $result = $request->isPost;
                break;
            default:
                $result = false;
        }
        if ( !$result && $throwException ) {
            throw new ApiException("Метод '{$method} не найден'");
        }
        return $result;
    }


    /**
     * @param Api $api
     * @param bool $throwException
     * @throws ApiException
     * @return bool
     */
    public function checkKey(Api $api, $throwException = true)
    {
        $rules = $api->rules();
        if ( !(isset($rules['check_key']) && $rules['check_key'] === false) ) {
            $request = $this->getRequest();
            $apiId = $api->getId();
			
            if ( md5( $apiId . $this->_secret_key ) !== $request->getQueryParam($this->secretKeyVar) ) {
                if ( $throwException ) {
                    throw new ApiException('Неверный ключ '.md5( $apiId . $this->_secret_key ));
                    // throw new ApiException('Неверный ключ');
                }
                return false;
            }
        }
        return true;
    }


    /**
     * @param Api $api
     * @return mixed
     */
    public function call($api = null)
    {
        $request = $this->getRequest();
        $response = $this->getResponse();

        try {
            if ( !is_object($api) ) {
                $api = $this->getApi($api);
            }

            // проверка безопасности
            if ( !$this->checkKey($api) ) {
                return false;
            }

            // определяю имя метода в апи
            $methodName = $request->getQueryParam('do');
            if ( !$methodName ) {
                $methodName = $request->isGet ? 'get' : 'post';
            }
			
            // проверяю уровень прав 
            if ( !$this->checkAllow($api, $methodName) ) {
                return false;
            }

            // проверяю тип запроса
            if ( !$this->checkVerb($api, $methodName) ) {
                return false;
            }

            // вызываю метод апи
            $params = $request->isGet ? $this->prepareQueryParams() : $request->post();
            $response->setData($api->{$methodName}($params));
            $response->send();
        } catch (Exception $e) {
            $response->clear();
            $response->setError($e);
            $response->send();
            exit();
        }
        return true;
    }


    /**
     * @return array
     *
     * Подготавливаем параметры запроса
     */
    public function prepareQueryParams()
    {
        $request = $this->getRequest();
        $params = $request->getQueryParams();
        unset($params['debug']);
        unset($params['key']);
        unset($params['do']);
        // unset($params['token']);
        return $params;
    }
	
	private function checkToken( $token ) {
	
		$sql = "SELECT * FROM `tokens` WHERE `token` = '" . mysql_escape_string( $token ) . "';";
		
		$res = mysql_query($sql);
		
		if (mysql_error())
			throw new ApiException( 'Ошибка работы с базой данных: ' . mysql_error(), 2 );
			
		if( $res ) {
		
			$item = mysql_fetch_assoc( $res );
			
			return $item[ 'user_id' ];
			
		}
		
		return NULL;
	}
}