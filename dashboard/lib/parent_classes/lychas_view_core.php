<?php

#################################################
# external-oms // lib // core view
#################################################
# parent of all view classes
#################################################  
# @author Ni.Re 
# @copyright Copyright 2013 WIRmachenDRUCK GmbH
# @license commercial 
#################################################
#################################################
# VERSION 1.1 Build 140711
#################################################
#  added a filter, that only tpl variables 
#  starting with '{[' and ending with ']}'
#  will be replaced
#################################################
#################################################
# VERSION 1.1 Build 140426
#################################################
#  redone the _create_view function
#################################################
#################################################
# VERSION 1.0 Build 140419
#################################################
#  added documentation
#  --
#  clean up
#  --
#  added config values
#  --
#  Release
#################################################

class lychas_view_core {

    private $hide_box = '';

    public function __construct() {
        
    }

    //make a variable optional in 1.2 release
    protected function _create_view($s_filename, $a_variables, $b_dashboard = false) {

        if (isset($a_variables['_tpl_hide_box'])) {
            $this->hide_box = '_hide';
            unset($a_variables['_tpl_hide_box']);
        }
        if ($b_dashboard) {
            return $this->generate_dashboard_layout($s_filename, $a_variables);
        } else {
            $s_template = $this->_get_template($s_filename);
            $s_html_output = $this->_replace_vars($s_template, $a_variables);

            library::show_debug("<!-- dynamic -->");
            return $this->_set_basic_values($s_html_output);
        }
    }

    private function generate_dashboard_layout($s_filename, $a_variables) {
        $o_dashboard_style = library::get_library('dashboard');
        $a_variables['{[navigation]}'] = $o_dashboard_style->_generate_navigation();
        if(!isset($a_variables['{[visitor_graph]}'])){
            $a_variables['{[visitor_graph]}'] = '"";';
        }
        $a_variables['{[breadcrumbs]}'] = $o_dashboard_style->generate_breadcrumb();
        $a_variables = $a_variables + $o_dashboard_style->_generate_user_data();
        $a_variables = $a_variables + $o_dashboard_style->_generate_stats();
        $a_variables = array('{[TPL_MASTER_CONTENT]}' => $this->_get_template($s_filename)) + $a_variables;

        $s_template = $this->_get_template('master_frame',true);
        $s_html_output = $this->_replace_vars($s_template, $a_variables);

        library::show_debug("<!-- dynamic -->");
        return $this->_set_basic_values($s_html_output);
    }

    private function _set_basic_values($s_html_output) {
        $a_variables['{[_tpl_path]}'] = substr(str_replace(library::get_config_value('s_main_file_name'), '', $_SERVER['PHP_SELF']), 0, -1);
        $a_variables['{[_tpl_base_url]}'] = $a_variables['{[_tpl_path]}'] . '/' . library::get_config_value('s_main_file_name');
        $s_html_output = $this->_replace_vars($s_html_output, $a_variables);
        return $s_html_output;
    }

    private function _replace_vars($s_html, $a_variables) {
        foreach ($a_variables as $s_key => $s_value) {
            if ((substr($s_key, 0, 2) == '{[' && substr($s_key, -2) == ']}') || (substr($s_key, 0, 2) == '[{' && substr($s_key, -2) == '}]')) {
                $s_html = str_replace($s_key, $s_value, $s_html);
            }
        }
        return $s_html;
    }

    private function _get_template($s_filename,$b_dashboard = false) {
        if($b_dashboard){
            $s_path = library::get_config_value('s_root') . '/core/dashboard/tpl/tpl_' . $s_filename . '.php';
        }else{
            $s_path = library::get_config_value('s_root') . library::get_global_value('path') . 'tpl/tpl_' . $s_filename . '.php';
        }
        if (file_exists($s_path)) {
            return file_get_contents($s_path);
        } else {
            echo $s_path;
            exit('Template Datei konnte nicht gefunden werden! Leider konnte die benötigte Template datei nicht gefunden werden.<br>(Error : 3 ; Template Name : tpl_' . $s_filename);
        }
    }

}
