<?php

#################################################
# upload 2.0
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

session_start();
require_once (dirname(__FILE__) . '/dashboard/lib/library.php');
if ($_SERVER['HTTP_HOST'] == 'localhost:8080') {
    require_once (dirname(__FILE__) . '/dashboard/config/local_config.php');
} else {
    require_once (dirname(__FILE__) . '/dashboard/config/config.php');
}

class index {

    public function __construct() {

        $o_error_level = library::get_library('debugging');
        $o_error_level->set_error_level(library::get_config_value('i_error_level'));
        library::set_global_value('index_loadtime', library::get_timestamp());
        library::set_global_value('call_route_log', PHP_EOL . '[' . date('d.m.Y H:i:s') . ']' . PHP_EOL . '<br>');
        $o_tracker = library::get_library('tracking');
        $o_tracker->track_user();
        $this->_page_handler();
    }

    public function __destruct() {
        $s_performace_log = '[' . date('d.m.Y H:i:s') . ']' . PHP_EOL . '<br>';
        $s_performace_log .= 'Peak Mem : ' . substr((memory_get_peak_usage() / 1024 / 1024), 0, 7) . ' MB' . PHP_EOL . '<br>';
        $s_performace_log .= 'exec. time: ' . substr((library::get_timestamp() - library::get_global_value('index_loadtime')), 0, 7) . PHP_EOL . '<br>' . PHP_EOL . '<br>';
        $o_logging_helper = library::get_library('debugging');
        $o_logging_helper->write_log($s_performace_log, 'performace_log');
        $o_logging_helper->write_log(library::get_global_value('call_route_log'), 'call_route_log');
    }

    /**
     * (private)
     * 
     * checks if the module needs a permission
     * if the module is not registered
     * it counts as "needs permission"
     * 
     * @param string $s_called_page name of the module
     * @return boolean
     */
    private function _need_permission($s_called_page) {
        if (in_array($s_called_page, library::get_config_value('a_core_modules'))) {
            library::show_debug("<!-- core module(" . $s_called_page . ") dont need permissions -->");
                return false;
        }
        $s_query = "SELECT need_permission FROM [table_pre]_lychas_installed_modules where name = '" . library::escape_string($s_called_page) . "' AND active = '1' LIMIT 1";
        $a_result = library::select_query($s_query);
        if ($a_result[0] === NULL || $a_result[0]['need_permission'] == 1) {
            library::show_debug("<!-- module(" . $s_called_page . ") need permissions -->");
            return true;
        }
        library::show_debug("<!-- module dont need permissions -->");
        return false;
    }
    /**
     * (private)
     * 
     * main page handler
     * checks for login, permissions
     * and if the module is active
     */
    private function _page_handler() {
        if (!isset($_GET['url']) || $_GET['url'] == '') {
            $s_called_page = 'index';
        } else {
            if (substr($_GET['url'], -1) == '/') {
                $s_called_page = substr($_GET['url'], 0, -1);
            } else {
                $s_called_page = $_GET['url'];
            }
        }

        // try to load the module, 
        // set 404 error if its not found
        if (!$this->_load_module($s_called_page)) {
            library::show_debug("<!-- module not found, loading 404 -->");
            if ($s_called_page == 'index') {
                if (library::get_config_value('b_maintenance')) {
                    $_SESSION['tmp_action'] = 'maintenance';
                } else {
                    $_SESSION['tmp_action'] = 'custom_error';
                    $_SESSION['custom_error']['header'] = 'Hier entsteht eine Webpräsenz';
                    $_SESSION['custom_error']['content'] = 'Bald wird es hier mehr zu sehen geben!';
                }
            } else {
                $_SESSION['tmp_action'] = '404';
            }
            $s_called_page = 'error_pages';
            if (!$this->_load_module($s_called_page)) {
                exit('CRITICAL ERROR: ERROR MODULE NOT FOUND (err-code:1)');
            }
        }

        // do we need permission?
        // and do we have the permissions?
        // if not we habe an permission denied error
        if ($this->_need_permission($s_called_page)) {
            if (!library::validate_permissions($s_called_page)) {
                library::show_debug("<!-- not the needed permissions, loading perm. denied -->");
                $s_called_page = 'error_pages';
                $_SESSION['tmp_action'] = 'permission';
                if (!$this->_load_module($s_called_page)) {
                    exit('CRITICAL ERROR: ERROR MODULE NOT FOUND (err-code:2)');
                }
            }
        }


        //load modules as default or with specific parameters
        switch ($s_called_page) {
            case 'order_listing' :
                $a_param = NULL;
                library::show_debug("<!-- order_listing -->");
                $a_param[0]['lie_id'] = 37;
                new $s_called_page($this->_i_default_err_level, 300, $a_param);
                break;
            default :
                library::show_debug("<!-- " . $s_called_page . " -->");
                new $s_called_page();
                break;
        }
    }

    /**
     * (private)
     * 
     * checks if a module exists as a file
     * @param string $s_called_page module to check
     * @return boolean
     */
    private function _load_module($s_called_page) {
        $this->_get_module_path($s_called_page);
        if ($this->_get_module_path($s_called_page)) {
            library::show_debug("<!-- module path: " . './dashboard' . library::get_global_value('path') . $s_called_page . '.php' . " -->");
            if (file_exists('./dashboard' . library::get_global_value('path') . $s_called_page . '.php')) {
                require_once './dashboard' . library::get_global_value('path') . $s_called_page . '.php';
                return true;
            }
        }
        return false;
    }

    /**
     * (private)
     * gets path of module
     * @param string $s_called_page module which path you desire 
     */
    private function _get_module_path($s_called_page) {
        if (in_array($s_called_page, library::get_config_value('a_core_modules'))) {
            $a_result[0]['core_module'] = true;
        } else {
            $s_query = "SELECT core_module FROM [table_pre]_lychas_installed_modules where name = '" . library::escape_string($s_called_page) . "' LIMIT 1";
            $a_result = library::select_query($s_query);
            if ($a_result[0] === NULL || $a_result[0] === FALSE) {
                library::set_global_value('call_route_log', library::get_global_value('call_route_log') . '-- Module[' . $s_called_page . '] not found--' . PHP_EOL . '<br>');
                return false;
            }
        }
        if ($a_result[0]['core_module']) {
            $s_path = '/core/' . $s_called_page . '/';
            library::set_global_value('path', $s_path);
            library::set_global_value('module_name', $s_called_page);
            library::set_global_value('core', 1);
            library::set_global_value('call_route_log', library::get_global_value('call_route_log') . '-- Module[' . $s_called_page . '][CORE] set--' . PHP_EOL . '<br>');
        } else {
            $s_path = '/modules/' . $s_called_page . '/';
            library::set_global_value('path', $s_path);
            library::set_global_value('module_name', $s_called_page);
            library::set_global_value('core', 0);
            library::set_global_value('call_route_log', library::get_global_value('call_route_log') . '-- Module[' . $s_called_page . '] set--' . PHP_EOL . '<br>');
        }
        return true;
    }

    /**
     * (private)
     * 
     * checks if the user is logged in, 
     * and if the loggin session is valid
     * also updates rights if needed
     * 
     * @return boolean
     */
    private function _validate_login() {
        //token and user ID are set , and the token is valid? Good!
        if (isset($_SESSION['login_token']) && isset($_SESSION['user']['id']) && $_SESSION['login_token'] == md5($_SESSION['user']['id'] . '99184e362654e9bc6a734553d18800c9')) {
            library::show_debug("<!-- logged in -->");
            //lets see if we need a rights refresh
            $s_query = 'SELECT need_update FROM [table_pre]_refresh_rights_heap WHERE user_id = "' . $_SESSION['user']['id'] . '"';
            $a_result = library::database_query($s_query);
            if ($a_result[0] === NULL || $a_result[0]['need_update'] == 0) {
                // no everything is OK
                return true;
            } else {
                // something changed
                // so we have to call the login module, 
                // and make a rights refresh
                library::show_debug("<!-- update permissions -->");
                $this->_load_module('login');
                $_SESSION['tmp_action'] = 'update';
                $obj = new login();
                unset($obj);
                unset($_SESSION['tmp_action']);
                return true;
            }
        }
        //we are not logged in..
        library::show_debug("<!-- not logged in -->");
        return false;
    }

}

$o_bootstrap = new index();
