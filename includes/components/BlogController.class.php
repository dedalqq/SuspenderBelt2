<?php
/**
 * Description of BlogController
 *
 * @author dedal.qq
 */
class BlogController {
    
    /**
     * @var BlogController
     */
    private static $instance;
    
    private function __construct() {
        if (App::getCurrentCategory(2) == '') {
            $this->showBlogList();
        }
    }
    
    public static function init() {
        if (self::$instance == null || !self::$instance instanceof self) {
            self::$instance = new self;
        }
    }
    
    private function showBlogList() {
        
    }
}

?>
