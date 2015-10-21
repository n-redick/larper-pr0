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

class debugging_library {

    public function show_debug($s_debug_text) {
        if (library::get_config_value('b_debugging')) {
            if (is_array($s_debug_text)) {
                echo print_r($s_debug_text);
            } else {
                echo $s_debug_text;
            }
        }
    }

    public function show_error_page($s_header, $s_content) {
        $_SESSION['custom_error']['header'] = $s_header;
        $_SESSION['custom_error']['content'] = $s_content;
        $_SESSION['tmp_action'] = 'custom_error';
        header('Location: ' . basename($_SERVER['PHP_SELF']) . '?p=error_pages');
        exit();
    }

    public function show_error($s_content) {
        exit($s_content);
    }

    public function set_error_level($i_error_level) {
        library::show_debug("<!-- error_level:" . $i_error_level . " -->");
        error_reporting(E_ALL);
        switch ($i_error_level) {
            case 0 :
                ini_set('display_errors', 'Off');
                break;
            case 1 :
                ini_set('display_errors', 'On');
                break;
            case 2 :
                ini_set('display_errors', 'On');
                break;
            default :
                ini_set('display_errors', 'Off');
                break;
        }
        ini_set('log_errors', 'On');
        ini_set('error_log', library::get_config_value('s_root') . '/logs/php_logs/error_log_' . date('Ymd') . '.log');
    }

    public function write_log($s_error_text, $s_error_file = FALSE) {
        if ($s_error_file == false) {
            $s_error_file = library::get_config_value('s_root') . '/logs/dashboard_logs/default_log_' . date('Ymd') . '.php';
        } else {
            if (strpos($s_error_file, 'sql') === FALSE) {
                $s_error_file = library::get_config_value('s_root') . '/logs/dashboard_logs/' . $s_error_file . '_' . date('Ymd') . '.php';
            } else {
                $s_error_file = library::get_config_value('s_root') . '/logs/sql_logs/' . $s_error_file . '_' . date('Ymd') . '.php';
            }
        }
        if (!file_exists($s_error_file)) {
            $s_error_text = '<?php' . PHP_EOL . 'if($_SERVER["REMOTE_ADDR"] != "37.99.200.50"){exit();}' . PHP_EOL . '?>' . PHP_EOL . $s_error_text . PHP_EOL . PHP_EOL;
        }
        $handle = @fopen($s_error_file, "a");
        if ($handle !== false) {
            fwrite($handle, $s_error_text . PHP_EOL);
            fclose($handle);
            chmod($s_error_file, 0777);
        } else {
            library::show_debug('<!-- could not write sql-log! -->');
        }
    }

}
