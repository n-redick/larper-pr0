<?php

require_once (dirname(dirname(dirname(dirname(__FILE__)))) . '/lib/parent_classes/lychas_view_core.php');

class view_module_manager extends lychas_view_core {

    private $s_class_name;
    private $a_variables;

    public function __construct() {
        parent::__construct();
        $this->s_class_name = __CLASS__;
    }

    public function view_handler($a_data, $s_type) {
        $this->a_variables['{[title]}'] = "Installed Modules";
        $this->a_variables['{[title_2]}'] = "Waiting Modules";
        $this->a_variables['{[install_list]}'] = $this->_generate_list($a_data[0], true);
        $this->a_variables['{[waiting_list]}'] = $this->_generate_list($a_data[1], false);
        return $this->create_output();
    }

    private function _generate_list($a_array, $b_installed) {
        
        $s_html = '';
        if ($b_installed) {
            $s_controll_1 = 'class="icon awesome close tipsy-trigger" original-title="Uninstall"';
            $s_controll_2 = 'class="icon awesome close tipsy-trigger" original-title="Deactivate"';
        } else {
            $s_controll_1 = 'class="icon awesome check tipsy-trigger" original-title="Install"';
        }
        if (is_array($a_array)) {
            foreach ($a_array as $a_line) {
                $s_html .= '<tr>';
                $s_html .= '    <td>' . $a_line['name'] . '</td>';
                if($a_line['core_module'] == 1){
                    $s_html .= '    <td class="center"><span class="icon awesome check-square-o"></span></td>';
                }else{
                    $s_html .= '    <td class="center"><span class="icon awesome square-o"></span></td>';
                }
                $s_html .= '    <td class="center"><a href="#" ' . $s_controll . '></a></td>';
                $s_html .= '</tr>';
            }
        }
        return $s_html;
    }

    public function create_output() {
        $s_template = "overview";
        return $this->_create_view($s_template, $this->a_variables, true);
    }

}
