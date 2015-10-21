<?php

require_once (dirname(dirname(dirname(dirname(__FILE__)))) . '/lib/parent_classes/lychas_view_core.php');

class view_dashboard extends lychas_view_core {

    private $a_variables;
    private $i_visitors = -1;
    private $i_uploads = -1;
    private $i_comments = -1;
    private $i_users = -1;
    private $i_reports = -1;
    private $o_dashboard_lib;

    public function __construct() {
        parent::__construct();
        $this->o_dashboard_lib = library::get_library('dashboard');
    }

    private function _get_new_users_count() {
        if ($this->i_users == -1) {
            $s_query = 'SELECT count(username) as users FROM [table_pre]_lychas_user WHERE created >= "' . date('Y-m-d', strtotime('-7 days')) . '"';
            $a_result = library::select_query($s_query);
            $this->i_users = $a_result[0]['users'];
        }
        return $this->i_users;
    }

    private function _get_new_users_percent() {
        $i_this_week = $this->i_users;
        $s_query = 'SELECT count(username) as user FROM [table_pre]_lychas_user WHERE created >= "' . date('Y-m-d', strtotime('-14 days')) . '" AND created < "' . date('Y-m-d', strtotime('-7 days')) . '"';
        $a_result = library::select_query($s_query);
        $i_last_week = $a_result[0]['user'];
        if ($i_last_week == 0) {
            $i_last_week = 1;
        }
        return (($i_this_week - $i_last_week) / $i_last_week) * 100;
    }

    private function _get_open_reports() {
        return "0";
    }

    private function _get_report_list() {
        for ($i = 0; $i < 10; $i++) {
            $array[$i]['id'] = rand(200, 500);
            $array[$i]['user'] = "Nico";
            $array[$i]['url'] = "http://www.google.de";
            $array[$i]['post'] = "Lorem ipsum...";
            $array[$i]['from'] = "Gryndal";
            $array[$i]['amount'] = rand(1, 30);
        }
        return $array;
    }

    private function _generate_report_level($i_amount) {
        if ($i_amount < 3) {
            return '<span class="highlight green">' . $i_amount . '</span>';
        } else if ($i_amount < 10) {
            return '<span class="highlight yellow">' . $i_amount . '</span>';
        } else {
            return '<span class="highlight red">' . $i_amount . '</span>';
        }
    }

    private function _generate_report_list($a_array) {
        $s_html = '';
        foreach ($a_array as $a_line) {
            $s_html .= '<tr>';
            $s_html .= '    <td class="center">' . $a_line['id'] . '</td>';
            $s_html .= '    <td>' . $a_line['user'] . '</td>';
            $s_html .= '    <td><a href="' . $a_line['url'] . '">' . $a_line['post'] . '</a></td>';
            $s_html .= '    <td>' . $a_line['from'] . '</td>';
            $s_html .= '    <td class="center">' . $this->_generate_report_level($a_line['amount']) . '</td>';
            $s_html .= '</tr>';
        }
        $this->a_variables['{[report_list]}'] = $s_html;
    }

    private function _generate_user_list() {
        $s_query = "SELECT id,username,created FROM [table_pre]_lychas_user";
        $a_array = library::select_query($s_query);
        $s_html = '';
        foreach ($a_array as $a_line) {
            $s_html .= '<tr>';
            $s_html .= '    <td class="center">' . $a_line['id'] . '</td>';
            $s_html .= '    <td>' . $a_line['username'] . '</td>';
            $s_html .= '    <td>' . $a_line['created'] . '</td>';
            $s_html .= '    <td>' . rand(10, 20) . '</td>';
            $s_html .= '    <td>' . rand(10, 20) . '</td>';
            $s_html .= '</tr>';
        }
        $this->a_variables['{[user_list]}'] = $s_html;
    }

    private function _generate_visitor_graph($i_this_month) {
        $this->a_variables['{[visitor_graph]}'] = '[';
        if ($i_this_month < 12) {
            for ($i = $i_this_month + 1; $i <= 12; $i++) {
                if ($i < 10) {
                    $s_month = '0' . $i;
                } else {
                    $s_month = $i;
                }
                $s_query = 'SELECT count(ip_address) as visitors FROM [table_pre]_lychas_tracker WHERE datetime >= "' . date('Y') . '-' . $s_month . '-01" AND datetime < "' . date('Y') . '-' . $s_month . '-31"';
                $a_result = library::select_query($s_query);
                $this->a_variables['{[visitor_graph]}'] .= '[' . (0 - (12 - $i)) . ',' . $a_result[0]['visitors'] . '],';
            }
        }
        for ($i = 1; $i <= $i_this_month; $i++) {
            if ($i < 10) {
                $s_month = '0' . $i;
            } else {
                $s_month = $i;
            }
            $s_query = 'SELECT count(ip_address) as visitors FROM [table_pre]_lychas_tracker WHERE datetime >= "' . date('Y') . '-' . $s_month . '-01" AND datetime < "' . date('Y') . '-' . $s_month . '-31"';
            $a_result = library::select_query($s_query);
            $this->a_variables['{[visitor_graph]}'] .= '[' . $i . ',' . $a_result[0]['visitors'] . '],';
        }
        $this->a_variables['{[visitor_graph]}'] = substr($this->a_variables['{[visitor_graph]}'], 0, strlen($this->a_variables['{[visitor_graph]}']) - 1) . ']';
    }

    private function _generate_info_circles() {
        //first circle
        $this->a_variables['{[visitors_count]}'] = $this->o_dashboard_lib->_get_visitors_count();

        //second circle
        $this->a_variables['{[visitors_percent]}'] = $this->o_dashboard_lib->_get_visitors_percent();
        $this->a_variables['{[visitors_arrow]}'] = $this->o_dashboard_lib->_generate_arrow($this->a_variables['{[visitors_percent]}']);

        //third circle
        $this->a_variables['{[uploads_count]}'] = $this->o_dashboard_lib->_get_uploads_count();

        //fourth circle        
        $this->a_variables['{[uploads_percent]}'] = $this->o_dashboard_lib->_get_uploads_percent();
        $this->a_variables['{[uploads_arrow]}'] = $this->o_dashboard_lib->_generate_arrow($this->a_variables['{[uploads_percent]}']);

        //fifth circle
        $this->a_variables['{[comments_count]}'] = $this->o_dashboard_lib->_get_comments_count();

        //sixth circle
        $this->a_variables['{[comments_percent]}'] = $this->o_dashboard_lib->_get_comments_percent();
        $this->a_variables['{[comments_arrow]}'] = $this->o_dashboard_lib->_generate_arrow($this->a_variables['{[comments_percent]}']);

        //seventh circle
        $this->a_variables['{[users_count]}'] = $this->_get_new_users_count();

        //eighth circle
        $this->a_variables['{[users_percent]}'] = $this->_get_new_users_percent();
        $this->a_variables['{[users_arrow]}'] = $this->o_dashboard_lib->_generate_arrow($this->a_variables['{[users_percent]}']);

        //eighth circle
        $this->a_variables['{[report_count]}'] = $this->_get_open_reports();
    }

    private function _generate_mainpage() {
        $this->_generate_info_circles();
        $this->_generate_report_list($this->_get_report_list());
        $this->_generate_visitor_graph(date('m'));
    }

    private function _generate_userpage() {
        $this->_generate_user_list();
    }

    private function _generate_messagepage() {
        
    }

    private function _generate_optionpage() {
        
    }
    
    private function _load_logfile($s_type,$s_date){
        $s_path = library::get_config_value('s_root').'/logs/';
        switch($s_type){
            case 'sql' :
                $s_path .= '/sql_logs/sql_log_'.$s_date.'php';
                $this->a_variables['{[title]}'] = "SQL LOG";
                break;
            case 'sql_error' :
                $s_path .= '/sql_logs/sql_error_log_'.$s_date.'php';
                $this->a_variables['{[title]}'] = "SQL ERROR LOG";
                break;
            case 'php' :
                $s_path .= '/php_logs/error_log_'.$s_date.'.log';
                $this->a_variables['{[title]}'] = "PHP ERROR LOG";
                break;
            case 'lychas' :
                $s_path .= '/lychas_logs/call_route_log_'.$s_date.'.php';
                $this->a_variables['{[title]}'] = "LYCHAS CALL ROUTE LOG";
                break;
            case 'perf' :
                $s_path .= '/lychas_logs/performance_log_'.$s_date.'.php';
                $this->a_variables['{[title]}'] = "LYCHAS PERFORMACE LOG";
                break;
            default :
                return "Error: Wrong Type!";
        }
        if(file_exists($s_path)){
            if($s_type == 'php'){
                return substr(str_replace('[','<br><br>[',str_replace(']',']<br>',file_get_contents($s_path))),8);
            }else{
                return file_get_contents($s_path);
            }
        }else{
            return 'Keine Logs vorhanden';
        }
    }
    
    private function _generate_default_error_page(){
        $this->a_variables['{[title]}'] = "ÜBERSICHT";
        $this->a_variables['{[error_log]}'] = '<a href="{[_tpl_path]}?url=dashboard&error_logs&sql">SQL Log</a><br>'
                . '<a href="{[_tpl_path]}?url=dashboard&error_logs&sql_error">SQL Error Log</a><br>'
                . '<a href="{[_tpl_path]}?url=dashboard&error_logs&php">PHP Error Log</a><br>'
                . '<a href="{[_tpl_path]}?url=dashboard&error_logs&lychas">Lychas Call Route Log</a><br>'
                . '<a href="{[_tpl_path]}?url=dashboard&error_logs&performace">Lychas Performace Log</a>';
    }
    
    private function _generate_errorpage() {
        $s_type = '';
        if(isset($_GET['lychas'])){
            $s_type = 'lychas';
        }else if(isset($_GET['sql'])){
            $s_type = 'sql';
        }else if(isset($_GET['sql_error'])){
            $s_type = 'sql';
        }else if(isset($_GET['performance'])){
            $s_type = 'perf';
        }else if(isset($_GET['php'])){
            $s_type = 'php';
        }
        if($s_type != ''){        
            $this->a_variables['{[error_log]}'] = $this->_load_logfile($s_type, date('Ymd'));
        }else{
            $this->_generate_default_error_page();
        }
    }

    public function create_output() {
        if (isset($_GET['option'])) {
            $this->_generate_optionpage();
            $s_template = 'dashboard_options';
        } else if (isset($_GET['user'])) {
            $this->_generate_userpage();
            $s_template = 'dashboard_users';
        } else if (isset($_GET['error_logs'])) {
            $this->_generate_errorpage();
            $s_template = 'dashboard_error_logs';
        } else if (isset($_GET['admin_messages'])) {
            $this->_generate_messagepage();
            $s_template = 'dashboard_messages';
        } else {
            $this->_generate_mainpage();
            $s_template = 'dashboard_main';
        }
        return $this->_create_view($s_template, $this->a_variables, true);
    }

}
