<?php
/**
 * Description of PageInfo
 *
 * @author dedal.qq
 * 
 * @property string $page_title Заголовок сообщения
 * @property int $mass_type Иконка сообщения
 * @property string $info_mass Информационное сообщение
 */
class PageInfo extends PageElement {
    
    //ok|not_ok|warning|error
    const OK = 1;
    const NOT_OK = 2;
    const WARNING = 3;
    const ERROR = 4;
    
    /**
     *
     * @var ContentBlock
     */
    private $content_block;

    public function __construct() {
        parent::__construct();
        $this->setWarning();
        $this->content_block = new ContentBlock();
    }
    
    protected $properties = array(
        'page_title' => self::STRING,
        'mass_type' => self::INT,
        'info_mass' => self::STRING
    );

    public function getTplFileName() {
        return 'page_info';
    }
    
    public function setOk() {
        $this->mass_type = self::OK;
    }
    
    public function setNotOk() {
        $this->mass_type = self::NOT_OK;
    }
    
    public function setWarning() {
        $this->mass_type = self::WARNING;
    }
    
    public function setError() {
        $this->mass_type = self::ERROR;
    }
    
    public function rander($tpl_name = '') {
        if ($this->page_title) {
            $this->setBlock('page_title');
        }
        if ($this->info_mass) {
            $this->setBlock('info_mass');
        }
        $this->content_block->content = parent::rander($tpl_name);
        return $this->content_block->rander();
    }
}

?>
