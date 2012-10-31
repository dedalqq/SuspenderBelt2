<?php
/**
 * Description of PageElement
 *
 * @author dedalqq
 */
abstract class PageElement extends Object {
    
    const STRING = 1;
    const INT = 2;

    public static $tpl_folder;
    
    /**
     * Масов строк шаблона
     * @var array
     */
    protected $tpl;
    
    /**
     * Данные
     * @var array
     */
    protected $data;
    
    /**
     * Свойства
     * @var array
     */
    protected $properties;
    
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
     * @return string
     */
    public abstract function getTpl();


    public function __construct() {
        $this->tpl_name = 'main';
    }
    
    /**
     * Установить шаблон
     * @param string $name 
     */
    public function setTplName($name = 'main') {
        $this->tpl_name = $name;
    }

    /**
     *
     * @param array $data
     * @return bool
     */
    public function setData($data) {
        foreach ($this->properties as $name => $type) {
            if ($type == self::INT) {
                $this->data[$name] = intval($data[$name]);
            }
            elseif($type == self::STRING) {
                $this->data[$name] = strval($data[$name]);
            }
        }
        return true;
    }

    /**
     * Загружает шаблон и сохраняет его имя
     * @param string $tpl_name 
     */
    private function loadTpl($tpl_name) {
        
        
        $this->curent_tpl_name = $tpl_name;
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
        /**
         * @todo реализовать всякую хрень по наподнению шаблона и вывода его
         */
        
        return $html;
    }
    
    /**
     *
     * @return string
     */
    public function __toString() {
        return $this->rander();
    }
}

?>
