<?php
/**
 * Description of TagControl
 *
 * @author dedal.qq
 */
class TagControl extends DataBasePageElement {
    
    /**
     *
     * @var TagControl
     */
    private static $object = null;

    public function __construct() {
        parent::__construct();
        $this->properties = array(
            'id' => self::INT,
            'name' => self::STRING,
            'use_num' => self::INT,
            'date' => self::INT,
        );
    }

    public function getTplFileName() {
        return 'tags';
    }

    protected function getTableName() {
        return 'tags';
    }
    
    public static function getTagsList() {
        if (!self::$object instanceof self) {
            self::$object = new self();
            
            self::$object->load('name LIKE '.MySQL::stringHandler($_POST['tag'].'%'));
            self::$object->randerAll();
            header('Content-Type: text/html; charset='.$GLOBALS['config']['encoding']);
            
            echo self::$object->rander('list');
            exit();
        }
    }
}

?>
