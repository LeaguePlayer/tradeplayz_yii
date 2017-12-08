<?php

/**
 * Class Api
 */
abstract class Api
{
    /**
     * @return array
     *
     * [check_key] - проверять ли наличие ключа в запросе
     * [verbs] - правила доступа к апи: @ - только для авторизованных, ? - для всех пользователей
     */
    public function rules()
    {
        return array(
            'check_key' => false,
            'allows' => array(
                array('get', '?'),
                array('post', '@')
            ),
            'verbs' => array(),
        );
    }

    public function getAllow($method)
    {
        if ( !method_exists($this, $method) ) {
            throw new ApiException("Method {$method} does not exists");
        }
        $rules = $this->rules();
        $allows = @$rules['allows'];
        foreach ( $allows as $rule ) {
            foreach (explode(',', $rule[0]) as $name) {
                if ( trim($name) === $method ) {
                    return $rule[1];
                }
            }
        }
        throw new ApiException("You are not allowed to api '{$this->getId()}'");
    }

    public function getVerb($method)
    {
        if ( !method_exists($this, $method) ) {
            throw new ApiException("Method {$method} does not exists");
        }
        $rules = $this->rules();
        $verbs = @$rules['verbs'];
        foreach ( $verbs as $rule ) {
            foreach (explode(',', $rule[0]) as $name) {
                if ( trim($name) === $method ) {
                    return $rule[1];
                }
            }
        }
        return 'get';
    }

    public function getId()
    {
        return lcfirst(substr(get_class($this), 0, -3));
    }

    public function get($params = array())
    {
    }

    public function post($params = array())
    {
    }
}