<?php

/**
 * @author dedal.qq 
 */

include 'includes/init.php';


if (App::getCurrentCategory(1) == 'autorisation') {
    Autorisation::i()->controller();
}


App::getMainDecorator()->rander();

//echo $menu;

//MainDecorator::i()->rander();