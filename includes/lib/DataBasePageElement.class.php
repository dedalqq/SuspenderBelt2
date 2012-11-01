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
    
    function getTableName();
    
    public function load() {
        //if ()
    }
    
    
    
}

?>
