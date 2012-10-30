<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Date
 *
 * @author dedal.qq
 */
class Date {
    
    static private $now;
    static private $months;
    
    private function __construct() {
        
    }
    
    static public function init() {
        date_default_timezone_set('Europe/Moscow');
        self::$now = date('U');
        self::$months = array(
            1 => 'янв',
            2 => 'фев',
            3 => 'мар',
            4 => 'апр',
            5 => 'май',
            6 => 'июн',
            7 => 'июл',
            8 => 'авг',
            9 => 'сен',
            10 => 'окт',
            11 => 'ноя',
            12 => 'дек'
        );
    }
    
    static public function now() {
        return self::$now;
    }
    
    static public function format($date) {
        return date('j ', $date)
                .self::$months[date('n', $date)]
                .date(' Y H:i', $date);
    }
}

?>
