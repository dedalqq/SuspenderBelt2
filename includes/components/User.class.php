<?php

/**
 * Description of User
 *
 * @author dedal.qq
 * 
 * @property string $login
 * @property string $password
 * @property int $avatar_id
 * @property string $first_name
 * @property string $last_name
 * @property string $mid_name
 * 
 * @property int $num_comments Description
 * @property int $num_posts Description
 * 
 * @property int $date_last_ping Description
 * 
 */
class User extends DataBasePageElement {
    
    protected $properties = array(
        'login' => self::STRING,
        'password' => self::STRING,
        'avatar_id' => self::INT,
        'first_name' => self::STRING,
        'last_name' => self::STRING,
        'mid_name' => self::STRING,
        'num_comments' => self::INT,
        'num_posts' => self::INT,
        'date_last_ping' => self::INT
    );

    public function __construct($id = 0) {
        parent::__construct($id);
    }

    public function getTableName() {
        return 'users';
    }

    public function getTplFileName() {
        return 'user';
    }
    
    public function getStatus() {
        if (Date::now() - $this->date_last_ping < 14) {
            return '<span class="green">On line</span>';
        }
        else {
            return '<span class="red">Off line</span>';
        }
    }
}

?>
