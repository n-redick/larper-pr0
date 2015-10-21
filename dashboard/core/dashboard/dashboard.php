<?php

#################################################
# external-oms // login-handler
#################################################
# handles login, rights updates, 
# and login/logout screen
#################################################  
# @author Ni.Re 
# @copyright Copyright 2013 WIRmachenDRUCK GmbH
# @license commercial 
#################################################
#################################################
# VERSION 0.9 Build 140212
#################################################
# cleaned everything up, added descriptions 
# to every function
# --
# splitted modules and core modules
# --
# preparing for core release
#################################################

class dashboard {

    /**
     * (private) Holds the view-class
     * @see _order_handler 
     */
    private $_o_view;

    /**
     * (public) calls main handler function
     * @param integer $i_error_level
     */
    public function __construct() {
        $this->_dashboard_handler();
    }

    private function _dashboard_handler() {      
        $this->_display_dashboard();
    }

    private function _display_dashboard() {
        library::show_debug("<!-- switch to dashboard view -->");
        require_once (dirname(__FILE__) . '/view/view_dashboard.php');
        $this->_o_view = new view_dashboard();
        echo $this->_o_view->create_output();
    }

}
