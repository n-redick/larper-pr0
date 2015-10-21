<?php

class dashboard_library {

    private $i_visitors = -1;
    private $i_uploads = -1;
    private $i_comments = -1;
    private $i_users = -1;
    private $i_reports = -1;

    public function _generate_arrow($i_value) {
        if ($i_value > -10 && $i_value < 10) {
            return "arrow-right";
        } else if ($i_value >= 10) {
            return "arrow-up";
        } else {
            return "arrow-down";
        }
    }

    public function generate_breadcrumb() {
        //<span><a href="http://www.creepypixel.com/demos/caffeine/1.1/dashboard.html#" class="icon entypo home"></a></span>
        $s_html = '';
        $s_url = '?url='.$_GET['url'];
        foreach ($_GET as $s_key => $s_value) {
            if ($s_key == 'url') {
                if($s_value != 'dashboard'){
                    $s_html .= '<span class="middle"></span><span><a href="{[_tpl_path]}'.$s_url.'">'. strtoupper(str_replace('_', ' ', $s_value)).'</a></span>';
                }
            }else{
                $s_url .= '&'.$s_key;
                $s_html .= '<span class="middle"></span><span><a href="{[_tpl_path]}'.$s_url.'">'. strtoupper(str_replace('_', ' ', $s_key)).'</a></span>';
            }
        }
        return $s_html;
    }

    public function _generate_stats() {
        //first circle
        $a_variables['{[visitors_count]}'] = $this->_get_visitors_count();

        //second circle
        $a_variables['{[visitors_percent]}'] = $this->_get_visitors_percent();
        $a_variables['{[visitors_arrow]}'] = $this->_generate_arrow($a_variables['{[visitors_percent]}']);

        //third circle
        $a_variables['{[uploads_count]}'] = $this->_get_uploads_count();

        //fourth circle        
        $a_variables['{[uploads_percent]}'] = $this->_get_uploads_percent();
        $a_variables['{[uploads_arrow]}'] = $this->_generate_arrow($a_variables['{[uploads_percent]}']);

        //fifth circle
        $a_variables['{[comments_count]}'] = $this->_get_comments_count();

        //sixth circle
        $a_variables['{[comments_percent]}'] = $this->_get_comments_percent();
        $a_variables['{[comments_arrow]}'] = $this->_generate_arrow($a_variables['{[comments_percent]}']);
        return $a_variables;
    }

    public function _generate_user_data() {
        $a_variables['{[username]}'] = $_SESSION['user']['name'];
        $a_variables['{[last_login]}'] = date('d.m.Y H:i:s');
        return $a_variables;
    }

    public function _get_visitors_count() {
        if ($this->i_visitors == -1) {
            $s_query = 'SELECT count(ip_address) as visitors FROM [table_pre]_lychas_tracker WHERE datetime >= "' . date('Y-m-d', strtotime('-7 days')) . '"';
            $a_result = library::select_query($s_query);
            $this->i_visitors = $a_result[0]['visitors'];
        }
        return $this->i_visitors;
    }

    public function _get_visitors_percent() {
        $i_this_week = $this->i_visitors;
        $s_query = 'SELECT count(ip_address) as visitors FROM [table_pre]_lychas_tracker WHERE datetime >= "' . date('Y-m-d', strtotime('-14 days')) . '" AND datetime < "' . date('Y-m-d', strtotime('-7 days')) . '"';
        $a_result = library::select_query($s_query);
        $i_last_week = $a_result[0]['visitors'];
        if ($i_last_week == 0) {
            $i_last_week = 1;
        }
        return (($i_this_week - $i_last_week) / $i_last_week) * 100;
    }

    public function _get_uploads_count() {
        return "70";
    }

    public function _get_uploads_percent() {
        return "-15";
    }

    public function _get_comments_count() {
        return "30";
    }

    public function _get_comments_percent() {
        return "0";
    }

    public function _generate_navigation() {
        $s_text = '';
        $s_query = "SELECT button_label,name FROM [table_pre]_lychas_installed_modules WHERE active = 1";
        $a_result = library::select_query($s_query);
        foreach ($a_result as $a_row) {
            if (library::validate_permissions($a_row['name'])) {
                $s_text .= '<li><a href="{[_tpl_path]}/?url=' . $a_row['name'] . '">' . $a_row['button_label'] . '</a></li>';
            } else {
                
            }
        }
        return $s_text;
    }

}
