<?php
require_once (dirname(dirname(dirname(dirname(__FILE__)))).'/lib/parent_classes/lychas_view_core.php');
class view_error_pages extends lychas_view_core {
    
    public function __construct() {
        parent::__construct();
    }
    
    public function create_output($s_page_name){
        $a_variables = array();
        switch($s_page_name){
        case 'unknown': 
            $a_variables['{[tpl_error_header]}'] = 'Ein Unbekannter Fehler ist aufgetreten!';
            $a_variables['{[tpl_error_content]}'] = 'Die IT wurde Informiert';
            $s_page_name = 'free_template';
            break;
        case 'custom':
            $a_variables['{[tpl_error_header]}'] = $_SESSION['custom_error']['header'];
            $a_variables['{[tpl_error_content]}'] = $_SESSION['custom_error']['content'];
            $s_page_name = 'free_template';
            break;
        case 'maintenance':
            $a_variables['{[tpl_error_header]}'] = 'Wartungsarbeiten';
            $a_variables['{[tpl_error_content]}'] = 'Wir führen gerade wartungsarbeiten durch,<br>bitte versuchen sie es später nocheinmal!';
            $s_page_name = 'free_template';
            break;
        }
        return $this->_create_view($s_page_name,$a_variables);
    }
    
}

