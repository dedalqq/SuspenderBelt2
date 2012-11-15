<?php
/**
 * Description of File
 *
 * @author dedal.qq
 * 
 * @property string $name Description
 * @property string $saved_name Description
 * @property string $type Description
 * @property int $user_id Description
 * @property int $group_id Description
 * @property int $size Description
 * @property int $download_num Description
 */
class File extends DataBasePageElement {

    protected $properties = array(
        'name' => self::STRING,
        'saved_name' => self::STRING,
        'type' => self::STRING,
        'user_id' => self::INT,
        'group_id' => self::INT,
        'size' => self::INT,
        'download_num' => self::INT
    );

    private $multiple;
    private $can_edit;
    private $can_use_imagick = array('image/jpeg', 'image/png');
    
    /**
     *
     * @var User
     */
    public $user;

    public function __construct($id = 0, $multiple = true, $can_edit = true) {
        
        $this->multiple = (bool)$multiple;
        $this->can_edit = (bool)$can_edit;
        
        parent::__construct();
        
        //Для работы по аяксу (подгрузка превьюшки после загрузки файла того что загрузилось)
        if (isset($_POST['get_preview']) && isset($_POST['file_id'])) {
            $this->loadById((int)$_POST['file_id']);
            echo $this->rander();
            exit();
        }
        
        $this->values['link'] = $_SERVER['REDIRECT_URL'];
        
        $this->loadById($id);
        
        //$this->UploadFile();
    }
    
    public function afteLoad() {
        $this->user = new User($this->user_id);
        $this->values['file_url'] = $this->getUrl(false, 100);
        $this->values['file_ur_original'] = $this->getUrl(false, 600);
        $this->values['file_date_create'] = Date::format($this->date_create);
        $this->values['file_size'] = App::bytFormat($this->size);
    }
    
    public function getTableName() {
        return 'files';
    }
    
    public function getTplFileName() {
        return 'file';
    }
    
    public function loadById($id = 0) {
        if ($id == 0) {
            return false;
        }
        if ($this->multiple) {
            $this->group_id = $id;
            $this->load('group_id='.$id);
            $this->randerAll();
        }
        else {
            $this->id = $id;
            $this->load();
        }
        return true;
    }

    /**
     * 
     * @param DataBasePageElement $object
     * @param string $fild
     * @return boolean
     */
    public function UploadFile($object = null, $fild = 'file_id') {
        if (!isset($_FILES['file'])) {
            return false;
        }
        
        $file_num = count($_FILES['file']['name']);
        for($i=0; $i<$file_num; $i++) {

            $new_file_name = md5($_FILES['file']['name'][$i].'_'.Date::now());
            
            if (!move_uploaded_file(
                    $_FILES['file']['tmp_name'][$i],
                    $GLOBALS['config']['file_storage'].$new_file_name)
            ) {
                continue;
            }
            
            if (!empty($_POST['group_id'])) {
                $this->group_id = (int)$_POST['group_id'];
                $return_file_id = $this->group_id;
            }
            
            if (!$this->multiple) {
                unlink($GLOBALS['config']['file_storage'].$this->data['name']);
            }
            
            $this->saved_name = $new_file_name;
            $this->name = $_FILES['file']['name'][$i];
            $this->size = $_FILES['file']['size'][$i];
            $this->type = $_FILES['file']['type'][$i];
            $this->date_create = Date::now();
            $this->user_id = Autorisation::i()->getUser()->id;
            
            if ($this->multiple) {
                $this->id = 0;
                $id = $this->save();
            }
            else {
                $return_file_id = $this->save();
                break;
            }
            
            if ($this->multiple && !$this->group_id) {
                $this->group_id = $id;
                $this->save();
                $return_file_id = $this->group_id;
            }
            
            /////////////////
            //$this->reSize(100);
        }
        
        if ($object != null) {
            $object->data[$fild] = $return_file_id;
            $object->save();
        }
        echo "<script>
                window.parent.updateFile(".$return_file_id.", ".$this->values['link'].");
            </script>";
            
        exit();
    }
    
    /**
     * Получить ссылку на файл
     * @param bool $download Если true то по ссылке фаил будет скачиваться
     * @return string ссылка на файл
     */
    public function getUrl($download = false, $preview_size = 0) {
        return '/file/'.($download ? 'download' : 'get').'/'.$this->id
            .($preview_size ? '?preview='.$preview_size : '');
    }
    
    public function getLink($print_size = false, $download = false) {
//        $tpl = Tpl::getInstance();
//        $name = $this->name;
//        if ($print_size) {
//            $name.= ' ('.byt_format($this->size).')';
//        }
//        $tpl->block('link');
//        $tpl->value('url', $this->getUrl($download));
//        $tpl->value('class', 'a_mode_on');
//        $tpl->value('name', $name);
//        
//        return $tpl->echo_tpl('file.html');
    }
    
    public function rander($tpl_name = '') {
        
        $html = '';
        
        if ($this->getCount()) {
            $html.= parent::rander('main');
        }
        
        if ($this->can_edit) {
            $this->values['multiple'] = $this->multiple ? 'multiple' : '';
            $this->randerAll(false);
            $html = parent::rander('form').$html;
        }

        return '<div id="frame">'.$html.'</div>';
    }
    
    public function reSize($height) {
        
        if (!empty($this->data['name']) && in_array($this->type, $this->can_use_imagick)) {
            
            $image = new Imagick($GLOBALS['config']['file_storage'].$this->saved_name);
            $imageprops = $image->getImageGeometry();

            if ($imageprops['height'] > $height) {
                $width = $imageprops['width']/$imageprops['height']*$height;
                $image->resizeImage($width, $height, imagick::FILTER_LANCZOS, 0.9, true);
                
                if ($fe = fopen($GLOBALS['config']['file_storage'].$this->saved_name, 'w')) {
                    fwrite($fp, (string)$image);
                    fclose($fe);
                    return true;
                }
            }
        }
        return false;
    }
    
    public function getStatus() {
        $bloc = new ContentBlock();
        $this->values['count_file'] = $this->getCount();
        $bloc->content = parent::rander('status');
        return $bloc->rander();
    }

    /**
     * Возвращает сам фаил
     * @param type $download
     */
    public function getFileContent($download = false) {
        
        $attach = $download ? ' attachment;' : '';
        
        if (!empty($this->data['name'])) {
       
            header('Content-type: '.$this->name);
            header('Content-Disposition:'.$attach.' filename="'.$this->name.'"');
            
            if (!empty($_GET['preview']) && in_array($this->type, $this->can_use_imagick)) {
                
                $height = (int)$_GET['preview'];
                $image = new Imagick($GLOBALS['config']['file_storage'].$this->saved_name);
                $imageprops = $image->getImageGeometry();
                
                if ($imageprops['height'] > $height) {
                    $width = $imageprops['width']/$imageprops['height']*$height;
                    $image->resizeImage($width, $height, imagick::FILTER_LANCZOS, 0.9, true);
                }
                
                /**
                * @todo помоему strlen это как то кривоватенько 0_о" 
                */
                header('Content-length: '.strlen((string)$image));
                echo $image;
                $this->data['download_num']++;
                $this->save();
                exit();
            }
            elseif ($fe = fopen($GLOBALS['config']['file_storage'].$this->saved_name, 'r')) {
                header('Content-length: '.$this->size);
                while(!feof($fe)) {
                    $content = fgets($fe);
                    echo $content;
                }
                fclose($fe);
                $this->data['download_num']++;
                $this->save();
                exit();
            }
        }
        
        exit();
    }
}

?>
