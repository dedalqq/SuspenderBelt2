<?php

/**
 * Description of DataBaseElement
 *
 * @author t.kovalev
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
    
    private $num_elements = 0;
    private $current_elements = 0;

    public function __construct($id = 0) {
        parent::__construct();
        
        $this->sql = MySQL::i();
        
        $this->properties['id'] = self::INT;
        $this->properties['date_create'] = self::INT;
        $this->properties['date_modif'] = self::INT;
        
        $this->id = $id;
        
        if ($this->id != 0) {
            $this->load();
        }
    }
    
    abstract protected function getTableName();
    
    public function load($where = '1') {
        if ($this->id > 0) {
            $where = '`id`='.$this->id;
        }
        
        $this->sql_result = $this->sql->db_select($this->getTableName(), $where);
        $this->num_elements = $this->sql->getCountSelected();
        
        if ($this->num_elements > 0) {
            $this->fetch();
        }
        return false;
    }
    
    public function fetch() {
        if ($this->current_elements == $this->num_elements) {
            return false;
        }
        $this->setData($this->sql->getDbRow($this->sql_result));
        $this->current_elements++;
        return true;
    }

    public function save() {
        if ($this->id == 0) {
            $this->date_create = Date::now();
            $this->sql->db_insert($this->getTableName(), $this->getData());
        }
        elseif ($this->id > 0) {
            $this->date_modif = Date::now();
            $this->sql->db_update($this->getTableName(), $this->getData(), '`id`='.$this->id);
        }
    }
    
}

?>