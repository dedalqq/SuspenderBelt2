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

        if ($this->text == '' || $this->title == '') {
            App::error('Нельзя сохранить сообщение без текста или без заголовка =Р');
            return false;
        }
        
        if ($this->id == 0) {
            $this->user_id = Autorisation::i()->getUser()->id;
        }
        elseif ($this->id > 0) {
            $this->modif_by = Autorisation::i()->getUser()->id;
        }
        
        require_once("plugins/nbbc.php");
	
	$input = $this->text;
	
	$bbcode = new BBCode;
        $bbcode->SetDetectURLs(true);
	$output = $bbcode->Parse($input);
        $this->html_text = $output;
        
        return true;
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
            
            $tags = new TagControl();
            $this->values['tag_control'] = $tags;
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
