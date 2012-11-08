<?php
/**
 * Description of TagControl
 *
 * @author dedal.qq
 * 
 * @property string $name
 * @property int $use_num
 * @property int $date
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
            
            if (self::$object->getCount()) {
                echo self::$object->rander('list');
            }
            exit();
        }
    }
    
    /**
     * @return array
     */
    public static function add() {
        
        $array = $_POST['tag'];
        
        $tag = new self;
        
        for($i=0; $i<count($array); $i++) {
            $array[$i] = htmlspecialchars($array[$i]);
            $tag->reset();
            $tag->load('name='.MySQL::stringHandler($array[$i]));
            if ($tag->id) {
                $tag->data['use_num']++;
                $tag->save();
            }
        }
        
        return $array;
    }
}

?>
