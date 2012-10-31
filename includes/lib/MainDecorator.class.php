<?php
/**
 * Description of MainDecorator
 *
 * @author t.kovalev
 */
class MainDecorator {
	
	/**
	 *
	 * @var MainDecorator
	 */
	private static $object = null;

	private function __construct() {
		
	}
	
	/**
	 * 
	 * @return MainDecorator
	 */
	public static function i() {
		if (self::$object == null && !self::$object instanceof self) {
			self::$object = new self;
		}
		return self::$object;
	}
	
	
	
	
	
	
	public function rander() {
		
	}
}

?>
