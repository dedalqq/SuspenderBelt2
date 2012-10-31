<?php
/**
 * Description of MySQL
 *
 * @author dedalqq
 */
class MySQL {
    
    private static $instance = null;
    
    private function __construct() {
        
    }
    
    public static function i($login, $password, $host, $db_name) {
        if (self::$instance == null && !self::$instance instanceof self) {
            self::$instance = new self;
        }
        return self::$instance;        
    }
    
    public function db_select() {
        
    }
    
    public function db_insert() {
        
    }
    
    public function db_update() {
        
    }
}

?>
