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
    
    protected $properties = array(
        'page_title' => self::STRING,
        'encoding' => self::STRING,
        'menu' => self::STRING,
        'content' => self::STRING,
        'footer' => self::STRING,
        'form_login' => self::STRING,
    );

    public function getTplFileName(){
        return 'index';
    }
    
    public static function init($tpl_folder_name) {
        self::$tpl_folder = $tpl_folder_name;
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
    
    public function addContent($content, $place = 'content') {
        $this->data[$place] = $content;
    }

    public function rander() {

        $this->encoding = $GLOBALS['config']['encoding'];
        
        $top_menu = new TopMenu;
        $this->addContent($top_menu, 'top_menu');
        
        echo parent::rander();
        echo "\n".'<!-- done: ' . App::getRuningTime() . ' -->';
    }

}

?>
