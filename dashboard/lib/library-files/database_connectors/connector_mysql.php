<?php

#################################################
# external-oms // lib // global_holder
#################################################
# holds global variables and objects
# usable in each module
#################################################  
# @author Ni.Re 
# @copyright Copyright 2013 WIRmachenDRUCK GmbH
# @license commercial 
#################################################
#################################################
# VERSION 1.0 Build 140419
#################################################
#  added documentation
#  --
#  clean up
#  --
#  added config values
#  --
#  Release
#################################################

class connector_mysql {

    /**
     * (private) sql object container
     * @see __construct 
     */
    private $_o_sql_connections;

    /**
     * (private) debug text container
     * @see database_query 
     */
    private $_s_debug_text = '';

    /**
     * (public)
     * sets SQL Default Params
     * 
     * @param type $o_sql_data existing SQL object
     * @param type $s_database main Database name
     */
    public function __construct($s_database = NULL) {
        //get config file
        $this->set_database_infos(library::get_config_value('s_main_database'), library::get_config_value('s_db_server'), library::get_config_value('s_db_user'), library::get_config_value('s_db_password'), library::get_config_value('s_db_name'));
    }

    /**
     * (public)
     * set up new database Information, so we can use it later on
     * 
     * @param type $s_database_info_name
     * @param type $s_server
     * @param type $s_user
     * @param type $s_password
     * @param type $s_db
     * @return boolean
     */
    public function set_database_infos($s_database_info_name, $s_server, $s_user, $s_password, $s_db) {
        if (isset($s_database_info_name) && isset($s_server) && isset($s_user) && isset($s_password) && isset($s_db)) {
            $array = array();
            $array['server'] = $s_server;
            $array['user'] = $s_user;
            $array['password'] = $s_password;
            $array['db'] = $s_db;
            $array['connection'] = mysql_connect($s_server, $s_user, $s_password);
            mysql_select_db($s_db, $array['connection']);
            $this->_o_sql_connections[$s_database_info_name] = $array;
            return true;
        }
        return false;
    }

    /**
     * (public)
     * makes the DB query,returns array,calles handler for log and error reports
     * @param type $s_query the query
     * @param type $b_result should there be a return value?
     * @param type $s_database which database to use
     * @param type $i_error_level what errors will be reportet
     * @return array if [0] = false , something went wrong
     */
    public function database_query($s_query, $b_result = true, $s_database = NULL, $i_error_level = NULL) {
        if ($s_database == NULL) {
            $s_database = wmd_global::get_config_value('s_main_database');
        }
        if ($s_database == NULL) {
            $i_error_level = wmd_global::get_config_value('i_error_level');
        }
        if (!isset($this->_o_sql_connections[$s_database])) {
            exit('Selected Database Information is not set!');
        }
        $i_starttime = library::get_timestamp();
        //make a result array
        $o_result = mysql_query($s_query, $this->_o_sql_connections[$s_database]['connection']);
        if (!$o_result) {
            $a_return[0] = FALSE;
            $a_return[1] = mysql_error($this->_o_sql_connections[$s_database]['connection']);
            $a_return[2] = mysql_errno($this->_o_sql_connections[$s_database]['connection']);
            $a_return[3] = $s_query;
        } elseif ($b_result == true) {
            $i_counter = 0;
            if (mysql_num_rows($o_result) > 0) {
                while ($row = mysql_fetch_assoc($o_result)) {
                    $a_return[$i_counter] = $row;
                    $i_counter++;
                }
            } else {
                $a_return[0] = NULL;
            }
        } else {
            $a_return[0] = TRUE;
            $a_return[1] = mysql_affected_rows();
        }
        $i_endtime = library::get_timestamp();
        $i_total_time = ($i_endtime - $i_starttime);
        $this->_monitoring($i_total_time, $s_query, $a_return, $o_result, $i_error_level);
        return $a_return;
    }

    /**
     * (privat)
     * creates the logs, and error reports
     * 
     * @param type $i_total_time execution time
     * @param type $s_query the query
     * @param type $a_return return value
     * @param type $o_result query result
     * @param type $i_error_level error_level
     */
    private function _monitoring($i_total_time, $s_query, $a_return, $o_result, $i_error_level) {
        switch ($i_error_level) {
            case 0:
                //no reporting at all!
                break;
            case 1:
                if ($a_return[0] === false) {
                    $this->_create_query_log($i_total_time, $s_query, $a_return, $o_result);
                    $this->_write_log($this->_s_debug_text, dirname(dirname(__FILE__)) . '/dashboard/error_log/sql_error_log_' . date('Ymd') . '.php');
                    $this->_s_debug_text = '';
                }
                break;
            case 2:
                if (substr($i_total_time, 0, 2) != '0.' || $s_query == '' || (!is_array($a_return[0]) && $a_return[0] !== TRUE)) {
                    $this->_create_query_log($i_total_time, $s_query, $a_return, $o_result);
                    if ($a_return[0] === false) {
                        $this->_write_log($this->_s_debug_text, dirname(dirname(__FILE__)) . '/dashboard/error_log/sql_error_log_' . date('Ymd') . '.php');
                    }
                    $this->_write_log($this->_s_debug_text);
                    $this->_s_debug_text = '';
                }
                break;
            case 3:
                if ($a_return[0] === false) {
                    $this->_create_query_log($i_total_time, $s_query, $a_return, $o_result);
                }
                break;
            case 4:
                if (substr($i_total_time, 0, 2) != '0.' || $s_query == '' || !is_array($a_return[0])) {
                    $s_last_log = $this->_create_query_log($i_total_time, $s_query, $a_return, $o_result);
                    if ($a_return[0] === false) {
                        $this->_write_log($s_last_log, dirname(dirname(__FILE__)) . '/dashboard/error_log/sql_error_log_' . date('Ymd') . '.php');
                    }
                    $this->_write_log($s_last_log);
                }
                break;
            case 5:
                if (substr($i_total_time, 0, 2) != '0.' || $s_query == '' || !is_array($a_return[0])) {
                    $this->_create_query_log($i_total_time, $s_query, $a_return, $o_result);
                    if ($a_return[0] === false) {
                        $this->_write_log($this->_s_debug_text, dirname(dirname(__FILE__)) . '/dashboard/error_log/sql_error_log_' . date('Ymd') . '.php');
                    }
                    $this->_write_log($this->_s_debug_text);
                    exit();
                }
                break;
            case 6:
                $this->_create_query_log($i_total_time, $s_query, $a_return, $o_result);
                $this->_write_log($this->_s_debug_text);
                break;
        }
    }

    /**
     * (privat)
     * writes the logfile
     * 
     * @param string $s_error_text text to write
     * @param string $s_error_file which file should be used
     */
    private function _write_log($s_error_text, $s_error_file = FALSE) {
        if ($s_error_file == false) {
            $s_error_file = dirname(dirname(dirname(__FILE__))) . '/logs/sql_logs/sql_log_' . date('Ymd') . '.php';
        } else {
            $s_error_file = dirname(dirname(dirname(__FILE__))) . '/logs/sql_logs/sql_error_log_' . date('Ymd') . '.php';
        }
        if (!file_exists($s_error_file)) {
            $s_error_text = '<?php' . PHP_EOL . '//if($_SERVER["REMOTE_ADDR"] != "37.99.200.50"){exit();}' . PHP_EOL . '?>' . PHP_EOL;
        }
        $handle = @fopen($s_error_file, "a");
        if ($handle !== false) {
            fwrite($handle, $s_error_text);
            fclose($handle);
        } else {
            library::show_debug('<!-- could not write sql-log! -->');
        }
    }

    /**
     * (private)
     * creates a generic layout for login
     * 
     * @param type $i_total_time exectime
     * @param type $s_query the query
     * @param type $a_return return value
     * @param type $o_result query result
     * @return string log text
     */
    private function _create_query_log($i_total_time, $s_query, $a_return, $o_result) {
        $s_debug_log = '';
        $b_critical = false;
        if ($a_return[0] === false) {
            $b_critical = true;
            $s_debug_log .= '<font color="red">';
        }
        $s_debug_log .= '/------------------------------------------------/<br>';
        $s_debug_log .= 'Datum: ' . date('Y-m-d H:i:s') . "<br>";
        $s_debug_log .= wordwrap(str_replace('x789723!789034w5j2f545n7N8N8735asdFdsa8Yz', 'passphrase', $s_query), 250, '<br>');
        if (substr($i_total_time, 0, 2) != '0.') {
            $b_critical = true;
            $s_debug_log .= '<font color="orange">';
        } else {
            $s_debug_log .= '<font>';
        }
        $s_debug_log .= '<br>Exec-Time: ' . $i_total_time . '</font>';
        if ($a_return[0] != FALSE) {
            if ($a_return[0] === TRUE) {
                $s_debug_log .= '<br>Aff. Rows: ' . mysql_affected_rows(@$this->_o_sql_data->o_sql_connection);
            } else {
                $s_debug_log .= '<br>Num. Rows: ' . mysql_num_rows($o_result);
            }
        } elseif ($a_return[0] === NULL) {
            $s_debug_log .= '<br>Notice: Empty Result!';
        } else {
            $s_debug_log .= '<br>Error: ' . mysql_error();
        }
        $s_debug_log .= '<br>/------------------------------------------------/<br>';
        if ($b_critical) {
            $s_debug_log .= '</font>';
        };
        $this->_s_debug_text .= $s_debug_log;
        return $s_debug_log;
    }

    /**
     * (private)
     * creates a UID for Querys
     * @params string the values is used in the hash 
     * @return type
     */
    function _create_uid($s_value = '') {
        return md5(microtime() . md5($s_value) . md5(date('siH') . md5(date('Yis')) . md5(mt_rand(0, 5698) . rand(5987, 9658))));
    }

}
