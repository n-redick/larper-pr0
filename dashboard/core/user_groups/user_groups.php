<?php

#################################################
# external-oms // user-group-manager
#################################################
# user managment and rightsd management
#################################################  
# @author Ni.Re 
# @copyright Copyright 2014 WIRmachenDRUCK GmbH
# @license commercial 
#################################################
#################################################
# VERSION 0.9 Build 140308
#################################################
# cleaned everything up, added descriptions 
# to every function
# --
# splitted modules and core modules
# --
# preparing for core release
# --
# added a new field to the database
#################################################

require_once (dirname(dirname(dirname(__FILE__))) . '/lib/wmd_core.php');

class user_groups extends wmd_core {

    /**
     * (private) Holds the view-class
     * @see _order_handler 
     */
    private $_o_view;
    private $i_elements_per_page = 30;
    private $_b_allow_api = false;

    public function __construct($i_error_level, $b_api_call = false) {
        parent::__construct($i_error_level, 'live', false);
        if ($b_api_call == false) {
            $this->_handler();
        } else {
            $this->_b_allow_api = true;
        }
    }

    public function _api_handler($a_params) {
        if ($this->_b_allow_api === false) {
            return false;
        }
        switch ($a_params['type']) {
            case 'change_rights' :
                $this->_update_rights($a_params['user_id'], $a_params['perm_id'], $a_params['status']);
                break;
        }
    }

    private function _update_rights($s_user_id, $s_perm_id, $b_status) {
	switch($b_status){
		case 0 :
		    $s_query = "INSERT INTO [table_pre]_usergroups_permissions (perm_id,group_id,status) VALUES ('" . $s_perm_id . "','" . $s_user_id . "','0') ON DUPLICATE KEY UPDATE status = 0";
		    $a_result = wmd_global::database_query($s_query,true,'dev');
		    if ($a_result[0] !== false) {
		        print '1';
		    }
		break;
		case 1 :
		    $s_query = "INSERT INTO [table_pre]_usergroups_permissions (perm_id,group_id,status) VALUES ('" . $s_perm_id . "','" . $s_user_id . "','1') ON DUPLICATE KEY UPDATE status = 1";
		    $a_result = wmd_global::database_query($s_query,true,'dev');
		    if ($a_result[0] !== false) {
		        print '1';
		    }
		break;
		case 2:
		    $s_query = "DELETE FROM [table_pre]_usergroups_permissions WHERE perm_id = '" . $s_perm_id . "' AND group_id = '" . $s_user_id . "'";
		    $a_result = wmd_global::database_query($s_query,true,'dev');
		    if ($a_result[0] !== false) {
		        print '1';
		    }
		break;
		case 8 :
		    $s_query = "SELECT id from adminbenutzer WHERE username = '" . $s_user_id . "' LIMIT 1";
		    $a_user_ids = wmd_global::database_query($s_query,true,'live');
		    if(!empty($a_user_ids[0])){
			    $s_query = "INSERT INTO [table_pre]_usergroups_members (user_id,group_id) VALUES ('" . $a_user_ids[0]['id'] . "','" . $s_perm_id . "') ON DUPLICATE KEY UPDATE user_id = user_id";
			    $a_result = wmd_global::database_query($s_query,true,'dev');
			    if ($a_result[0] !== false) {
				print '1';
			    }
		    }
		break;
		case 9 :
		    $s_query = "DELETE FROM [table_pre]_usergroups_members WHERE group_id = '" . $s_perm_id . "' AND user_id = '" . $s_user_id . "'";
		    $a_result = wmd_global::database_query($s_query,true,'dev');
		    if ($a_result[0] !== false) {
		        print '1';
		    }
		break;
		default:
		    print 'HELL NO!';
		break;
        }
        $s_query = "INSERT INTO [table_pre]_refresh_rights_heap (user_id,need_update) VALUES ('" . $s_user_id . "','1') ON DUPLICATE KEY UPDATE need_update = '1'";
        wmd_global::database_query($s_query, false);
    }

    private function _handler() {
        if (isset($_GET['a'])) {
            $s_task = $_GET['a'];
        } else {
            $s_task = '';
        }
        switch ($s_task) {
            case 'group' :
                $this->_get_group(base64_decode($_GET['i']));
                break;
            default :
                $this->_get_overview();
                break;
        }
    }

    private function _get_group($i_group_id) {
        $s_query = "SELECT id,name,description from [table_pre]_usergroups WHERE active = 1 AND id = '" . $i_group_id . "'";
        $a_group = wmd_global::database_query($s_query);
        $s_query = "SELECT "
                . "    a.name,"
                . "    b.group_id "
                . "FROM "
                . "    [table_pre]_permissions as a,"
                . "    [table_pre]_usergroups_permissions as b "
                . "WHERE "
                . "    a.id = b.perm_id "
                . "    AND b.group_id = '" . $i_group_id . "' "
                . "ORDER BY "
                . "    a.module_id";
        $a_result = wmd_global::database_query($s_query);
        foreach ($a_result as $a_row) {
            $a_user_element[$a_row['group_id']]['rights'][count($a_user_element[$a_row['group_id']]['rights'])] = $a_row['name'];
        }
        $s_query = "SELECT user_id FROM [table_pre]_usergroups_members WHERE group_id = '" . $i_group_id . "'";
        $a_members = wmd_global::database_query($s_query, true, 'dev');
        for ($i = 0; $i < count($a_members); $i++) {
            $s_query = "SELECT username,id,reseller__id,lieferanten__id,cdate,email_eil_sachbearbeiter FROM adminbenutzer where id = '" . $a_members[$i]['user_id'] . "'";
            $a_user = wmd_global::database_query($s_query, true, 'live');
            $a_members[$i] = $this->_get_role_and_permissions($a_user);
        }
	
	$s_query = "SELECT username,id FROM adminbenutzer";
        $a_all_admins = wmd_global::database_query($s_query, true, 'live');

        $a_entries['group'] = $a_group[0];
        $a_entries['rights'] = $a_user_element;
        $a_entries['members'] = $a_members;
        $a_entries['suggest'] = $a_all_admins;
        $this->_display('group', $a_entries);
    }

    private function _get_role_and_permissions($a_users) {
        $a_user_element = array();
        $s_sub_query = '';
        foreach ($a_users as $a_user) {
            if (preg_match('/^Abteilung-[0-9]+/', $a_user['username']) == 1) {
                $a_user_element[$a_user['id']]['role'] = 'Lieferant';
            } elseif ($a_user['lieferanten__id'] == 0 && $a_user['reseller__id'] == 0) {
                $a_user_element[$a_user['id']]['role'] = 'Mitarbeiter';
            } elseif ($a_user['reseller__id'] != 0) {
                $a_user_element[$a_user['id']]['role'] = 'Reseller';
            } else {
                $a_user_element[$a_user['id']]['role'] = 'Unbekannt';
            }
            $s_sub_query .= "'" . $a_user['id'] . "',";
            $a_user_element[$a_user['id']]['username'] = $a_user['username'];
            foreach ($a_user as $key => $value) {
                $a_user_element[$a_user['id']][$key] = $value;
            }
        }
        return $a_user_element;
    }

    private function _get_overview() {
        $s_query = "SELECT id,name,description from [table_pre]_usergroups  as a WHERE a.active = 1";
        $a_user_groups = wmd_global::database_query($s_query, true, 'dev');
        if (empty($a_user_groups)) {
            unset($a_user_groups[0]);
            $a_user_groups[0]['id'] = 0;
            $a_user_groups[0]['name'] = 'FEHLER:';
            $a_user_groups[0]['description'] = 'Leider wurden keine aktiven Benutzergruppen gefunden.';
        } else {
            for ($i = 0; $i < count($a_user_groups); $i++) {
                $s_query = "SELECT count(group_id) as members FROM [table_pre]_usergroups_members WHERE group_id = '" . $a_user_groups[$i]['id'] . "'";
                $a_members = wmd_global::database_query($s_query, true, 'dev');
                $a_user_groups[$i]['members'] = $a_members[0]['members'];
            }
        }
        $this->_display('overview', $a_user_groups);
    }

    private function _display($s_type, $a_entries = NULL, $i_entry_counter = NULL) {
        require_once (dirname(__FILE__) . '/view/view_user_groups.php');
        $this->_o_view = new view_user_groups();
        echo $this->_o_view->create_output($s_type, $a_entries, $i_entry_counter, $this->i_elements_per_page);
    }

}
