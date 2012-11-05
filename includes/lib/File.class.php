<?php
/**
 * Description of File
 *
 * @author dedal.qq
 * 
 * @property-read string $name Description
 * @property-read string $type Description
 * @property-read int $user_id Description
 * @property-read int $group_id Description
 * @property-read int $size Description
 * @property-read int $download_num Description
 */
class File extends DataBasePageElement {

    protected $properties = array(
        'name' => self::STRING,
        'type' => self::STRING,
        'user_id' => self::INT,
        'group_id' => self::INT,
        'size' => self::INT,
        'download_num' => self::INT
    );

    private $multiple;
    private $can_edit;
    
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
        
        $this->loadById($id);
        
        $this->UploadFile();
    }
    
    public function updateComposition() {
        $this->user = new User($this->user_id);
        $this->values['file_url'] = $this->getUrl(false, 120);
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

    private function UploadFile() {
        if (!isset($_FILES['file'])) {
            return false;
        }
        
        $file_num = count($_FILES['file']['name']);
        for($i=0; $i<$file_num; $i++) {

            if (!move_uploaded_file(
                    $_FILES['file']['tmp_name'][$i],
                    $GLOBALS['config']['file_storage'].$_FILES['file']['name'][$i].'_'.Date::now())
            ) {
                continue;
            }
            
            if (!empty($_POST['group_id'])) {
                $this->group_id = (int)$_POST['group_id'];
                $return_file_id = $this->group_id;
            }
            
            if (!$this->multiple) {
                unlink($GLOBALS['config']['file_storage'].$this->data['name'].'_'.$this->data['date_create']);
            }
            
            $this->data['name'] = (string)$_FILES['file']['name'][$i];
            $this->data['size'] = (int)$_FILES['file']['size'][$i];
            $this->data['type'] = (string)$_FILES['file']['type'][$i];
            $this->data['date_create'] = Date::now();
            $this->data['user_id'] = Autorisation::i()->getUser()->id;
            
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
        }
        
        echo "<script>
                window.parent.updateFile(".$return_file_id.");
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
        
        $html = '<div id="frame">';
        
        if ($this->getCount()) {
            $html.= parent::rander();
        }
        
        if ($this->can_edit) {
            $this->values['multiple'] = $this->multiple ? 'multiple' : '';
            $this->randerAll(false);
            $html.= parent::rander('form');
        }
        
        $html.= '</div>';
        
        return $html;
    }
    
    /**
     * Возвращает сам фаил
     * @param type $download
     */
    public function getFileContent($download = false) {
        
        $attach = $download ? ' attachment;' : '';
        
        $typs_for_preview = array('image/jpeg', 'image/png');
        
        if (!empty($this->data['name'])) {
       
            header('Content-type: '.$this->name);
            header('Content-Disposition:'.$attach.' filename="'.$this->name.'"');
            
            if (!empty($_GET['preview']) && in_array($this->type, $typs_for_preview)) {
                
                $height = (int)$_GET['preview'];
                $image = new Imagick($GLOBALS['config']['file_storage'].$this->name.'_'.$this->date_create);
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
            elseif ($fe = fopen($GLOBALS['config']['file_storage'].$this->data['name'].'_'.$this->data['date_create'], 'r')) {
                header('Content-length: '.$this->data['size']);
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
