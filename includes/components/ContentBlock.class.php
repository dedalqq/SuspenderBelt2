<?php
/**
 * Description of ContentBlock
 *
 * @author dedal.qq
 * 
 * @property string $content
 * @property string $align Выравнивание контента
 */
class ContentBlock extends PageElement {
    
    protected $properties = array(
        'content' => self::STRING,
        'align' => self::STRING
    );

    public function __construct() {
        parent::__construct();
        $this->content = 'left';
    }
    
    public function getTplFileName() {
        return 'content_block';
    }
}

?>
