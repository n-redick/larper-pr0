<?php
class error_pages {
    
    /**
    * (private) Holds the view-class
    * @see _order_handler 
    */
    private $_o_view;
    
    public function __construct() {
        switch($_SESSION['tmp_action']){
            case 'permission' :
                $this->display_page('no_access');
                break;
            case '404' :
                $this->display_page('404');
                break;
            case 'custom_error' :
                $this->display_page('custom');
                break;
            case 'maintenance' :
                $this->display_page('maintenance');
                break;
            default :
                $this->display_page('unknown');
                break;
        }
    }
        
    private function display_page($s_page_name){
        require_once (dirname(__FILE__).'/view/view_error_pages.php');
        $this->_o_view = new view_error_pages();
        echo $this->_o_view->create_output($s_page_name);
    }
}
