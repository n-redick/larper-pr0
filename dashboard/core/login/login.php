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

/**
 * main class of this file
 * handles login/logout, 
 * creating of permission-session
 * refresh of permissions 
 *
 * @see wmd_core
 * @author Ni.Re 
 * @copyright Copyright 2013 WIRmachenDRUCK GmbH
 * @license commercial
 */
class login {

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
        $this->_login_handler();
    }

    /**
     * (private)
     * depending on the action get things in motion
     */
    private function _login_handler() {
        $s_action = NULL;
        if (isset($_POST['login'])) {
            $s_action = 'login';
        }
        if (isset($_GET['logout'])) {
            $s_action = 'logout';
        }
        if (isset($_GET['register'])) {
            $s_action = 'register';
        }
        // check if $_GET['a'] is not empty, else set $s_action = NULL
        switch ($s_action) {
            case 'login' :
                // calls login function
                if ($this->_login($_POST['username'], $_POST['password'])) {
                    //makes a header redirect, and exits;
                    library::redirect();
                }
                // something went wrong (like wrong password, 
                // or not all fields where filled)
                // so we have do display the login form again
                $this->display_login();
                break;
            case 'logout' :
                // simple stuff, delete/destroy session
                // display login form
                unset($_SESSION);
                session_destroy();
                library::redirect();
                break;
            case 'register' :
                if (isset($_POST['register'])) {
                    if($this->_register()){
                        library::redirect();
                    }
                }
                $this->display_register();
                break;
            default :
                $this->display_login();
        }
    }

    /**
     * (private)
     * load view file, and init the class
     * then echo the output
     */
    private function display_login() {
        library::show_debug("<!-- switch to login view -->");
        require_once (dirname(__FILE__) . '/view/view_login.php');
        $this->_o_view = new view_login();
        echo $this->_o_view->create_output();
    }

    /**
     * (private)
     * load view file, and init the class
     * then echo the output
     */
    private function display_register() {
        library::show_debug("<!-- switch to register view -->");
        require_once (dirname(__FILE__) . '/view/view_register.php');
        $this->_o_view = new view_register();
        echo $this->_o_view->create_output();
    }

    /**
     * (private)
     * 
     * checks if password and username are right,
     * and grants the permissions the user 
     * is allowed to have
     * 
     * @param string $s_username Username
     * @param string $s_password Passwort
     * @return boolean
     */
    private function _login($s_username, $s_password) {
        // are both variables filled?
        if (empty($s_username) || empty($s_password)) {
            $_SESSION['login_error'] = 'Leerer Benutzername oder leeres Passwort!';
            return false;
        } else {
            $o_login = library::get_library('login');
            return $o_login->login($s_username, $s_password);
        }
    }

    /**
     * (private)
     * 
     * checks if password and username are right,
     * and grants the permissions the user 
     * is allowed to have
     * 
     * @param string $s_username Username
     * @param string $s_password Passwort
     * @return boolean
     */
    private function _register() {
        if (!$this->_check_input()) {
            return false;
        } else {
            $o_login = library::get_library('login');
            $o_login->register($_POST['username'], $_POST['password'],$_POST['email']);
            return true;
        }
    }

    private function _check_input() {
        $a_needed_parameters = $this->_generate_needed_array();
        $b_return = true;
        foreach ($a_needed_parameters as $s_parameter => $s_displayname) {
            if (!isset($_POST[$s_parameter]) || $_POST[$s_parameter] == '') {
                if ($b_return == true) {
                    $_SESSION['login_error'] = "Folgende Felder sind nicht ausgefüllt:";
                }
                $b_return = false;
                $_SESSION['login_error'] .= '<br> -' . $s_displayname;
            }
        }
        if (!$b_return) {
            return $b_return;
        }
        return $this->_validate_input();
    }

    private function _generate_validation_array() {
        $a_same[0]['first'] = 'password';
        $a_same[0]['second'] = 'password_repeat';
        $a_same[0]['display'] = 'Passwort';
        $a_same[1]['first'] = 'email';
        $a_same[1]['second'] = 'email_repeat';
        $a_same[1]['display'] = 'E-mail';
        return $a_same;
    }

    private function _generate_needed_array(){
        $a_needed_parameters = array(
            'username'          => 'Benutzername',
            'email'             => 'E-Mail',
            'email_repeat'      => 'E-Mail Wiederholung',
            'password'          => 'Passwort',
            'password_repeat'   => 'Passwort Wiederholung'
        );
        return $a_needed_parameters;
    }
    
    private function _validate_input() {
        $a_same = $this->_generate_validation_array();
        $b_return = true;
        foreach ($a_same as $a_element) {
            if ($_POST[$a_element['first']] !== $_POST[$a_element['second']]) {
                if($b_return == true){
                    $_SESSION['login_error'] = "Folgende Felder sind nicht gleich:";
                }
                $b_return = false;
                $_SESSION['login_error'] .= '<br>' . $a_element['display'];
            }
        }
        return $b_return;
    }

}
