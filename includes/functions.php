<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
function bug($var, $stop = false) {
    
    if ($stop) {
        echo '<pre>';
        var_dump($var);
        echo '</pre>';
        //exit;
    } else {
        $info = new PageInfo();
        $info->page_title = 'Информация для дебага';
        $info->info_mass = '<p><pre>' . htmlspecialchars(print_r($var, true)) . '</pre></p>';
        MainDecorator::i()->addError($info);
    }
}

?>
