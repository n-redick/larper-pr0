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

class database_library {

    /**
     * (private)
     * contains the mysql object
     * @var object 
     */
    private $_o_mysql = NULL;
    public function __construct() {
        $this->create_database_object();
    }
    /**
     * (public)(static) 
     * calls the DB handle query function
     * 
     * @param string $s_query the query
     * @param boolean $b_result should there be a return value?
     * @param string $s_database database to call
     * @return array
     */
    public function database_query($s_query, $b_result, $s_database) {
        if ($this->_o_mysql == NULL) {
            $this->create_database_object();
        }
        if ($s_database == NULL) {
            $s_database = library::get_config_value('s_main_database');
        }
        return $this->_o_mysql->database_query($this->_replace_token($s_query), $b_result, $s_database, library::get_config_value('i_error_level'));
    }

    private function _replace_token($s_query){
        $a_token = library::get_config_value('a_sql_token');
        foreach($a_token as $s_replace => $s_value){
            $s_query = str_replace($s_replace, library::get_config_value($s_value), $s_query);
        }
        return $s_query;
    }
    
    /**
     * (public)(static)
     * creates a new database object and initialize it
     * @param string $s_database Database Connection Name
     * @param string $s_engine Database Engine
     */
    public function create_database_object() {
        switch (strtolower(library::get_config_value('s_db_engine'))) {
            case 'mysql' :
                require_once dirname(__FILE__).'/database_connectors/connector_mysql.php';
                $this->_o_mysql = new connector_mysql(library::get_config_value('s_main_database'));
                break;
            //default is mysql
            default :
                require_once dirname(__FILE__).'/database_connectors/connector_mysql.php';
                $this->_o_mysql = new connector_mysql(library::get_config_value('s_main_database'));
                break;
        }
    }
}
    