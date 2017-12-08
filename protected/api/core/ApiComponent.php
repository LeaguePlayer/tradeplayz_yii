<?php

class ApiComponent
{
    /**
     * @param $name
     * @return mixed
     * @throws ApiException
     */
    public function __get($name)
    {
        $getter = 'get' . $name;
        if (method_exists($this, $getter)) {
            // read property, e.g. getName()
            return $this->$getter();
        }
        throw new ApiException("Method with name {$name} does not exist");
    }


    /**
     * @param $name
     * @param $value
     * @throws ApiException
     */
    public function __set($name, $value)
    {
        $setter = 'set' . $name;
        if (method_exists($this, $setter)) {
            // set property
            $this->$setter($value);
            return;
        }
        if (method_exists($this, 'get' . $name)) {
            throw new ApiException('Setting read-only property: ' . get_class($this) . '::' . $name);
        } else {
            throw new ApiException('Setting unknown property: ' . get_class($this) . '::' . $name);
        }
    }
}