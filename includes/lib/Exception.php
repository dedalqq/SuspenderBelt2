<?php
/**
 * Description of Exception
 *
 * @author dedal.qq
 */
class MyException {
   public function __construct($message, $errorLevel = 0, $errorFile = '', $errorLine = 0) {
       
       $mass = new PageInfo();
       
       if ($errorLevel == E_NOTICE) {
           $mass->page_title = 'Замечание';
           $mass->info_mass = '<b>Фаил</b>: '.$errorFile.'<br>'
                             .'<b>Строка</b>: '.$errorLine.'<br><br>'
                             .'<b>Сообщение</b><p style="padding-left: 20px;">'.$message.'</p>';
       }
       elseif ($errorLevel == E_WARNING) {
           $mass->page_title = 'Предупреждение';
           $mass->info_mass = '<b>Фаил</b>: '.$errorFile.'<br>'
                             .'<b>Строка</b>: '.$errorLine.'<br><br>'
                             .'<b>Сообщение</b><p style="padding-left: 20px;">'.$message.'</p>';
       }
       else {
           $mass->page_title = 'Внимание';
           $mass->info_mass = '<b>Фаил</b>: '.$errorFile.'<br>'
                             .'<b>Строка</b>: '.$errorLine.'<br><br>'
                             .'<b>Сообщение</b><p style="padding-left: 20px;">'.$message.'</p>';
       }
       
       MainDecorator::i()->addError($mass);
   }
}

?>
