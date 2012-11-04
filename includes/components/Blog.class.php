<?php
/**
 * Description of Blog
 *
 * @author dedal.qq
 * 
 * @property int $user_id ид пользователя создавшего сообщение
 * @property int $modif_by ид пользователя который последним редактировал сообщение
 * @property string $title Заголовок сообщения
 * @property string $text Исходный текст сообщения
 * @property string $html_text Текст сообщения для отображения
 */
class Blog extends DataBasePageElement {
    
    /**
     *
     * @var User
     */
    protected $user;

    protected $properties = array(
        'user_id' => self::INT,
        'modif_by' => self::INT,
        'title' => self::STRING,
        'text' => self::STRING,
        'html_text' => self::STRING
    );
    
    public function __construct($id = 0) {
        $this->user = new User();
        parent::__construct($id);
    }
    
    public function updateComposition() {
        $this->user->id = $this->user_id;
        $this->user->load();
        if (Autorisation::i()->getUser()->id == $this->user_id) {
            $this->setBlock('can_edit');
        }
        else {
            $this->setBlock('can_edit', false);
        }
    }
    
    public function getUser() {
        return $this->user;
    }

    protected function beforeSave() {
        parent::beforeSave();
        
        if ($this->id == 0) {
            $this->user_id = Autorisation::i()->getUser()->id;
        }
        elseif ($this->id > 0) {
            $this->modif_by = Autorisation::i()->getUser()->id;
        }
        
        
        
        $this->html_text = $this->text;
    }

    protected function getTableName() {
        return 'blogs';
    }

    public function getTplFileName() {
        return 'blog';
    }
    
    public function rander($tpl_name = '') {
        if ($this->tpl_name == 'blog_form') {
            if ($this->id == 0) {
                $this->values['editor_mod'] = 'Создать новое сообщение';
            }
            else {
                $this->values['editor_mod'] = 'Редактировать сообщение';
            }
        }
        
        $block = new ContentBlock();
        
        $this->data['date_create'] = Date::format($this->date_create);
        $block->content = parent::rander();
        
        $html = (string)$block;
        
        while ($this->fetch()) {
            $this->data['date_create'] = Date::format($this->date_create);
            $block->content = parent::rander();
            $html.= $block;
        }
        
        return $html;
    }
}

?>
