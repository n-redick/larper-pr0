<?php

require_once (dirname(dirname(dirname(dirname(__FILE__)))) . '/lib/parent_classes/lychas_view_core.php');

class view_register extends lychas_view_core {

    public function __construct() {
        parent::__construct();
    }

    private function _generate_needed_array() {
        $a_needed_parameters = array(
            'username' => 'Benutzername',
            'email' => 'E-Mail',
            'email_repeat' => 'E-Mail Wiederholung',
            'password' => 'Passwort',
            'password_repeat' => 'Passwort Wiederholung'
        );
        return $a_needed_parameters;
    }

    private function fill_input($a_variables) {
        $a_needed = $this->_generate_needed_array();
        foreach($a_needed as $key => $value){
            if(isset($_POST[$key]) && $_POST[$key] != '' && $key != 'password_repeat'){
                $a_variables['{['.$key.']}'] = $_POST[$key];
            }else{
                $a_variables['{['.$key.']}'] = '';
            }
        }
        return $a_variables;
    }

    public function create_output() {
        if (isset($_SESSION['login_error'])) {
            $a_variables['{[tpl_register_error]}'] = '<div id="fehlermeldung">' . $_SESSION['login_error'] . '</div>';
            unset($_SESSION['login_error']);
        } else {
            $a_variables['{[tpl_register_error]}'] = '';
        }

        $a_variables = $this->fill_input($a_variables);
        $a_variables['{[tpl_login_error]}'] = '';
        $a_variables['{[tab_register]}'] = 'active';
        $a_variables['{[tab_login]}'] = '';
        return $this->_create_view('login', $a_variables);
    }

}
