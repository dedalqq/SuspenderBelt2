<?php
/**
 * Description of BlogController
 *
 * @author dedal.qq
 */
class BlogController {
    
    /**
     * @var BlogController
     */
    private static $instance;
    
    private function __construct() {
        
        App::breadcrumb()->add('/blogs', 'Блоги');
        
        if (App::getCurrentCategory(2) == '') {
            $this->showBlogList();
        }
        elseif (App::getCurrentCategory(2) == 'add') {
            $this->showForm();
        }
        elseif (App::getCurrentCategory(2) == 'save') {
            $this->saveBlogMass();
        }
        elseif (App::getCurrentCategory(2) == 'edit') {
            $this->showForm(App::getPageId());
        }
        elseif (App::getCurrentCategory(2) == 'show') {
            $this->showBlog(App::getPageId());
        }
    }
    
    public static function init() {
        if (self::$instance == null || !self::$instance instanceof self) {
            self::$instance = new self;
        }
    }
    
    private function showBlogList() {
        $blogs = new Blog();
        $blogs->load();
        if (Autorisation::i()->isLogin()) {
            $button = PageElement::getButton('/blogs/add', 'Добавить');
        }
        else {
            $button = '';
        }
        
        MainDecorator::i()->addContent($button);
        MainDecorator::i()->addContent($blogs);
        MainDecorator::i()->addContent($button);
    }
    
    private function showForm($id = 0) {
        App::error(401);
        
        $blog = new Blog($id);
        $blog->setTplName('blog_form');
        
        MainDecorator::i()->addContent($blog);
    }
    
    private function saveBlogMass() {
        
        App::error(401);
        
        $blog = new Blog();
        $blog->parseHttpRequest();
        
        if ($blog->id == 0) {
            Autorisation::i()->getUser()->num_posts++;
            Autorisation::i()->getUser()->save();
        }
        if ($blog->save()) {
            
            $info = new PageInfo();
            $info->setOk();
            $info->page_title = 'Сообщение сохранено';
            $info->info_mass = 'Поздравляем ^_^ ваше сообщение было успешно сохранено в системе =)';

            MainDecorator::i()->addContent($info);
        }
        else {
            $blog->setTplName('blog_form');
            MainDecorator::i()->addContent($blog);
        }
    }
    
    private function showBlog($id = 0) {
        if ($id == 0) {
            App::error();
        }
        
        $blog = new Blog($id);
        MainDecorator::i()->addContent($blog);
        
        CommentsController::init(CommentsController::BLOG, $id);
    }
}

?>
