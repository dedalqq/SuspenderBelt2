<?php
/**
 * Description of ChatController
 *
 * @author dedal.qq
 */
class ChatController {
    
    private static $instance;
    
    private function __construct() {
        $this->initChat();
    }
    
    public static function init() {
        if (!self::$instance instanceof self) {
            self::$instance = new self();
        }
    }
    
    private function initChat() {
        $chat = new Chat();
        
        $block = new ContentBlock();
        $block->content = $chat;
        
        MainDecorator::i()->addContent($block);
    }
    
}

?>
