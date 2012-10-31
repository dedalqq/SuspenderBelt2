<?php

/**
 * Description of Application
 *
 * @author dedal.qq
 */
class App {

    public static function init() {

        Date::init();
        MainDecorator::i();

        MySQL::init(
                $GLOBALS['db_config']['login'],
                $GLOBALS['db_config']['password'],
                $GLOBALS['db_config']['host'],
                $GLOBALS['db_config']['db_name'],
                $GLOBALS['db_config']['db_prefix']
        );

        MySql::i()->char_set($GLOBALS['config']['encoding']);
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
     * @param type $parametrs
     * @param type $uri
     * @return string
     */
    public static function raplaceUriParametrs($parametrs = array(), $uri = '') {

        return '';
    }

}

?>
