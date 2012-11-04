<?php
/**
 * Description of TopMenu
 *
 * @property string $text Description
 * @author dedalqq
 */
class TopMenu extends PageElement {

    public $left_itms = array();
    public $right_itms = array();
    
    protected $properties = array(
        'menu_itm_mod' => self::STRING,
        'menu_itm_text' => self::STRING,
    );
    
    public function getTplFileName() {
        return 'top_menu';
    }
    
    public function rander() {
        
        if (count($this->left_itms)) {
            $this->bloks['menu_itm'] = count($this->left_itms)-1;
        }
        
        $data = each($this->left_itms);
        $this->menu_itm_mod = $data[0];
        $this->menu_itm_text = $data[1];

        $this->loadTpl();

        $html = '';
        //bug($this->tpl);
        for($i=0; $i<count($this->tpl); $i++) {
            if (!is_array($this->tpl[$i])) {
                $html.= $this->tpl[$i];
            }
            else {
                if ($this->tpl[$i][2]) {
                    if (!isset($this->bloks[$this->tpl[$i][0]])) {
                        $i = $this->tpl[$i][1];
                    }
                }
                else {
                    if (isset($this->bloks[$this->tpl[$i][0]]) && $this->bloks[$this->tpl[$i][0]] > 0) {
                        $this->bloks[$this->tpl[$i][0]]--;
                        $i = $this->tpl[$i][1];
                        $data = each($this->left_itms);
                        $this->menu_itm_mod = $data[0];
                        $this->menu_itm_text = $data[1];
                    }
                }
            }
        }
        return (string)$html;
    }
}

?>
