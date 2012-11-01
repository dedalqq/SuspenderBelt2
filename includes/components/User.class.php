<?php

/**
 * Description of User
 *
 * @author dedal.qq
 */
class User extends DataBasePageElement {
    
    public $login;
    public $password;
    public $date;
    public $avatar_id;
    public $first_name;
    public $last_name;
    public $mid_name;
    
    //protected $

    public function __construct($id = 0) {
        parent::__construct($id);
        //$this->
    }

    public function getTableName() {
        return 'users';
    }

    public function getTplFileName() {
        return 'user';
    }
    
    
}

?>
