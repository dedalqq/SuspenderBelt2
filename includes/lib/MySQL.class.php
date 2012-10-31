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
	
	private $db_prefix;


	private function __construct($login, $password, $host, $db_name, $db_prefix) {
        $this->db_prefix = $db_prefix;
		$this->link = mysql_connect($host, $login, $password);
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
        }
        return self::$instance;        
    }
	
	/**
	 * 
	 * @return MySQL
	 */
	public static function i() {
		return self::init('', '', '', '', '');
	}

	public function db_select() {
        
    }
    
    public function db_insert() {
        
    }
    
    public function db_update() {
        
    }
}

?>
