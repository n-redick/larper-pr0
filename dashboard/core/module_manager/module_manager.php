<?php

#################################################
# external-oms // module_manager
#################################################
# DESCRIPTION:
# 
#################################################  
# @author  Ni.Re
# @copyright Copyright 2014 WIRmachenDRUCK GmbH
# @license commercial 
#################################################
# INFORMATION:
# Every internal function(private) start with an underscore 
#################################################
#################################################
# VERSION 1.0 Build 1400714
#################################################
# added
#################################################

class module_manager {

    private $_a_core_modules;
    private $_a_core_modules_database;
    private $_a_modules;
    private $_a_modules_database;
    
    private $_a_installed_modules;
    private $_a_waiting_modules;

    public function __construct() {
        $this->_get_core_modules_files();
        $this->_get_core_modules_database_entries();
        
        $this->_get_module_files();
        $this->_get_module_database_entries();
        $this->_get_installed_modules($this->_a_core_modules, $this->_a_core_modules_database);
        $this->_get_installed_modules($this->_a_modules, $this->_a_modules_database);

        $a_result[0] = $this->_a_installed_modules;
        $a_result[1] = $this->_a_waiting_modules;
        echo $this->_create_output($a_result);
    }



    private function _get_installed_modules($a_files,$a_database_entries){
        foreach($a_database_entries as $a_module){
            if(isset($a_files[$a_module['name']])){
                $this->_a_installed_modules[count($this->_a_installed_modules)] = $a_module;
                unset($a_files[$a_module['name']]);
            }
        }
        foreach($a_files as $a_file){
            $this->_a_waiting_modules[count($this->_a_waiting_modules)] = $a_file;
        }
    }
    
    private function _format_array($a_array,$b_core =false) {
        $a_formated_array = array();
        foreach ($a_array as $a_row) {
            if ($a_row != '..' && $a_row != '.') {
                if($a_row != 'error_pages' && $a_row != 'login' ){
                    $a_formated_array[$a_row]['name'] = $a_row;
                    if($b_core){
                        $a_formated_array[$a_row]['core_module'] = 1;
                    }else{
                        $a_formated_array[$a_row]['core_module'] = 0;
                    }
                }
            }
        }
        return $a_formated_array;
    }

    private function _get_core_modules_files() {
        $this->_a_core_modules = $this->_format_array(scandir(dirname(dirname(dirname(__FILE__))) . '/core/'),true);
    }

    private function _get_core_modules_database_entries() {
        $s_query = "SELECT * FROM [table_pre]_lychas_installed_modules WHERE core_module = 1";
        $this->_a_core_modules_database = library::select_query($s_query);
    }

    private function _get_module_files() {
        $this->_a_modules = $this->_format_array(scandir(dirname(dirname(dirname(__FILE__))) . '/modules/'));
    }

    private function _get_module_database_entries() {
        $s_query = "SELECT * FROM [table_pre]_lychas_installed_modules WHERE core_module = 0";
        $this->_a_modules_database = library::select_query($s_query);
    }
    
    private function _create_output($a_result, $s_type = NULL) {
        require_once (dirname(__FILE__) . '/view/view_' . __CLASS__ . '.php');
        $s_view_name = 'view_' . __CLASS__;
        $this->_o_view = new $s_view_name;
        echo $this->_o_view->view_handler($a_result, $s_type);
    }

}
