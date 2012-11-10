<?php
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
        if (false) {
            return date('j ', $date)
                    .self::$months[date('n', $date)]
                    .date(' Y H:i', $date);
        }
        else {
            $value = self::$now - $date;
            $s = (int)date('s', $value);
            $i = (int)date('i', $value);
            $h = (int)date('H', $value) - (int)date('Z')/60/60;
            $d = (int)date('j', $value) - 1;
            
            $result = $s.' с. назад';
            
            if ($i) {
                $result = $i.' м. '.$result;
            }
            
            if ($h) {
                $result = $h.' ч. '.$result;
            }
            
            if ($d) {
                $result = $d.' д. '.$result;
            }
            
            return $result;
        }
    }
    
    
}

?>
