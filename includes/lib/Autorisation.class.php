<?php

/**
 * Description of Autorisation
 *
 * @author dedal.qq
 * 
 * @property string $user_name
 */
class Autorisation extends PageElement {

    private static $object = NULL;

    /**
     * @var User 
     */
    private $user;
    private $is_login;
    
    protected $properties = array(
        'user_name' => self::STRING
    );

    public function __construct() {
        
        session_start();
        parent::__construct();
        $this->getStatus();
    }
    
    public function getTplFileName() {
        return 'autorisation';
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

        $action = App::$url_request[2];

        if ($action == 'login') {
            $login = htmlspecialchars($_POST['login']);
            $password = md5(htmlspecialchars($_POST['password']));

            $this->user->load("`login`='".$login."' AND `password`='".$password."'");

            if ($this->user->id > 0) {
                $page->info_mass = 'Вы успешно авторизировались!';
                $page->setOk();

                $_SESSION['auth']['user_id'] = $this->user->id;
                
                MainDecorator::i()->addContent($this, 'form_login');
            } else {
                $page->info_mass = 'Неверное сочитание логина и пароля!';
                $page->setError();
            }
        } elseif ($action == 'exit') {
            $_SESSION['auth']['user_id'] = 0;
            $page->info_mass = 'До скорой встречи!';
            $page->setOk();
            MainDecorator::i()->addContent($this, 'form_login');
        }

        $this->getStatus();

       MainDecorator::i()->addContent($page);
        }

    public function getStatus() {
        if (!isset($_SESSION['auth']['user_id'])) {
            $_SESSION['auth']['user_id'] = false;
        }

        if ((bool)$_SESSION['auth']['user_id']) {

            $this->setBlock('auth_on');
            $this->setBlock('auth_off', false);
            
            $this->is_login = true;

            $this->user = new User((int)$_SESSION['auth']['user_id']);

            $this->user_name = $this->user->login;
        } else {
            
            $this->setBlock('auth_on', false);
            $this->setBlock('auth_off');
            $this->is_login = false;
        }
        MainDecorator::i()->addContent($this, 'form_login');
    }

    public function isLogin() {
        if ($this->user instanceof User) {
            return (bool)$this->user->getId();
        } else {
            return false;
        }
    }
}

?>
