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
elseif (App::getCurrentCategory(1) == 'my_files') {
    
    $file = new File(0, true);
    MainDecorator::i()->addContent($file);
    
}
elseif (App::getCurrentCategory(1) == 'file') {
    
    $file = new File(0, false);
    $file->id = (int)App::getPageId();
    $file->load();
    
    if (App::getCurrentCategory(2) == 'download') {
        $file->getFileContent(true);
    }
    elseif (App::getCurrentCategory(2) == 'get') {
        $file->getFileContent();
    }
}
else {
    app::error();
}

App::getMainDecorator()->rander();

/**
 * @todo сделать получение значение из другого значения
 * @todo сделать вывод всего что найдено в рендере
 */