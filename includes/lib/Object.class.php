<?php

/**
 * Description of Object
 *
 * @author dedalqq
 */
abstract class Object {

    const STRING = 1;
    const INT = 2;

    /**
     * Данные
     * @var array
     */
    protected $data;

    /**
     * Свойства
     * @var array
     */
    protected $properties;

    public function __construct() {
        $this->data = array();
        $this->properties = array();
    }

    public function __set($name, $value) {
        if (isset($this->properties[$name])) {
            if ($this->properties[$name] == self::INT) {
                $this->data[$name] = (int) $value;
            } elseif ($this->properties[$name] == self::STRING) {
                $this->data[$name] = (string) $value;
            }
        }
    }

    public function __get($name) {
        if (isset($this->data[$name])) {
            return $this->data[$name];
        }
        return null;
    }
    
    /**
     *
     * @param array $data
     * @return bool
     */
    public function setData($data) {
        foreach ($this->properties as $name => $type) {
            if ($type == self::INT) {
                $this->data[$name] = intval($data[$name]);
            } elseif ($type == self::STRING) {
                $this->data[$name] = strval($data[$name]);
            }
        }
        return true;
    }
}

?>
