<?php
/**
 * Description of TopMenu
 *
 * @property string $text Description
 * @author dedalqq
 */
class TopMenu extends PageElement {
    
    protected $properties = array(
        'text' => self::STRING,
        'name' => self::STRING,
    );

    public function getTplFileName() {
        return 'top_menu';
    }
}

?>
