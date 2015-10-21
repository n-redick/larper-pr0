<?php
class error_handler{
    
    private $s_error_file   = FALSE;
    private $s_mail_sender  = 'script_error@wir-machen-druck.de';
    private $s_mail_target  = FALSE;
    
    public function __construct($s_mail_target,$s_error_file = FALSE) {
        if($s_error_file != FALSE){
            $this->s_error_file = $s_error_file;
        }
        if($s_mail_target != FALSE && $s_mail_target != ''){
            $this->s_mail_target = $s_mail_target;
        }
    }
    
    public function error(){
        
    }
    
    private function send_mail(){
        
    }
    
    private function write_log($s_error_text,$s_error_file = FALSE){
        $handle = fopen("./css/cache/".$file_name.".php", "w");
        fwrite($handle,$sjson);
        touch("./css/cache/".$file_name.".php");
    }
    
}
?>
