<?php

/**
 * @author dedal.qq 
 */

include 'includes/init.php';

$menu = new TopMenu();

$menu->text = 'omg';
$menu->name = 'qqq';

$menu->setBlock('qq1_qqww23_2');
$menu->setBlock('qq1_qqww23_2', false);


echo $menu;

//MainDecorator::i()->rander();