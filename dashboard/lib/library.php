<?php

#################################################
# project name
#################################################  
# @author Ni.Re 
# @copyright Copyright 2014  WIRmachenDRUCK GmbH
# @license commercial 
#################################################
#################################################
# VERSION 1.0 Build 140923 
#################################################
#  start of development
#################################################

class library {

    /**
     * (private)
     * array contains helper
     * @var array 
     */
    private static $_a_helper;

    /**
     * (public)(static)
     * gives back a config variable
     * @param string $s_field_name variable name
     */
    public static function get_config_value($s_field_name) {
        if (!isset(library::$_a_helper['config'])) {
            library::$_a_helper['config'] = new config();
        }
        return library::$_a_helper['config']->get_config_value($s_field_name);
    }

    public static function get_global_value($s_name) {
        library::_is_initialised('global_variables', false);
        return library::$_a_helper['global_variables']->get_value($s_name);
    }

    public static function set_global_value($s_name, $u_value) {
        library::_is_initialised('global_variables', false);
        library::$_a_helper['global_variables']->set_value($s_name, $u_value);
    }

    public static function select_query($s_query, $s_database = NULL) {
        library::_is_initialised('database', false);
        return library::$_a_helper['database']->database_query($s_query, true, $s_database);
    }

    public static function update_query($s_query, $s_database = NULL) {
        library::_is_initialised('database', false);
        library::$_a_helper['database']->database_query($s_query, false, $s_database);
    }

    public static function escape_string($s_string) {
        library::_is_initialised('database', false);
        return mysql_real_escape_string($s_string);
    }

    public static function delete_query($s_query, $s_database = NULL) {
        library::_is_initialised('database', false);
        library::$_a_helper['database']->database_query($s_query, false, $s_database);
    }

    public static function show_debug($s_debug_text) {
        library::_is_initialised('debugging', false);
        library::$_a_helper['debugging']->show_debug($s_debug_text);
    }

    private static function _is_initialised($s_helper_name, $b_passtrough = true) {
        if (!$b_passtrough) {
            if (!isset(library::$_a_helper[$s_helper_name])) {
                require_once library::get_config_value('s_root') . '/lib/library-files/' . $s_helper_name . '_library.php';
                $s_class_name = $s_helper_name . '_library';
                library::$_a_helper[$s_helper_name] = new $s_class_name();
            }
        } else {
            require_once library::get_config_value('s_root') . '/lib/library-files/' . $s_helper_name . '_library.php';
            $s_class_name = $s_helper_name . '_library';
            return new $s_class_name();
        }
    }

    public static function get_library($s_helper_name) {
        return library::_is_initialised($s_helper_name);
    }

    public static function redirect($a_parameters = NULL, $s_url = NULL, $b_secure = FALSE, $b_return_url = FALSE, $caller_file = NULL, $caller_line = NULL) {
        if ($a_parameters != NULL) {
            if ($b_secure === FALSE) {
                $s_parameters = '?';
                foreach ($a_parameters as $s_key => $s_value) {
                    $s_parameters .= $s_key . '=' . $s_value . '&';
                }
                $s_parameters = substr($s_parameters, 0, -1);
            } else {
                $o_secure_params = library::get_library('secure_params');
                $s_parameters = '?parameter=' . $o_secure_params->obfuscate_url($a_parameters);
            }
        }else{
            $s_parameters = '';
        }
        if ($s_url == NULL) {
            $s_redirect_url = basename($_SERVER['PHP_SELF']);
        } else {
            $s_redirect_url = $s_url;
        }
        library::set_global_value('call_route_log', library::get_global_value('call_route_log') . '(' . (string) $b_return_url . '|' . $caller_file . '|' . $caller_line . ')Redirecting to:' . $s_redirect_url . $s_parameters . PHP_EOL . '<br>');
        if ($b_return_url == true) {
            return $s_redirect_url . $s_parameters;
        } else {
            header('Location: ' . $s_redirect_url . $s_parameters);
            exit();
        }
    }

    /**
     * (public)(static)
     * checks if user has the 
     * permissions to see the called page
     * 
     * @param string $s_called_page
     * @return boolean 
     */
    public static function validate_permissions($s_called_page, $s_right = NULL) {
        if ($s_right === NULL) {
            if (isset($_SESSION['permissions'][$s_called_page]) && $_SESSION['permissions'][$s_called_page] != 'DENIED') {
                return true;
            }
            return false;
        } else {
            if (isset($_SESSION['permissions'][$s_called_page][$s_right]) && $_SESSION['permissions'][$s_called_page] != 'DENIED') {
                return true;
            }
            return false;
        }
    }

    public static function get_timestamp() {
        $i_tmp_time = microtime();
        $i_tmp_time = explode(" ", $i_tmp_time);
        $i_tmp_time = $i_tmp_time[1] + $i_tmp_time[0];
        return $i_tmp_time;
    }

}
