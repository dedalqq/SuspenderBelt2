<?php
/**
 * Description of breadcrumb
 *
 * @author dedal.qq
 */
class Breadcrumb extends PageElement {
    
    private $elements = array();

    public function add($url, $text) {
        $this->elements[$url] = $text;
    }

    public function getTplFileName() {
        return 'breadcrumb';
    }
    
    public function rander($tpl_name = '') {
        
        $html = '';
        
        foreach ($this->elements as $url => $text) {
            $this->values['url'] = $url;
            $this->values['text'] = $text;
            $html.= parent::rander();
        }
        
        $this->values['contents'] = $html;
        
        return parent::rander('out');
    }
}

?>
