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
 * @property array $tags Список тэгов
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
        'tags' => self::TYPE_ARRAY,
        'html_text' => self::STRING
    );
    
    public function __construct($id = 0) {
        
        $this->user = new User();
        $this->addToIndexComposition($this->user, 'user_id');
        
        parent::__construct($id);
    }
    
    public function afteLoad() {
        if (Autorisation::i()->getUser()->id == $this->user_id) {
            $this->setBlock('can_edit');
        }
        else {
            $this->setBlock('can_edit', false);
        }
        $this->values['date'] = Date::format($this->date_create);
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
        
        $this->tags = TagControl::add();
        
        return true;
    }

    protected function getTableName() {
        return 'blogs';
    }

    public function getTplFileName() {
        return 'blog';
    }
    
    public function rander($tpl_name = '') {
        
        if ($this->getCount() === 0) {
            $bloc = new ContentBlock();
            $bloc->content = "Еще ни одна сущность не отписалась тут.";
            $bloc->align = 'center';
            return $bloc;
        }
        
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
        
        $html = '';
        do {
            $block->content = parent::rander($tpl_name);
            $html.= (string)$block;
        }
        while ($this->fetch());
        
        return $html;
    }
}

?>
