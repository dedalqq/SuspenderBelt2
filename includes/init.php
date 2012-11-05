<?php

function __autoload($class_name) {
    if (file_exists('includes/lib/'.$class_name.'.class.php')) {
        include_once 'includes/lib/'.$class_name.'.class.php';
    }
    elseif (file_exists('includes/components/'.$class_name.'.class.php')) {
        include_once 'includes/components/'.$class_name.'.class.php';
    }
}

include 'config.php';
include 'functions.php';

include 'lib/Exception.php';
//set_error_handler(create_function('$c, $m, $f, $l', 'new MyException($m, $c, $f, $l);'), E_ALL);

ini_set('display_errors', '1');

ini_set('session.gc_maxlifetime', 60*60*24*365);
ini_set('session.cookie_lifetime', 60*60*24*365);

App::init();

?>