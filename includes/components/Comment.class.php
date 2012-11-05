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

        if ($this->text == '') {
            App::error('Нельзя сохранить сообщение без текста =Р');
            return false;
        }
        
        if ($this->id == 0) {
            $this->user_id = Autorisation::i()->getUser()->id;
        }
        elseif ($this->id > 0) {
            $this->modif_by = Autorisation::i()->getUser()->id;
        }
        
        $this->html_text = $this->text;
        
        return true;
    }

    protected function getTableName() {
        return 'comments';
    }

    public function getTplFileName() {
        return 'comment';
    }
    
}

?>
