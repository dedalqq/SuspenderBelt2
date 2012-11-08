<?php

/**
 * Description of Object
 *
 * @author dedalqq
 */
abstract class Object {

    const STRING = 1;
    const INT = 2;
    const TYPE_ARRAY = 3;

    /**
     * Данные
     * @var array
     */
    public $data = array();

    /**
     * Свойства
     * @var array
     */
    protected $properties = array();

    public function __construct() {
        
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
            } elseif ($type == self::TYPE_ARRAY) {
                if (is_array($data[$name])) {
                    $this->data[$name] = $data[$name];
                } elseif (is_array($array = unserialize($data[$name]))) {
                    $this->data[$name] = $array;
                } else {
                    $this->data[$name] = array();
                }
            }
        }
        return true;
    }
    
    public function getData() {
        return $this->data;
    }
}

?>
