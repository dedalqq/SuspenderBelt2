<?php

/**
 * Description of Application
 *
 * @author dedal.qq
 */
class App {

    private static $url_request;

    private static $start_script;
    
    /**
     *
     * @var TopMenu
     */
    public static $top_menu;
    
    /**
     *
     * @var Breadcrumb
     */
    private static $breadcrumb;

    public static function init() {
            
        self::$start_script = microtime(true);
            
        MySQL::init(
                $GLOBALS['db_config']['login'],
                $GLOBALS['db_config']['password'],
                $GLOBALS['db_config']['host'],
                $GLOBALS['db_config']['db_name'],
                $GLOBALS['db_config']['db_prefix']
        );

        MySql::i()->char_set($GLOBALS['config']['encoding']);
        
        Date::init();
        
        MainDecorator::i();

        MainDecorator::init($GLOBALS['config']['tpl_folder']);
        
        self::requestHandler();
        
        Autorisation::i();
        
        self::setTopMenu();
        
        self::$breadcrumb = new Breadcrumb();
    }

    public static function requestHandler() {
        if (isset($_SERVER['REDIRECT_URL'])) {
            self::$url_request = explode('/', $_SERVER['REDIRECT_URL']);
            unset(self::$url_request[0]);
        }
        else {
            self::$url_request = array(1 => 'blogs');
        }
    }
    
    public static function breadcrumb() {
        return self::$breadcrumb;
    }

        public static function getRuningTime() {
        return microtime(true) - self::$start_script;
    }
    
    private static function setTopMenu() {
        self::$top_menu = new TopMenu;
        self::$top_menu->left_itms = array(
            '/blogs' => 'Блог',
            '/ppc' => 'ппц',
            '/my_files' => 'файлы',
            '/profile' => 'Профиль'
        );
        
        MainDecorator::i()->addContent(self::$top_menu, 'top_menu');
    }


    public static function getCurrentCategory($i) {
        if (isset(self::$url_request[(int)$i])) {
            return self::$url_request[(int)$i];
        }
        elseif (count(self::$url_request) == 0) {
            return 'main';
        }
        return '';
    }
    
    public static function getPageId() {
        if (self::$url_request[count(self::$url_request)] != '') {
            return (int)self::$url_request[count(self::$url_request)];
        }
        else {
            return (int)self::$url_request[count(self::$url_request)-1];
        }
    }

    /**
     * 
     * @param type $value
     * @return string
     */
    public static function bytFormat($value) {
        $i = 0;
        $format = array('b', 'Kb', 'Mb', 'Gb');
        while ($value > 1024) {
            $i++;
            $value = $value / 1024;
        }
        return round($value, 2) . ' ' . $format[$i];
    }
    
    /**
     * 
     * @return MainDecorator
     */
    public static function getMainDecorator() {
        return MainDecorator::i();
    }
    
    public static function error($error = '404') {
        $info = new PageInfo();
        $info->setError();
        
        if ($error == '404') {
            $info->page_title = 'Ошибка 404.';
            $info->info_mass = 'Мы приносим вам глубочайшие извинения, но к сожалению данная страница не найдена Т_Т.';
            MainDecorator::i()->addContent($info);
        
            App::getMainDecorator()->rander();
            exit();
        }
        elseif ($error == '401') {
            
        }
        else {
            $info->page_title = 'Ошибка';
            $info->info_mass = $error;
            MainDecorator::i()->addContent($info);
        }
    }

    /**
     * 
     * @param type $parametrs
     * @param type $uri
     * @return string
     */
    public static function raplaceUriParametrs($parametrs = array(), $uri = '') {

        return '';
    }

}

?>
