<?php

/**
 * Description of MainDecorator
 *
 * @author t.kovalev
 * 
 * @property string $page_title
 * @property string $encoding
 * @property string $menu
 * @property string $content
 * @property string $footer
 * 
 * @property array $form_login
 * 
 */
class MainDecorator extends PageElement {

    /**
     * текущий шаблон
     * @var type 
     */
    public static $tpl_folder;
    
    /**
     *
     * @var MainDecorator
     */
    private static $object = null;
    
    public $error_mass;
    
    protected $properties = array(
        'page_title' => self::STRING,
        'encoding' => self::STRING,
        'menu' => self::STRING,
        'top_menu' => self::STRING,
        'content' => self::STRING,
        'footer' => self::STRING,
        'form_login' => self::STRING,
    );

    public function __construct() {
        parent::__construct();
        $this->content = '';
    }
    
    public function getTplFileName(){
        return 'index';
    }
    
    public static function init($tpl_folder_name) {
        self::$tpl_folder = $tpl_folder_name;
    }
    
    public function addError($error) {
        $this->error_mass.= $error;
    }

    /**
     * 
     * @return MainDecorator
     */
    public static function i() {
        if (self::$object == null || !self::$object instanceof self) {
            self::$object = new self;
        }
        return self::$object;
    }
    
    public function addContent($content, $place = 'content') {
        if ($place == 'content') {
            $this->$place.= $content;
        }
        else {
            $this->$place = $content;
        }
    }

    public function rander($tpl_name = '') {
        
        header('Content-Type: text/html; charset='.$GLOBALS['config']['encoding']);
        
        $this->encoding = $GLOBALS['config']['encoding'];
        
        $this->content = App::breadcrumb().$this->error_mass.$this->content;
        
        if (isset($_GET['print_only_content'])) {
            $content = array();
            $content['content'] = $this->content;
            echo json_encode($content);
        }
        else {
            echo parent::rander();
            echo "\n".'<!-- done: ' . App::getRuningTime() . ' -->';
        }
    }

}

?>
