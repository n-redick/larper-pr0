<?php

class login_library {

    public function login($s_username, $s_password) {
        // lets see what the real password looks like
        // but we are cautious, so we just want the md5 of the password
        $s_query = "SELECT MD5(AES_DECRYPT( password,'[AES_KEY]'))as password ,id,username FROM [table_pre]_lychas_user WHERE  `username` ='" . library::escape_string($s_username) . "'";
        $a_result = library::select_query($s_query);
        // no empty result, no sql error, and the password hashes match? PERFECT!
        if ($a_result[0] === NULL || $a_result[0] === FALSE || $a_result[0]['password'] != md5(md5($s_password.$s_username.md5(library::get_config_value('s_login_token'))))) {
            // something went wrong
            $_SESSION['login_error'] = 'Falscher Benutzername oder Passwort!';
            return false;
        } else {
            // all fine, lets setup basic informations,
            // and update rights
            $_SESSION['user']['id'] = $a_result[0]['id'];
            $_SESSION['user']['name'] = $a_result[0]['username'];
            $_SESSION['login_token'] = md5($a_result[0]['id'] . library::get_config_value('s_login_token'));
            $this->update_rights();
            return true;
        }
    }
    
    public function register($s_username,$s_password,$s_email){
        $s_query = "INSERT INTO [table_pre]_lychas_user ("
                 . " `username`,"
                 . " `password`,"
                 . " `email`"
                 . ") VALUE ("
                 . "'".library::escape_string($s_username)."',"
                 . "AES_ENCRYPT('".md5($s_password.$s_username.md5(library::get_config_value('s_login_token')))."','[AES_KEY]'),"
                 . "AES_ENCRYPT('".library::escape_string($s_email)."','[AES_KEY]'));";
        library::select_query($s_query);
        $this->login($s_username, $s_password);
    }

    /**
     * (private)
     * create a new permissions session array
     */
    public function update_rights() {
        unset($_SESSION['permissions']);
        //get Groups
        $s_query = "SELECT group_id FROM [table_pre]_lychas_usergroups_members WHERE user_id = '" . $_SESSION['user']['id'] . "'";
        var_dump($a_groups = library::select_query($s_query));
        if ($a_groups[0] !== NULL && $a_groups[0] !== FALSE) {
            foreach ($a_groups as $a_group) {
                // get permissions for the group
                $s_query = "SELECT a.status,b.id,b.display_name,b.name,c.name as module,b.parameter,b.icon from [table_pre]_lychas_usergroups_permissions as a ,[table_pre]_lychas_permissions as b, [table_pre]_lychas_installed_modules as c WHERE a.group_id = '" . $a_group['group_id'] . "' AND a.perm_id = b.id AND b.module_id = c.id AND c.active = 1";
                var_dump($a_result = library::select_query($s_query));
                // unset old permissions
                // if we have a valid result, create a new permissions array
                if ($a_result[0] !== NULL && $a_result[0] !== FALSE) {
                    foreach ($a_result as $a_module) {
                        if ($a_module['status'] == 2) {
                            unset($_SESSION['permissions'][$a_module['module']]);
                            $_SESSION['permissions'][$a_module['module']] = 'DENIED';
                        } else {
                            if ($_SESSION['permissions'][$a_module['module']] != 'DENIED') {
                                $_SESSION['permissions'][$a_module['module']][$a_module['name']]['display_name'] = $a_module['display_name'];
                                $_SESSION['permissions'][$a_module['module']][$a_module['name']]['name'] = $a_module['name'];
                                $_SESSION['permissions'][$a_module['module']][$a_module['name']]['parameter'] = $a_module['parameter'];
                                $_SESSION['permissions'][$a_module['module']][$a_module['name']]['icon'] = $a_module['icon'];
                            }
                        }
                    }
                }
            }
        }
        // get permissions for the user
        $s_query = "SELECT a.status,b.id,b.name,b.display_name,c.name as module, b.admin,b.parameter,b.icon from [table_pre]_lychas_user_permissions as a , [table_pre]_lychas_permissions as b, [table_pre]_lychas_installed_modules as c WHERE a.user_id = '" . $_SESSION['user']['id'] . "' AND a.perm_id = b.id AND b.module_id = c.id AND c.active = 1";
        $a_result = library::select_query($s_query);
        // unset old permissions
        // if we have a valid result, create a new permissions array
        if ($a_result[0] !== NULL && $a_result[0] !== FALSE) {
            foreach ($a_result as $a_module) {
                if ($a_module['status'] == 2) {
                    unset($_SESSION['permissions'][$a_module['module']]);
                    $_SESSION['permissions'][$a_module['module']] = 'DENIED';
                } else {
                    if ($_SESSION['permissions'][$a_module['module']] != 'DENIED') {
                        $_SESSION['permissions'][$a_module['module']][$a_module['right_name']]['name'] = $a_module['name'];
                        $_SESSION['permissions'][$a_module['module']][$a_module['right_name']]['right_name'] = $a_module['right_name'];
                        $_SESSION['permissions'][$a_module['module']][$a_module['right_name']]['admin'] = $a_module['admin'];
                        $_SESSION['permissions'][$a_module['module']][$a_module['right_name']]['parameter'] = $a_module['parameter'];
                        $_SESSION['permissions'][$a_module['module']][$a_module['right_name']]['icon'] = $a_module['icon'];
                    }
                }
            }
        }
        //reset the heap table
        $s_query = "DELETE FROM [table_pre]_refresh_rights_heap WHERE  user_id = " . $_SESSION['user']['id'];
        library::select_query($s_query);
    }

}

/*
     * To change this license header, choose License Headers in Project Properties.
     * To change this template file, choose Tools | Templates
     * and open the template in the editor.
     */

    