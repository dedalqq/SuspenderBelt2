<?php
/**
 * Description of Blog
 *
 * @author dedal.qq
 * 
 * @property int $user_id ид пользователя создавшего сообщение
 * @property int $modif_by ид пользователя который последним редактировал сообщение
 * @property int $object тип объекта
 * @property int $object_id ид объекта
 * @property string $text Исходный текст сообщения
 * @property string $html_text Текст сообщения для отображения
 */
class Comment extends DataBasePageElement {
    
    /**
     * @var User
     */
    protected $user;

    protected $properties = array(
        'user_id' => self::INT,
        'modif_by' => self::INT,
        'object' => self::INT,
        'object_id' => self::INT,
        'text' => self::STRING,
        'html_text' => self::STRING
    );
    
    public function __construct($id = 0) {
        $this->user = new User();
        $this->addToIndexComposition($this->user, 'user_id');
        $this->values['link'] = $_SERVER['REDIRECT_URL'];
        parent::__construct($id);
    }
    
    public function afteLoad() {
        
        $this->values['date'] = Date::format($this->date_create);
        
        if (Autorisation::i()->getUser()->id == $this->user_id) {
            $this->setBlock('can_edit');
        }
        else {
            $this->setBlock('can_edit', false);
        }
        
        $this->values['user_status'] = $this->user->getStatus();
    }
    
    public function getUser() {
        return $this->user;
    }

    protected function beforeSave() {
        parent::beforeSave();

        if ($this->text == '') {
            App::error('Нельзя сохранить сообщение без текста =Р');
            return false;
        }
        
        $this->user = Autorisation::i()->getUser();
        
        if ($this->id == 0) {
            $this->user_id = $this->user->id;
        }
        elseif ($this->id > 0) {
            $this->modif_by = $this->user->id;
        }
        
        
        
        /**
         * @todo Добавить парсиншг бб кода
         */
        $this->html_text = $this->text;
        
        return true;
    }

    protected function getTableName() {
        return 'comments';
    }

    public function getTplFileName() {
        return 'comment';
    }
    
    /**
     * 
     * @param string $tpl_name
     * @return string
     */
    public function rander($tpl_name = '') {
        
        if ($this->getCount() === 0) {
            $bloc = new ContentBlock();
            $bloc->content = "Еще никто не прокоментировал это.";
            $bloc->block_id = 'comment_info';
            $bloc->align = 'center';
            return $bloc;
        }
        
        $block = new ContentBlock();
        $block->content = parent::rander($tpl_name);
        $html = $block->rander();
        while ($this->fetch()) {
            $block->content = parent::rander($tpl_name);
            $html.= $block->rander();
        }
        return $html;
    }
}

?>
