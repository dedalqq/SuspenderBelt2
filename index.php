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


$user = new User();
$user->load();

do {
    bug($user->login);
    bug($user->date_create);

}
while($user->fetch());
    
//echo $menu;

//MainDecorator::i()->rander();