<?php
/**
 * Description of MySQL
 *
 * @author dedalqq
 */
class MySQL {

    /**
     *
     * @var MySQL
     */
    private static $instance = null;
    
    private $mysqli;
    private $db_name;
    private $db_prefix;
    
    private $count_selected = 0;
    
    private $sql_num_query;
    
    private $join_array = array();
    
    private function __construct($login, $password, $host, $db_name, $db_prefix) {
        $this->db_prefix = $db_prefix;
        $this->db_name = $db_name;
        
        $this->mysqli = new mysqli($host, $login, $password, $db_name);
    }

    /**
     * 
     * @param type $login
     * @param type $password
     * @param type $host
     * @param type $db_name
     * @return MySQL
     */
    public static function init($login, $password, $host, $db_name, $db_prefix) {
        if (self::$instance == null && !self::$instance instanceof self) {
            self::$instance = new self($login, $password, $host, $db_name, $db_prefix);
            /**
             * @todo реализовать проверку на наличие ошибок
             */
            //if (!self::$instance->link) {
             //   self::$instance = false;
            //}
        }
        return self::$instance;
    }
    
    /**
     * 
     * @param string $string
     * @return string
     */
    public static function stringHandler($value) {
        if (is_array($value)) {
            return '\''.serialize($value).'\'';
        }
        return '\''.addslashes($value).'\'';
    }
    
    /**
     * @todo реализовать
     * Число всего записей после запроса без лимита или всего в таблице
     */
    public function getCount() {
        
    }
    
    /**
     * Число выбраных записей
     * @return int
     */
    public function getCountSelected() {
        return $this->count_selected;
    }

    /**
     * Устанавливает кодировку для работы с базой
     * @param string $string
     * @return boolea
     */
    public function char_set($string) {
        if (!$this->mysqli) {
            return false;
        }
        $this->mysqli->set_charset($string);
    }

    /**
     * @return MySQL
     */
    public static function i() {
        return self::init('', '', '', '', '');
    }
    
    /**
     * Устанавливает добавление в запрос конструкции JOIN LEFT
     * @param string $table_name Имя прикрепляемой таблицы
     * @param string $field Имя поля в основной таблице по которой идет присоединение
     */
    public function setJoiin($table_name, $field) {
        $this->join_array[$table_name] = $field;
    }
    
    public function setLimit() {
        
    }

    public function db_select($table_name, $where) {
        
        $query = 'SELECT * FROM `'.$this->db_prefix.$table_name.'` AS t0'
                .$this->getJoinQuery()
                .' WHERE '.$where
                .$this->getLimitQuery();
        
        $this->join_array = array();
        
        $result = $this->mysqli->query($query);
        
        $this->sql_num_query++;
        
        if ($result) {
            $this->count_selected = $result->num_rows;
            return array($result, $result->fetch_fields());
        }
        return false;
    }
    
    private function getJoinQuery() {
        $join = '';
        $i = 1;
        foreach ($this->join_array as $table => $field) {
            $join.= ' LEFT JOIN `'.$this->db_prefix.$table.'` AS t'.$i
                   .' ON t'.$i.'.`id` = t0.`'.$field.'`';
            $i++;
        }
        return $join;
    }
    
    private function getLimitQuery() {
        $limit = '';
        
        return $limit;
    }

    /**
     * 
     * @param mysqli_result $result
     */
    public function getDbRow($result) {
        if (!$result instanceof mysqli_result) {
            return false;
        }
        return $result->fetch_array();
    }

    function db_insert($table_name, $data = array()) {

        $names = array();
        $values = array();
        
        foreach($data as $i => $v) {
            $names[] = '`'.$i.'`';
            $values[] = self::stringHandler($v);
        }

        $query = 'INSERT INTO `'.$this->db_name.'`.`'.$this->db_prefix.$table_name.'`
                ('.join(',', $names).')
                VALUES
                ('.join(',', $values).');';
        
        $this->mysqli->query($query);
        
        $this->sql_num_query++;

        return $this->mysqli->insert_id;
    }

    public function db_update($table_name, $data = array(), $where = '') {
        $query = 'UPDATE `'.$this->db_name."`.`".$this->db_prefix.$table_name."` SET ";
        $set_data = array();
        
        foreach ($data as $i => $v) {
            $set_data[] = '`'.$i.'`='.self::stringHandler($v);
        }
        
        $query.= join(',', $set_data).' WHERE '.$where;
        
        $this->mysqli->query($query);

        $this->sql_num_query++;
        
        return $this->mysqli->affected_rows;
    }
    
    public function db_delete() {
        
    }
    
    public function __destruct() {
        $this->mysqli->close();
    }

}

?>
