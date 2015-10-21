<?php

#################################################
# external-oms // config
#################################################
# Contains Config Varaibles
#################################################  
# @author Ni.Re 
# @copyright Copyright 2014 WIRmachenDRUCK GmbH
# @license commercial 
#################################################
#################################################
# VERSION 1.0 Build 140315
#################################################
# started implemetation, of config file to make
# usage of the framework a f***ing lot simpler 
#################################################

class config {

    private $_a_config;

    public function __construct() {

        date_default_timezone_set('Europe/Berlin');
        //SET VARIABLES
        $this->_a_config = array(
            //Debugging
            'i_error_level' => 2,
            'b_debugging' => true,
            'b_maintenance' => true,
            's_denied_mail' => 'n.redick@wir-machen-druck.de',
            's_404_mail' => 'n.redick@wir-machen-druck.de',
            //SQL
            's_db_engine' => 'mysql',
            's_table_prefix' => 'pr0',
            's_main_database' => 'larper_pr0',
            's_db_server' => 'localhost',
            's_db_user' => 'root',
            's_db_password' => '',
            's_db_name' => 'larper-pr0',
            's_AES_key' => 'x789723!789034w5j2f545n7N8N8735asdFdsa8Yz',
            'a_sql_token' => array(
                '[table_pre]' => 's_table_prefix',
                '[AES_KEY]' => 's_AES_key'
            ),
            //Caching
            'b_use_caching' => false,
            //Pathfinding
            's_main_file_name' => 'index.php',
            's_root' => dirname(dirname(__FILE__)),
            //Security
            's_login_token' => '99184e362654e9bc6a734553d18800c9',
            'b_save_mode' => false,
            's_save_mode_ip' => '37.99.200.50',
            'a_core_modules'    => array(
                                'error_pages',
                                'login'),
            'a_core_rights'     => array(
                'error_pages'           => false,
                'login'                 => false)
        );
    }

    public function get_config_value($s_field_name) {
        if (isset($this->_a_config[$s_field_name])) {
            return $this->_a_config[$s_field_name];
        } else {
            //i have no better idea -.-
            return 'ERR_FALSE';
        }
    }

}
