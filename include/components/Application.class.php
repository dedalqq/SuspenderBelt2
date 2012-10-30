<?php
/**
 * Description of Application
 *
 * @author dedal.qq
 */
class App {
    
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
            $value = $value/1024;
        }
        return round($value, 2).' '.$format[$i];
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
