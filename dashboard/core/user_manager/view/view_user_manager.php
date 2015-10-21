<?php

require_once (dirname(dirname(dirname(dirname(__FILE__)))) . '/lib/wmd_view_core.php');

class view_user_manager extends wmd_view_core {

    private $_i_elements_per_page;

    public function __construct() {
        parent::__construct();
    }

    public function create_output($s_type, $a_entries, $i_entry_counter, $i_elements_per_page) {
        $this->_i_elements_per_page = $i_elements_per_page;
        switch ($s_type) {
            case 'overview' :
                $a_variables['{[reseller_entries]}'] = $a_entries['reseller'];
                $a_variables['{[factory_entries]}'] = $a_entries['factories'];
                $a_variables['{[worker_entries]}'] = $a_entries['worker'];
                $a_variables['{[all_entries]}'] = $a_entries['all'];
                $a_variables['_tpl_hide_box'] = 1;
                return $this->_create_view('user_manager_overview', $a_variables);
                break;
            case 'list' :
                return $this->_create_layout_all($a_entries, $i_entry_counter);
                break;
            case 'user' :
                return $this->_create_layout_user($a_entries[$_GET['t']]);
                break;
        }
    }

    private function _create_layout_user($a_users) {
        $s_query = "SELECT b.id,a.button_label as m_name,b.name as r_name,user_id FROM [table_pre]_installed_modules as a, [table_pre]_permissions as b LEFT JOIN [table_pre]_user_permissions ON id = perm_id AND user_id = " . $a_users['id'] . " where b.module_id = a.id";
        $a_result = wmd_global::database_query($s_query);
        switch (strtolower($a_users['role'])) {
            case 'mitarbeiter' :
                $a_users['user_pic'] = '{[_tpl_path]}/dashboard/img/worker.png';
                break;
            case 'reseller' :
                $a_users['user_pic'] = '{[_tpl_path]}/dashboard/img/reseller.png';
                break;
            case 'lieferant' :
                $a_users['user_pic'] = '{[_tpl_path]}/dashboard/img/factory.png';
                break;
            default :
                $a_users['user_pic'] = '{[_tpl_path]}/dashboard/img/unknown.png';
                break;
        }
        $s_html_output = '<div class="panel panel-info">
                    <div class="panel-heading">
                        <h3 class="panel-title">Benutzerinformationen und Rechte</h3>
                    </div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-3 col-lg-3 hidden-xs hidden-sm">
                                <img class="img-circle" src="' . $a_users['user_pic'] . '" alt="User Pic">
                            </div>
                            <div class=" col-md-9 col-lg-9 hidden-xs hidden-sm">
                                <table class="table table-user-information" style="margin-top:25px;">
                                    <tbody>
                                    <tr>
                                        <td>Benutzername:</td>
                                        <td>' . $a_users['username'] . '</td>
                                    </tr>
                                    <tr>
                                        <td>Kontoart:</td>
                                        <td>' . $a_users['role'] . '</td>
                                    </tr>
                                    <tr>
                                        <td>Erstellt:</td>
                                        <td>' . date('d.m.Y H:i:s', strtotime($a_users['cdate'])) . '</td>
                                    </tr>';
        if ($a_users['email_eil_sachbearbeiter'] != '') {
            $s_html_output .= '     <tr>
                                        <td>E-Mail:</td>
                                        <td>' . $a_users['email_eil_sachbearbeiter'] . '</td>
                                    </tr>';
        }
        $s_html_output .= '         </tbody>
                                </table>';

        $_last_module = '';
        foreach ($a_result as $a_right) {
            if ($a_right['m_name'] != $_last_module) {
                if ($_last_module != '') {
                    $s_html_output .= '</div></div>';
                }
                $s_html_output .= '<div class="panel panel-info">
                                        <div class="panel-heading panel-rights" data-right-name="' . md5($a_right['m_name']) . '">
                                            <h3 class="panel-title">
                                            	' . $a_right['m_name'] . '
                                            </h3>
                                        </div>
                                        <div class="panel-body" id="' . md5($a_right['m_name']) . '" style="display:none">';
                $_last_module = $a_right['m_name'];
            }
            $s_html_output .= '<div class="col-md-4 column" style="padding:10px 0px;margin:10px;">';
            if ($a_right['user_id'] != $a_users['id']) {
                $s_html_output .= '<p class="label label-warning" data-status="0" data-right-id="' . $a_right['id'] . '" data-user-id="' . $a_users['id'] . '" style="padding:10px 25px;cursor:pointer">' . $a_right['r_name'] . '</p>';
            } else {
                $s_html_output .= '<p class="label label-success" data-status="1" data-right-id="' . $a_right['id'] . '" data-user-id="' . $a_users['id'] . '" style="padding:10px 25px;cursor:pointer">' . $a_right['r_name'] . '</p>';
            }
            $s_html_output .= '</div>';
        }
        $s_html_output .= '</div></div></div>
                        </div>
                    </div>
                    <div class="panel-footer">';
        if ($a_users['email_eil_sachbearbeiter'] != '') {
            $s_html_output .= '<button class="btn btn-sm btn-primary" type="button" data-toggle="tooltip" data-original-title="Send message to user"><i class="glyphicon glyphicon-envelope"></i></button>';
        } else {
            $s_html_output .= '<button style="display:none" class="btn btn-sm btn-primary" type="button" data-toggle="tooltip" data-original-title="Send message to user"><i class="glyphicon glyphicon-envelope"></i></button>';
        }
        $s_html_output .= '<span class="pull-right" style="display:none">
                            <button class="btn btn-sm btn-warning" type="button" data-toggle="tooltip" data-original-title="Edit this user"><i class="glyphicon glyphicon-edit"></i></button>
                            <button class="btn btn-sm btn-danger" type="button" data-toggle="tooltip" data-original-title="Remove this user"><i class="glyphicon glyphicon-remove"></i></button>
                        </span>
                    </div>
                </div>';
        $a_variables['{[tpl_user_content]}'] = $s_html_output;
        $a_variables['{[tpl_user_logintoken]}'] = $_SESSION['login_token'];
        $a_variables['{[tpl_role]}'] = $_GET['last'];
        return $this->_create_view('user_manager_single', $a_variables);
    }

    private function _create_layout_all($a_users, $i_entry_counter) {
        $s_html_output = '';
        $i_row_counter = 0;
        foreach ($a_users as $i_id => $a_user) {
            if (!isset($a_user['username']) || $a_user['username'] == '') {
                $s_html_output .= '</div>';
            } else {
                if ($i_row_counter == 4) {
                    $s_html_output .= '</div><div style="height:10px;width:100%"></div>';
                    $i_row_counter = 0;
                }
                if ($i_row_counter == 0) {
                    $s_html_output .= '<div class="row">';
                }
                switch (strtolower($a_user['role'])) {
                    case 'mitarbeiter' :
                        $a_user['user_pic'] = '{[_tpl_path]}/dashboard/img/worker.png';
                        break;
                    case 'reseller' :
                        $a_user['user_pic'] = '{[_tpl_path]}/dashboard/img/reseller.png';
                        break;
                    case 'lieferant' :
                        $a_user['user_pic'] = '{[_tpl_path]}/dashboard/img/factory.png';
                        break;
                    default :
                        $a_user['user_pic'] = '{[_tpl_path]}/dashboard/img/unknown.png';
                        break;
                }

                $s_html_output .= '<div class="col-md-3 column" style="position:relative">'
                        . ' <img style="position:absolute;top:-25px;left:0px;width:50px;" class="img-circle" src="' . $a_user['user_pic'] . '" alt="User Pic">'
                        . '     <div class="panel panel-info">'
                        . '         <div class="panel-heading" style="cursor:pointer;padding:12px 25px" data-user-id="' . $i_id . '">'
                        . '             <h3 class="panel-title" style="text-align:center;line-height:26px">'
                        . '                 ' . utf8_decode($a_user['username'])
                        . '             </h3>'
                        . '         </div>'
                        . '         <div class="content_' . $i_id . '" style="display:none">'
                        . '             <div class="panel-body">'
                        . '                 <div class="caption">'
                        . '                     <h3 style="text-align:center">'
                        . '                         ' . $a_user['role']
                        . '                     </h3>';
                if (isset($a_user['rights'])) {
                    foreach ($a_user['rights'] as $s_name) {
                        $s_html_output .= '                     <p class="label label-info" data-user-id="' . $i_id . '" data-permission-name="' . md5($s_name) . '" style="cursor:pointer;display:block;padding:10px 0px;margin-bottom:-1px;margin-top:10px;">' . utf8_decode($s_name) . '</p>';
                        $s_html_output .= '                     <div style="display:none;border:1px solid #5bc0de;padding:10px;border-bottom-left-radius: .25em;border-bottom-right-radius: .25em" id="' . md5($s_name) . '_' . $i_id . '"></div>';
                    }
                }
                $s_html_output .= '                 </div>'
                        . '             </div>'
                        . '         </div>'
                        . '         <a class="btn content_' . $i_id . ' btn-info btn-block" style="display:none;padding:auto" href="{[_tpl_base_url]}?p=user_manager&a=show_user&t=' . $i_id . '&last=' . $_GET['a'] . '">'
                        . '             Anzeigen'
                        . '         </a>'
                        . '     </div>'
                        . '</div>';
                $i_row_counter++;
            }
        }
        $s_query = "SELECT name,description FROM [table_pre]_permissions";
        $a_result = wmd_global::database_query($s_query);
        foreach ($a_result as $a_rights) {
            $s_html_output .= '<div style="display:none;visibility:hidden" id="' . md5($a_rights['name']) . '">' . utf8_decode($a_rights['description']) . '</div>';
        }
        if (isset($_GET['o'])) {
            $i_page = $_GET['o'];
        } else {
            $i_page = 1;
        }
        $s_pagination = $this->_create_pagination($i_page, (ceil($i_entry_counter / $this->_i_elements_per_page)), 7, '?p=user_manager&a=' . $_GET['a'], 'o');
        $a_variables['{[tpl_content]}'] = '<div style="margin:0 auto;width:414px">' . $s_pagination . '</div>' . $s_html_output . '<div style="margin:0 auto;width:414px">' . $s_pagination . '</div>';
        return $this->_create_view('user_manager_list', $a_variables);
    }

}
