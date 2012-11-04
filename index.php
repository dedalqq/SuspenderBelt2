<?php

/**
 * @author dedal.qq 
 */

include 'includes/init.php';

if (App::getCurrentCategory(1) == 'autorisation') {
    Autorisation::i()->controller();
}
elseif (App::getCurrentCategory(1) == 'blogs') {
    BlogController::init();
}
else {
    app::error404();
}

App::getMainDecorator()->rander();

/**
 * @todo сделать получение значение из другого значения
 * @todo сделать вывод всего что найдено в рендере
 */