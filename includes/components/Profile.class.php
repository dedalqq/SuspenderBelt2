<?php
/**
 * Description of Profile
 *
 * @author dedal.qq
 */
class Profile extends PageElement {
    
    /**
     * @var Profile
     */
    private static $instance;
    
    public function __construct() {
        App::error(401);
        
        App::breadcrumb()->add('/profile', 'Профиль');
        $this->show();
    }
    
    public static function init() {
        if (self::$instance == null || !self::$instance instanceof self) {
            self::$instance = new self;
        }
    }
    
    private function show() {
        $user = Autorisation::i()->getUser();
        
        $file = new File($user->avatar_id, false, true);
        
        $file->UploadFile($user, 'avatar_id');
        
        $this->values['avatar'] = $file;
        
        MainDecorator::i()->addContent($this->rander('main'));
    }

    public function getTplFileName() {
        return 'user';
    }
}

?>
