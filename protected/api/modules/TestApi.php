<?php
/**
 * Class TestApi
 *
 * Формат запроса к апи http://rentor.pro/api/<api_id>?do=<method_name>&param1=some_param&param2=some_param
 *
 * @example http://rentor.pro/api/test -
 * будет вызвана стандартная заглушка для всех api-классов get() или post() @see Api
 *
 * @example http://rentor.pro/api/test?do=mymethod
 * будет выван метод mymethod без парметров
 *
 * @example http://rentor.pro/api/test?do=mymethod&param_one=1&param_two=2
 * будет выван метод mymethod c параметрами
 *
 * @example http://rentor.pro/api/test?do=undefuned
 * будет выброшено исключение
 */
class TestApi extends Api
{
    /**
     * @return array Правила проверки.
     *
     * [check_key] - проверять ли наличие секретного ключа в запросе.
     * Правило формирования ключа: md5($apiId . secret_key), где
     * $apiId - идентификатор api (в данном случае он равен «test»), @see Api
     * $secret_key - ключ, известный только сереру и клиенту, @see ApiEngine
     * Провекрка реализована в классае @see ApiEngine, метод checkKey()
     *
     * [allows] - правила доступа к методам.
     * Первым параметром передаются имена метода, разделенных запятой,
     * вторым парметром - уровень доступа: '?' - для всех пользователей, '@' - только для авторизованных.
     * Провекрка реализована в классае @see ApiEngine, метод checkAllow()
     *
     * [verbs] - разграничение методов согласно REST.
     * Первым параметром передаются имена метода, разделенных запятой,
     * вторым парметром - правило:'get' для GET-запросов, 'post' - для POST-запросов
     * Провекрка реализована в классае @see ApiEngine, метод checkVerb()
     *
     */
    public function rules()
    {
        return array(
            'check_key' => true,
            'allows' => array(
                array('mymethod, get, post', '@'),
                array('postmethod', '@'),
            ),
            'verbs' => array(
                array('mymethod', 'get'),
                array('postmethod', 'post'),
            ),
        );
    }

    /**
     * @param array $params
     * Зарезервированные слова, которые нельзя использовать в качестве переменных для $params:
     * do, token, debug, format
     *
     * @return array
     */
    function mymethod($params = array())
    {
        return $params;
    }

    function postmethod($postData = array())
    {
        return $postData;
    }
}