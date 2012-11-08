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
    
    /**
     * Число элементов которое удалось найти
     * @var int
     */
    private $num_elements = 0;
    
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

    public function load($where = '1') {
        if ($this->id > 0) {
            $where = '`id`='.$this->id;
        }
        
        $this->sql_result = $this->sql->db_select($this->getTableName(), $where);
        $this->num_elements = $this->sql->getCountSelected();
        
        if ($this->num_elements > 0) {
            $this->current_elements = 0;
            $this->fetch();
        }
        return false;
    }
    
    public function updateComposition() {
        
    }

    public function fetch() {
        if ($this->current_elements == $this->num_elements) {
            return false;
        }
        $this->setData($this->sql->getDbRow($this->sql_result));
        $this->updateComposition();
        $this->current_elements++;
        return true;
    }
    
    /**
     * Возвращает число элементов которые удалось загрузить
     * @return int
     */
    public function getCount() {
        return (int)$this->num_elements;
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
