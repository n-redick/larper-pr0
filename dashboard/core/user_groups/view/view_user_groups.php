<?php

require_once (dirname(dirname(dirname(dirname(__FILE__)))) . '/lib/wmd_view_core.php');

class view_user_groups extends wmd_view_core {

    private $_i_elements_per_page;

    public function __construct() {
        parent::__construct();
    }

    public function create_output($s_type, $a_entries, $i_entry_counter, $i_elements_per_page) {
        $this->_i_elements_per_page = $i_elements_per_page;
        switch ($s_type) {
            case 'overview' :
                return $this->_create_layout_overview($a_entries);
                break;
            case 'group' :
                return $this->_create_layout_group($a_entries);
                break;
        }
    }

    private function _create_layout_group($a_entries) {
        $s_query = "SELECT b.id,a.button_label as m_name,b.name as r_name,group_id,status FROM [table_pre]_installed_modules as a, [table_pre]_permissions as b LEFT JOIN [table_pre]_usergroups_permissions ON id = perm_id AND group_id = " . $a_entries['group']['id'] . " where b.module_id = a.id ORDER BY m_name";
        $a_result = wmd_global::database_query($s_query, true, 'dev');
        $s_html_output = '<div class="panel panel-info">
                    <div class="panel-heading">
                        <h3 class="panel-title">Gruppeninformationen und Rechte</h3>
                    </div>
                    <div class="panel-body">
                        <div class="row">
                            <div class=" col-md-12 col-lg-12 hidden-xs hidden-sm">
                                <table class="table table-user-information" style="margin-top:25px;">
                                    <tbody>
                                    <tr>
                                        <td>Gruppenname:</td>
                                        <td>' . $a_entries['group']['name'] . '</td>
                                    </tr>
                                    </tbody>
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
            $s_html_output .= '<div class="col-md-6 column" style="padding:10px 0px">';
            $s_html_output .= ''
                    . '<div class="label label-success" data-status="1" data-right-id="' . $a_right['id'] . '" data-user-id="' . $a_entries['group']['id'] . '" style="border-top-right-radius:0px;border-bottom-right-radius:0px;cursor:pointer;height:34px;width:30px;position:absolute;top:-2px">'
                    . '<i style="text-align:center;margin-top:7px;font-size:14px;" class="glyphicon glyphicon-ok-circle">'
                    . '</i>'
                    . '</div>'
                    . '<div class="label label-warning" data-status="2" data-right-id="' . $a_right['id'] . '" data-user-id="' . $a_entries['group']['id'] . '" style="border-radius:0px;cursor:pointer;height:34px;width:30px;position:absolute;top:-2px;left:31px;">'
                    . '<i style="text-align:center;margin-top:7px;font-size:14px;" class="glyphicon glyphicon-record">'
                    . '</i>'
                    . '</div>'
                    . '<div class="label label-danger" data-status="0" data-right-id="' . $a_right['id'] . '" data-user-id="' . $a_entries['group']['id'] . '" style="border-top-left-radius:0px;border-bottom-left-radius:0px;cursor:pointer;height:34px;width:30px;position:absolute;top:-2px;left:62px;">'
                    . '<i style="text-align:center;margin-top:7px;font-size:14px;" class="glyphicon glyphicon-ban-circle">'
                    . '</i>'
                    . '</div>';

            if ($a_right['status'] == '0') {
                $s_html_output .= '<p class="label label-danger" data-status="1" data-right-id="' . $a_right['id'] . '" data-user-id="' . $a_entries['group']['id'] . '" style="padding:10px 25px;cursor:pointer;margin-left:95px;">' . strip_tags($a_right['r_name']) . '</p>';
            } else {
                if ($a_right['group_id'] != $a_entries['group']['id']) {
                    $s_html_output .= '<p class="label label-warning" data-status="2" data-right-id="' . $a_right['id'] . '" data-user-id="' . $a_entries['group']['id'] . '" style="padding:10px 25px;cursor:pointer;margin-left:95px;">' . strip_tags($a_right['r_name']) . '</p>';
                } else {
                    $s_html_output .= '<p class="label label-success" data-status="1" data-right-id="' . $a_right['id'] . '" data-user-id="' . $a_entries['group']['id'] . '" style="padding:10px 25px;cursor:pointer;margin-left:95px;">' . strip_tags($a_right['r_name']) . '</p>';
                }
            }
            $s_html_output .= '</div>';
        }

        $s_members = '';
        foreach ($a_entries['members'] as $a_members) {
            foreach ($a_members as $a_member) {
                $s_members .= ''
                        . '<div class="col-md-3 column">'
                        . '    <img style="position:absolute;top:-25px;left:0px;" class="img-circle" src="https://lh5.googleusercontent.com/-b0-k99FZlyE/AAAAAAAAAAI/AAAAAAAAAAA/eu7opA4byxI/photo.jpg?sz=50" alt="User Pic">'
                        . '    <button data-user-id="' . $a_member['id'] . '" id="delete_user" data-user-id="' . $a_member['id'] . '" data-group-id="'.$a_entries['group']['id'].'" style="position:absolute;top:0px;right:16px;" class="btn btn-sm btn-danger" type="button" data-toggle="tooltip" data-original-title="Remove this user"><i class="glyphicon glyphicon-remove"></i></button>'
                        . '    <div class="panel panel-info">         '
                        . '        <div class="panel-heading" style="cursor: pointer; padding: 12px 25px; height: 82px;" data-user-id="' . $a_member['id'] . '">'
                        . '            <h3 class="panel-title" style="text-align:center;line-height:26px">                 '
                        . '                ' . $a_member['username'] . '            '
                        . '            </h3>         '
                        . '        </div>         '
                        . '        <div class="content_' . $a_member['id'] . '" style="">             '
                        . '            <div class="panel-body" style="padding:0px;">                 '
                        . '                <div class="caption">                     '
                        . '                    <h3 style="text-align:center">                         '
                        . '                        ' . $a_member['role'] . '                     '
                        . '                    </h3>                 '
                        . '                </div>             '
                        . '            </div>         '
                        . '        </div>         '
                        . '        <a class="btn content_' . $a_member['id'] . ' btn-info btn-block" style="" target="_blank" href="dashboard.php?p=user_manager&a=show_user&t=' . $a_member['id'] . '">'
                        . '            Anzeigen         '
                        . '        </a>     '
                        . '    </div>'
                        . '</div>';
            }
        }
        $s_html_output .= '</div></div></div>
                        </div>
                    </div>
                    <div class="panel-footer" style="padding-left:20px;padding-bottom:0px;">
                    <div class="panel-heading" style="height:44px;margin-top:-14px;margin-left:-20px;margin-right:-15px;background-color:#ddd">
                        <span style="font-size:16px;font-weight:bold;">Gruppenmitglieder ('.count($a_entries['members']).')</span>
                        <span class="pull-right">
                            <button class="btn btn-sm btn-info" onClick="$(' . "'#members'" . ').slideToggle()" type="button" data-toggle="tooltip" data-original-title="Edit this user"><i class="glyphicon glyphicon-th-large"></i></button>
                            <button class="btn btn-sm btn-success" onClick="$(' . "'#new_user'" . ').slideToggle()" type="button" data-toggle="tooltip" data-original-title="Remove this user"><i class="glyphicon glyphicon-plus"></i></button>
                        </span>
                    </div>
                    <div id="new_user" style="display:none;background-color:#ddd;margin-left:-20px;margin-right:-15px;border-top: 1px solid #aaa;padding:10px 15px;">
                        <label for="tags">Benutzername: </label>
                        <input id="names" style="height:28px">
                        <button type="button" class="btn btn-ico btn-labeled btn-success" id="add_user" data-group-id="'.$a_entries['group']['id'].'">
                            <span class="btn-label">
                                <i class="glyphicon glyphicon-plus">
                                </i>
                            </span>
                            Benutzer Hinzuf&uuml;gen
                        </button>
                    </div>
                        <div class="row" style="margin-top:35px;display:none;" id="members">
                        ' . $s_members . '
                            </div>
                    </div>
                </div>';

        $s_js = "$(function() {
    var availableNames = [ ";
        foreach ($a_entries['suggest'] as $a_names) {
            $s_js .= '"' . $a_names['username'] . '",';
        }
        $s_js = substr($s_js, 0, -1) . '];
    $( "#names" ).autocomplete({
      source: availableNames
    });
  });';
        $a_variables['{[tpl_user_content]}'] = $s_html_output;
        $a_variables['{[tpl_user_logintoken]}'] = $_SESSION['login_token'];
        $a_variables['{[tpl_suggest_javascript]}'] = $s_js;
        return $this->_create_view('user_groups_single', $a_variables, __CLASS__, false, NULL, false);
    }

    private function _create_layout_overview($a_entries) {
        $s_html = '';
        foreach ($a_entries as $a_group) {
            $s_html .= '
            <div class="col-md-3">
                <div class="panel panel-info" style="cursor:pointer" data-href="dashboard.php?p=user_manager&amp;a=reseller">
                    <div class="panel-heading" style="min-height:69px;max-height:69px;padding:0px 0px; overflow:hidden">
                        <img alt="300x200" style="width:100%" src="http://lorempixel.com/600/200/business">
                    </div>
                    <div class="panel-body" style="height: 156px;">
                        <div class="caption">
                            <h3>
                                ' . $a_group['name'] . '
                            </h3>
                            <p>
                                ' . $a_group['description'] . '
                            </p>
                        </div>
                    </div>
                    <p>
                        <a href="dashboard.php?p=user_groups&a=group&i=' . base64_encode($a_group['id']) . '" class="btn btn-info btn-block">Anzeigen</a>
                    </p>
                    <div class="panel-footer">
                        ' . $a_group['members'] . ' Mitglieder
                    </div>
                </div>
            </div>';
        }
        $a_variables['{[tpl_content]}'] = $s_html;
        return $this->_create_view('user_groups_list', $a_variables, __CLASS__, false, NULL, false);
    }

}
