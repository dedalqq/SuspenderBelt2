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
elseif (App::getCurrentCategory(1) == 'profile') {
    Profile::init();
}
elseif (App::getCurrentCategory(1) == 'my_files') {
    
    if (!Autorisation::i()->isLogin()) {
        return false;
    }
    
    $file = new File(0, true);
    $file->load('user_id='.Autorisation::i()->getUser()->id);
    MainDecorator::i()->addContent($file->getStatus());
    $file->randerAll();
    $file->UploadFile();
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
elseif (App::getCurrentCategory(1) == 'tags') {
    TagControl::getTagsList();
}
else {
    app::error();
}

App::getMainDecorator()->rander();

/**
 * @todo сделать получение значение из другого значения
 * @todo сделать вывод всего что найдено в рендере
 */