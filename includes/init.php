<?php

function __autoload($class_name) {
    if (file_exists('includes/lib/'.$class_name.'.class.php')) {
        include_once 'includes/lib/'.$class_name.'.class.php';
    }
    elseif (file_exists('includes/components/'.$class_name.'.class.php')) {
        include_once 'includes/components/'.$class_name.'.class.php';
    }
}

include 'functions.php';

ini_set('display_errors', '1');

ini_set('session.gc_maxlifetime', 60*60*24*365);
ini_set('session.cookie_lifetime', 60*60*24*365);

App::init();




//
//MySql::getInstance(
//        $GLOBALS['config']['db_host'],
//        $GLOBALS['config']['db_login'],
//        $GLOBALS['config']['db_password'],
//        $GLOBALS['config']['db_databane'],
//        $GLOBALS['config']['db_prefix']
//        );

//MySql::getInstance()->char_set($GLOBALS['config']['encoding']);

//Autorisation::getInstance();

?>