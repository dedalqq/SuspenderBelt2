<?php

/**
 * Description of PageElement
 *
 * @author dedalqq
 */
abstract class PageElement extends Object {

    /**
     * текущий шаблон
     * @var type 
     */
    private static $tpl_folder;

    /**
     * Масов строк шаблона
     * @var array
     */
    protected $tpl;

    /**
     * Имя шаблона который надо использовать
     * @var type 
     */
    private $tpl_name;

    /**
     * Имя шаблона который удалось загрузить
     * @var string
     */
    private $curent_tpl_name = null;
    
    /**
     * Список отображаемых блоков
     * @var type 
     */
    private $bloks = array();

    /**
     * @return string
     */
    public abstract function getTplFileName();

    public function __construct() {
        $this->tpl_name = 'main';
    }

    public static function init($tpl_folder_name) {
        self::$tpl_folder = $tpl_folder_name;
    }

    /**
     * Установить шаблон
     * @param string $name 
     */
    public function setTplName($name = 'main') {
        $this->tpl_name = $name;
    }

    /**
     * Загружает шаблон и сохраняет его имя
     * @param string $tpl_name 
     */
    private function loadTpl($tpl_name) {
        //bug(self::$tpl_folder.$this->getTplFileName().'.html');

        $fp = fopen(self::$tpl_folder . $this->getTplFileName() . '.html', 'r');

        if (!$fp) {
            return false;
        }
        $this->tpl = array();
        $add = false;
        $pointers = array();
        $lines_list = array();
        while (!feof($fp)) {
            $str = fgets($fp);
            if (preg_match('/<!-- VIEW ([a-z0-9_]*) -->/', $str, $name)) {
                if ($name[1] == $tpl_name) {
                    $add = true;
                } else {
                    if ($add) {
                        break;
                    }
                    $add = false;
                }
                continue;
            }

            if (preg_match('/<!-- BEGIN ([a-z0-9_]*) -->/', $str, $name)) {
                $this->tpl[] = array($name[1], 0);
                $lines_list[$name[1]] = count($this->tpl)-1;
                $pointers[$name[1]] = &$this->tpl[count($this->tpl) - 1][1];
                continue;
            }

            if (preg_match('/<!-- END ([a-z0-9_]*) -->/', $str, $name)) {
                $this->tpl[] = array($name[1], $lines_list[$name[1]]);
                $pointers[$name[1]] = count($this->tpl)-1;
                continue;
            }

            if ($add) {
                
                $vars = array();
                $result = preg_split('/{{[a-z0-9_]*}}/', $str);
                preg_match_all('/{{([a-z0-9_]*)}}/', $str, $vars);

                $this->tpl[] = $result[0];
                
                foreach($vars[1] as $i => $v) {
                    if (isset($this->data[$v])) {
                        $this->tpl[] = &$this->data[$v];
                    }
                    $this->tpl[] = $result[$i+1];
                }
            }
        }
        
        $this->curent_tpl_name = $tpl_name;

        return true;
    }

    /**
     *
     * @return string
     */
    public function rander($tpl_name = '') {
        /**
         * @todo как нить пересмотреть логику
         */
        if ($tpl_name != '') {
            $this->setTplName($tpl_name);
        }
        if ($this->tpl_name != $this->curent_tpl_name) {
            $this->loadTpl($this->tpl_name);
        }
        
        
        $html = '';
        
        for($i=0; $i<count($this->tpl); $i++) {
            if (is_string($this->tpl[$i])) {
                $html.= $this->tpl[$i];
            }
            else {
                if (!isset($this->bloks[$this->tpl[$i][0]])) {
                    $i = $this->tpl[$i][1];
                }
            }
        }
        return (string)$html;
    }
    
    public function setBlock($name, $enable = true) {
        if ($enable) {
            $this->bloks[$name] = true;
        }
        else {
            unset($this->bloks[$name]);
        }
    }

    /**
     *
     * @return string
     */
    public function __toString() {
        return (string)$this->rander();
    }

}

?>
