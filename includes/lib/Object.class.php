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
    
    /**
     * индекс композиции
     * @var array
     */
    protected $composition_index = array();

    public function __construct() {
        
    }

    public function __set($name, $value) {
        if (isset($this->properties[$name])) {
            if ($this->properties[$name] == self::INT) {
                $this->data[$name] = (int)$value;
            }
            elseif ($this->properties[$name] == self::STRING) {
                $this->data[$name] = (string)$value;
            }
            elseif (false && $this->properties[$name] == self::TYPE_ARRAY) {
                if (is_string($value)) {
                    $this->data[$name] = unserialize($value);
                }
                elseif (is_array($value)) {
                    $this->data[$name] = $value;
                }
                else {
                    $this->data[$name] = array();
                }
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
     * Добовляет объект в композицию
     * @param DataBasePageElement $object
     * @param string $fild
     */
    public function addToIndexComposition($object, $fild) {
        $this->composition_index[$fild] = $object;
    }
    
    /**
     *
     * @param array $data
     * @return bool
     */
    public function setData($data, $from_db = false) {
        if ($from_db) {
            
        }
        else {
            foreach ($this->properties as $name => $type) {
                $this->$name = $data[$name];
            }
        }
        return true;
    }
    
    public function parseHttpRequest() {
        foreach ($this->properties as $i => $v) {
            if (isset($_POST[$i])) {
                $this->$i = $_POST[$i];
            }
        }
    }
    
    public function getData() {
        return $this->data;
    }
}

?>
