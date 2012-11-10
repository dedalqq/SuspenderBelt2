<?php

/**
 * Description of DataBaseElement
 *
 * @author t.kovalev
 * 
 * protected $properties = array();
 * 
 * @todo проверить грамотность
 * @property int $id id объекта
 * @property int $date_create Дата создания
 * @property int $date_modif Дата последнего изменения
 */
abstract class DataBasePageElement extends PageElement {
    
    /**
     * @var MySQL
     */
    private $sql;
    
    /**
     *
     * @var mysqli_result
     */
    private $sql_result;
    
    private $sql_fields;


    /**
     * Число элементов которое удалось найти
     * @var int
     */
    private $num_elements = false;
    
    /**
     * Порядковый номер текущего элемента
     * @var int
     */
    private $current_elements = 0;

    protected $rander_all_elements = false;

    public function __construct($id = 0) {
        parent::__construct();
        
        $this->sql = MySQL::i();
        
        $this->properties['id'] = self::INT;
        $this->properties['date_create'] = self::INT;
        $this->properties['date_modif'] = self::INT;
        
        if ($id > 0) {
            $this->id = $id;
        }
        
        if ($this->id != 0) {
            $this->load();
        }
    }
    
    abstract protected function getTableName();
    
    public function randerAll($value = true) {
        $this->rander_all_elements = $value;
    }
    
    public function reset() {
        foreach ($this->properties as $i => $v) {
            $this->data[$i] = null;
        }
    }

    public function load($where = '1') {
        if ($this->id > 0) {
            $where = 't0.id='.$this->id;
        }
        
        foreach ($this->composition_index as $field => $object) {
            $this->sql->setJoiin($object->getTableName(), $field);
        }
        
        list($this->sql_result, $this->sql_fields) = $this->sql->db_select($this->getTableName(), $where);
        $this->num_elements = (int)$this->sql->getCountSelected();
        
        if ($this->num_elements > 0) {
            $this->current_elements = 0;
            $this->fetch();
        }
        return false;
    }
    
    public function afteLoad() {
        
    }

    public function fetch() {
        if ($this->current_elements == $this->num_elements) {
            return false;
        }
        
        $set_data_fields['t0'] = $this;
        $i = 1;
        foreach ($this->composition_index as $field => &$object) {
            $set_data_fields['t'.$i] = $object;
            $i++;
        }
        
        $data = $this->sql->getDbRow($this->sql_result);
        if (!is_array($this->sql_fields)) {
            var_dump($this->sql_fields);
            exit;
        }
        
        foreach ($this->sql_fields as $i => $v) {
            $field = (string)$v->name;
                    
            $set_data_fields[$v->table]->$field = $data[$i];
        }
        $this->current_elements++;
        $this->afteLoad();
        return true;
    }
    
    /**
     * Возвращает число элементов которые удалось загрузить
     * Или false если выборки из базы небыло
     * @return int|false
     */
    public function getCount() {
        return $this->num_elements;
    }

    protected function beforeSave() {
        if ($this->id == 0) {
            $this->date_create = Date::now();
        }
        elseif ($this->id > 0) {
            $this->date_modif = Date::now();
        }
        
        return true;
    }

    public function save() {
        if (!$this->beforeSave()) {
            return false;
        }
        
        if ($this->id == 0) {
            $this->id = $this->sql->db_insert($this->getTableName(), $this->getData());
        }
        elseif ($this->id > 0) {
            $this->sql->db_update($this->getTableName(), $this->getData(), '`id`='.$this->id);
        }
        
        return $this->id;
    }
    
    public function rander($tpl_name = '') {
        $html = parent::rander($tpl_name);
        if ($this->rander_all_elements) {
            while ($this->fetch()) {
                $html.= parent::rander($tpl_name);
            }
        }
        return $html;
    }
}
    
?>
