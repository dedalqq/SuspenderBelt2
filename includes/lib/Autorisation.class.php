<?php

/**
 * Description of Autorisation
 *
 * @author dedal.qq
 * 
 * @property int $id
 * @property string $session_id Description
 * @property int $user_id ид пользователя
 * @property array $data Данные хранимые в сессии
 * @property int $date_login Дата авторизации
 * @property int $date_last_ping Дата последнего отклика от пользователя
 * @property int $date_ping хз =) не помню =)
 * 
 */
class Autorisation extends DataBasePageElement {

    private static $object = NULL;

    /**
     * @var User 
     */
    public $user;
    private $is_login;

    public function __construct() {
        
        parent::__construct(0);
        
        $this->properties = array(
            'id' => self::INT,
            'session_id' => self::STRING,
            'user_id' => self::INT,
            'data' => self::TYPE_ARRAY,
            'date_login' => self::INT,
            'date_last_ping' => self::INT,
            'date_ping' => self::INT
        );
        
        $this->user = new User();
        
        $cookie_name = '42qq';
        $cookie_value = md5(Date::now().'_42qq');
        $cookie_live = Date::now()+60*60*24+10;
        
        if (empty($_COOKIE[$cookie_name])) {
            setcookie($cookie_name, $cookie_value, $cookie_live, '/');
            $this->session_id = $cookie_value;
        }
        else {
            $this->session_id = $_COOKIE[$cookie_name];
            $this->load('`session_id`='.MySQL::stringHandler($this->session_id));
        }
        
        $this->getStatus();
    }
    
    public function getTplFileName() {
        return 'autorisation';
    }
    
    protected function getTableName() {
        return 'session';
    }

    public function getUser() {
        if (!$this->user instanceof User) {
            $this->user = new User;
        }
        return $this->user;
    }

    /**
     * @return Autorisation
     */
    static public function i() {
        if (!(self::$object != NULL && self::$object instanceof self)) {
            self::$object = new self;
        }
        return self::$object;
    }

    public function controller() {
        $page = new PageInfo;
        $this->user = new User();

        $page->page_title = 'Авторизация';

        $action = App::getCurrentCategory(2);

        if ($action == 'login') {
            $login = htmlspecialchars($_POST['login']);
            $password = md5(htmlspecialchars($_POST['password']));

            $this->user->load("`login`='".$login."' AND `password`='".$password."'");

            if ($this->user->id > 0) {
                $page->info_mass = 'Вы успешно авторизировались!';
                $page->setOk();
                
                $this->user_id = $this->user->id;
                $this->date_login = Date::now();
                $this->save();
                
                MainDecorator::i()->addContent($this, 'form_login');
            } else {
                $page->info_mass = 'Неверное сочитание логина и пароля!';
                $page->setError();
            }
        } elseif ($action == 'exit') {
            
            $this->user_id = 0;
            $this->save();
            
            $page->info_mass = 'До скорой встречи!';
            $page->setOk();
            MainDecorator::i()->addContent($this, 'form_login');
        }

        $this->getStatus();

       MainDecorator::i()->addContent($page);
        }

    public function getStatus() {
        
        if ((bool)$this->user_id) {

            $this->setBlock('auth_on');
            $this->setBlock('auth_off', false);
            
            $this->is_login = true;

            $this->user = new User((int)$this->user_id);
            
        } else {
            
            $this->setBlock('auth_on', false);
            $this->setBlock('auth_off');
            $this->is_login = false;
        }
        MainDecorator::i()->addContent($this, 'form_login');
    }

    public function isLogin() {
        if ($this->user instanceof User) {
            return (bool)$this->user->id;
        } else {
            return false;
        }
    }
}

?>
