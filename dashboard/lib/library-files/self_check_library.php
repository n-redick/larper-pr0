<?php

#################################################
# external-oms // self_check (installer)
#################################################
# installes the missing Databases
#################################################  
# @author Nico Redick 
# @copyright Copyright 2015 Nico Redick
# @license open source
#################################################
#################################################
# VERSION 1.0 Build 150908
#################################################
# added
#################################################

class self_check_library {
    
    public function __construct() {
        $this->_check_installed_modules();
        $this->_check_page_view_cache();
        $this->_check_permissions();
        $this->_check_refresh_rights();
        $this->_check_user_permissions();
    }

    private function _check_installed_modules() {
        $s_query = "SELECT * FROM `" . library::get_config_value('s_table_prefix') . "_lychas_installed_modules` LIMIT 1";
        $o_result = library::select_query($s_query);
        if ($o_result === false) {
            $this->_create_installed_modules();
        }
    }

    private function _check_page_view_cache() {
        $s_query = "SELECT * FROM `" . library::get_config_value('s_table_prefix') . "_lychas_page_view_cache_heap` LIMIT 1";
        $o_result = library::select_query($s_query);
        if ($o_result === false) {
            $this->_create_page_view_cache();
        }
    }

    private function _check_permissions() {
        $s_query = "SELECT * FROM `" . library::get_config_value('s_table_prefix') . "_lychas_permissions` LIMIT 1";
        $o_result = library::select_query($s_query);
        if ($o_result === false) {
            $this->_create_permissions();
        }
    }

    private function _check_user_permissions() {
        $s_query = "SELECT * FROM `" . library::get_config_value('s_table_prefix') . "_lychas_user_permissions` LIMIT 1";
        $o_result = library::select_query($s_query);
        if ($o_result === false) {
            $this->_create_user_permissions();
        }
    }
    
    private function _check_refresh_rights() {
        $s_query = "SELECT * FROM `" . library::get_config_value('s_table_prefix') . "_lychas_refresh_rights_heap` LIMIT 1";
        $o_result = library::select_query($s_query);
        if ($o_result === false) {
            $this->_create_refresh_rights();
        }
    }

    private function _create_installed_modules() {
        $s_query = "CREATE TABLE IF NOT EXISTS `" . library::get_config_value('s_table_prefix') . "_lychas_installed_modules` (
                    `id` int(11) NOT NULL AUTO_INCREMENT,
                    `name` varchar(150) NOT NULL,
                    `button_label` varchar(50) NOT NULL,
                    `description` text NOT NULL,
                    `active` tinyint(1) NOT NULL,
                    `path` varchar(350) NOT NULL,
                    `core_module` tinyint(4) NOT NULL DEFAULT '0',
                    PRIMARY KEY (`id`),
                    UNIQUE KEY `name` (`name`),
                    KEY `active` (`active`)
                    ) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;";
        library::update_query($s_query);
    }

    private function _create_page_view_cache() {
        $s_query = "CREATE TABLE IF NOT EXISTS `" . library::get_config_value('s_table_prefix') . "_lychas_page_view_cache_heap` (
                    `filenamehash` varchar(32) NOT NULL,
                    `checksum` varchar(32) NOT NULL,
                    PRIMARY KEY (`filenamehash`)
                    ) ENGINE=MEMORY DEFAULT CHARSET=utf8;";
        library::update_query($s_query);
    }

    private function _create_permissions() {
        $s_query = "CREATE TABLE IF NOT EXISTS `" . library::get_config_value('s_table_prefix') . "_lychas_permissions` (
                    `id` int(11) NOT NULL AUTO_INCREMENT,
                    `name` varchar(200) NOT NULL,
                    `description` text NOT NULL,
                    `parameter` varchar(50) NOT NULL,
                    `module_id` int(11) NOT NULL,
                    `icon` varchar(10) NOT NULL,
                    PRIMARY KEY (`id`),
                    KEY `module_id` (`module_id`)
                    ) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;";
        library::update_query($s_query);
    }

    private function _create_refresh_rights() {
        $s_query = "CREATE TABLE IF NOT EXISTS `" . library::get_config_value('s_table_prefix') . "_lychas_refresh_rights_heap` (
                    `user_id` int(10) NOT NULL,
                    `need_update` tinyint(1) NOT NULL,
                    PRIMARY KEY (`user_id`)
                    ) ENGINE=MEMORY DEFAULT CHARSET=utf8;";
        library::update_query($s_query);
    }

    private function _create_user_permissions() {
        $s_query = "CREATE TABLE IF NOT EXISTS `" . library::get_config_value('s_table_prefix') . "_lychas_user_permissions` (
                    `perm_id` int(7) NOT NULL,
                    `user_id` int(7) NOT NULL,
                    PRIMARY KEY (`perm_id`,`user_id`)
                    ) ENGINE=InnoDB DEFAULT CHARSET=utf8;";
        library::update_query($s_query);
    }

}
