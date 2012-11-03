<?php
/**
 * Description of ContentBlock
 *
 * @author dedal.qq
 * 
 * @property string $content
 */
class ContentBlock extends PageElement {
    
    protected $properties = array(
        'content' => self::STRING
    );

    public function getTplFileName() {
        return 'content_block';
    }
}

?>
