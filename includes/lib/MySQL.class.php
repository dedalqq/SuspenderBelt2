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
    
    private $link;
    private $db_name;
    private $db_prefix;
    
    private $count_selected = 0;
    
    private $sql_num_query;
    
    private function __construct($login, $password, $host, $db_name, $db_prefix) {
        $this->db_prefix = $db_prefix;
        $this->db_name = $db_name;
        
        $this->link = @mysql_connect($host, $login, $password);
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
            if (!self::$instance->link) {
                self::$instance = false;
            }
        }
        return self::$instance;
    }
    
    /**
     * 
     * @param string $string
     * @return string
     */
    public static function stringHandler($string) {
        return '\''.$string.'\'';
    }
    
    public function getCount() {
        
    }
    
    public function getCountSelected() {
        return $this->count_selected;
    }

    public function char_set($string) {
        if (!$this->link) {
            return false;
        }
        mysql_set_charset($string, $this->link);
    }

    /**
     * @return MySQL
     */
    public static function i() {
        return self::init('', '', '', '', '');
    }

    public function db_select($table_name, $where) {
        
        $query = 'SELECT * FROM `'.$this->db_name.'`.`'.$this->db_prefix.$table_name.'` '
                .'WHERE '.$where;
        $result = mysql_query($query);
        
        if ($result) {
            $this->count_selected = mysql_num_rows($result);
            return $result;
        }
        return false;
    }
    
    /**
     * 
     * @param mysqli_result $result
     */
    public function getDbRow($result) {
        return mysql_fetch_array($result);
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

        mysql_query($query, $this->link);
        
        $this->sql_num_query++;

        return mysql_insert_id($this->link);
    }

    public function db_update($table_name, $data = array(), $where = '') {
        $query = 'UPDATE `'.$this->db_name."`.`".$this->db_prefix.$table_name."` SET ";
        $set_data = array();
        
        foreach ($data as $i => $v) {
            $set_data[] = '`'.$i.'`='.self::stringHandler($v);
        }
        
        $query.= join(',', $set_data).' WHERE '.$where;
        
        mysql_query($query, $this->link);

        $this->sql_num_query++;
        
        return mysql_affected_rows();
    }

}

?>
