<?php
/**
 * Description of TopMenu
 *
 * @author dedalqq
 */
class TopMenu extends PageElement {
    
    protected $properties = array(
        'text' => self::STRING,
    );

    public function getTpl() {
        return 'top_menu';
    }
}

?>
