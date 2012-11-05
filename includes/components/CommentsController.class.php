<?php
/**
 * Description of CommentsController
 *
 * @author dedal.qq
 */
class CommentsController {
    
    /**
     * @var CommentsController
     */
    private static $instance;
    
    const BLOG = 1;
    
    private $object = 0;
    private $object_id = 0;

    public function __construct($object, $id) {
        $this->object = $object;
        $this->object_id = $id;
        $this->show();
    }

    public static function init($object, $id) {
        if (self::$instance == null || !self::$instance instanceof self) {
            self::$instance = new self($object, $id);
        }
    }
    
    private function show() {
        
        if (isset($_POST['save'])) {
            $comment = new Comment();
            $comment->parseHttpRequest();
            $comment->save();
        }
        
        $comment = new Comment();
        $comment->load('object_id='.$this->object_id.' AND object='.$this->object);
        $comment->randerAll();
        MainDecorator::i()->addContent($comment);
        
        
        $form = new Comment();
        $form->object = $this->object;
        $form->object_id = $this->object_id;
        $form->setTplName('form');
        MainDecorator::i()->addContent($form);
    }
}

?>
