<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

function bug($var, $stop = false) {
    echo '<pre>';
    var_dump($var);
    echo '</pre>';
    if ($stop) exit;
}

?>
