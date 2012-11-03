<?php

/**
 * @author dedal.qq 
 */

include 'includes/init.php';

if (App::getCurrentCategory(1) == 'autorisation') {
    Autorisation::i()->controller();
}
elseif (App::getCurrentCategory(1) == 'main') {
    $str = 'sdkjfg{{qq:ww}}hjsdfgjsd{{ww}}hgfjsfd{{omg:ppc}}gsfdg';
    
    $q = new Blog(3);
    
    //$q->text = 'qqqq';
    //$q->html_text = 'qqq';
    
    //$q->save();
    //$q->setTplName('blog_form');
    MainDecorator::i()->addContent($q);
}
else {
    $info = new PageInfo();
    $info->setError();
    $info->page_title = 'Ошибка 404.';
    $info->info_mass = 'Мы приносим вам глубочайшие извинения, но к сожалению данная страница не найдена Т_Т.';
    MainDecorator::i()->addContent($info);
}



App::getMainDecorator()->rander();