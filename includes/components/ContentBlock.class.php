<?php
/**
 * Description of ContentBlock
 *
 * @author dedal.qq
 * 
 * @property string $content
 * @property string $align Выравнивание контента
 * @property string $block_id Ид блока
 */
class ContentBlock extends PageElement {
    
    protected $properties = array(
        'content' => self::STRING,
        'align' => self::STRING,
        'block_id' => self::STRING
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
