<?php

/**
 * Description of Autorisation
 *
 * @author dedal.qq
 * 
 * @property int $id
 * @property string $session_id Description
 * @property int $user_id ид пользователя
 * @property array $ex_data Данные хранимые в сессии
 * @property int $date_login Дата авторизации
 * @property string $ip ип
 * @property string $browser_info Данные браузера
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
            'ex_data' => self::TYPE_ARRAY,
            'date_login' => self::INT,
            'ip' => self::STRING,
            'browser_info' => self::STRING,
        );
        
        $this->user = new User();
        
        $cookie_name = '42qq';
        
        if (empty($_COOKIE[$cookie_name])) {
            $cookie_live = Date::now()+60*60*24+10;
            $cookie_value = md5(Date::now().'_42qq');
            
            setcookie($cookie_name, $cookie_value, $cookie_live, '/');
            $this->session_id = $cookie_value;
            $this->save();
        }
        else {
            $this->session_id = $_COOKIE[$cookie_name];
            $this->load('`session_id`='.MySQL::stringHandler($this->session_id));
            $this->save();
        }
        
        if (isset($_POST['action'])) {
            $this->controller();
        }elseif ($this->user_id > 0) {
            $this->user = new User((int)$this->user_id);
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

    private function controller() {
        $page = new PageInfo;
        $this->user = new User();

        $page->page_title = 'Авторизация';

        $action = htmlspecialchars($_POST['action']);

        if ($action == 'login') {
            $login = htmlspecialchars($_POST['login']);
            $password = md5(htmlspecialchars($_POST['password']));

            $this->user->load("`login`='".$login."' AND `password`='".$password."'");

            if ($this->user->id > 0) {
                $page->info_mass = 'Вы успешно авторизировались!';
                $page->setOk();
                
                $this->user_id = $this->user->id;
                $this->date_login = Date::now();
                $this->browser_info = $_SERVER['HTTP_USER_AGENT'];
                $this->ip = $_SERVER['REMOTE_ADDR'];
                $this->save();
                
                MainDecorator::i()->addContent($this, 'form_login');
            } else {
                $page->info_mass = 'Неверное сочитание логина и пароля!';
                $page->setError();
            }
        }
        elseif ($action == 'exit') {
            
            $this->user->date_last_ping = Date::now()-15;
            $this->user->id = $this->user_id;
            $this->user->save();
            
            $this->user_id = 0;
            $this->save();
            
            $page->info_mass = 'До скорой встречи!';
            $page->setOk();
            MainDecorator::i()->addContent($this, 'form_login');
        }
        elseif ($action == 'ping') {
            
            $this->user = new User((int)$this->user_id);
            
            if ($this->user->id > 0) {
                $this->user->date_last_ping = Date::now();
                $this->user->save();
                
                $data = array();
                /**
                 * @todo тут добавить то, что будет отправлять пользователю
                 */
                echo json_encode($data);
                exit;
            }
            
        }

        $this->getStatus();

       MainDecorator::i()->addContent($page);
        }

    public function getStatus() {
        
        if ((bool)$this->user_id) {

            $this->setBlock('auth_on');
            $this->setBlock('auth_off', false);
            
            $this->is_login = true;
            
        } else {
            
            $this->setBlock('auth_on', false);
            $this->setBlock('auth_off');
            $this->is_login = false;
        }
        //bug($this->loadTpl('main'));
        //bug($this->tpl);
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
