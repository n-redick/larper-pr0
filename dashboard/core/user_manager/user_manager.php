<?php

#################################################
# external-oms // core // User Management
#################################################
# User Rights Management
#################################################  
# @author Ni.Re 
# @copyright Copyright 2014 WIRmachenDRUCK GmbH
# @license commercial 
#################################################
#
#################################################
# VERSION 1.0 Build 140322
#################################################
#  added documentation
#  --
#  clean up
#  --
#  added config values
#  --
#  Release
#################################################

require_once (dirname(dirname(dirname(__FILE__))).'/lib/wmd_core.php');
class user_manager extends wmd_core {
    
    /**
    * (private) Holds the view-class
    * @see _order_handler 
    */
    private $_o_view;
    
    private $i_elements_per_page = 32;
    
    private $_b_allow_api = false;
    
    public function __construct($i_error_level = NULL,$b_api_call = false) {
        parent::__construct($i_error_level);
        if($b_api_call == false){
            $this->_handler();
        }else{
            $this->_b_allow_api = true;
        }
    }
    
    public function _api_handler($a_params){
        if($this->_b_allow_api === false){
		print "no api";
            return false;
        }
        switch($a_params['type']){
            case 'change_rights' :
                $this->_update_rights($a_params['user_id'],$a_params['perm_id'],$a_params['status']);
                break;
        }
    }
    
    private function _update_rights($s_user_id,$s_perm_id,$b_status){
        if($b_status == 0){
            $s_query = "INSERT INTO [table_pre]_user_permissions (perm_id,user_id) VALUES ('".$s_perm_id."','".$s_user_id."')";
            $a_result = wmd_global::database_query($s_query);
            if($a_result[0] !== false){
                print '1';
            }else{
		print 'err';
	    }
        }else{
            $s_query = "DELETE FROM [table_pre]_user_permissions WHERE perm_id = '".$s_perm_id."' AND user_id = '".$s_user_id."'";
            $a_result = wmd_global::database_query($s_query);
            if($a_result[0] !== false){
                print '1';
            }else{
		print 'err';
	    }
        }
        $s_query = "INSERT INTO [table_pre]_refresh_rights_heap (user_id,need_update) VALUES ('".$s_user_id."','1') ON DUPLICATE KEY UPDATE need_update = '1'";
        wmd_global::database_query($s_query,true,'dev');            
    }
    
    private function _handler(){
        if (isset($_GET['a'])) {
            $s_task = $_GET['a'];
        } else {
            $s_task = '';
        }
        switch($s_task){
            case 'user' :
                $this->_get_user();
                break;
            case 'lieferanten' :
                $this->_get_lieferanten();
                break;
            case 'reseller' :
                $this->_get_reseller();
                break;
            case 'all' :
                $this->_get_all();
                break;
            case 'show_user' :
                $this->_get_single_user($_GET['t']);
                break;
            default :
                $this->_get_overview();
                break;
        }
    }
    
    private function _get_single_user($i_id){
        $s_query = 'SELECT username,id,reseller__id,lieferanten__id,cdate,email_eil_sachbearbeiter FROM adminbenutzer where id = '.$i_id;
        $a_result = wmd_global::database_query($s_query,true,'live');
        $this->_display('user',$this->_get_role_and_permissions($a_result));
    }
    
    private function _get_overview(){
        $this->_display('overview',$this->_get_entries());    
    }
    
    private function _get_entries(){
        $a_entries['reseller'] = $this->_get_entries_reseller();
        $a_entries['worker'] = $this->_get_entries_worker();
        $a_entries['factories'] = $this->_get_entries_factories();
        $a_entries['all'] = $this->_get_entries_all();
        return $a_entries;
    }
    
    private function _get_entries_reseller(){
        $s_query = "SELECT count(id) as counter FROM adminbenutzer WHERE reseller__id != 0";
        $a_result = wmd_global::database_query($s_query,true,'live');
        if($a_result[0] === FALSE){
            return '369';
        }
        if($a_result[0] == NULL){
            return '0';
        }
        return $a_result[0]['counter'];
    }
    
    private function _get_entries_factories(){
        $s_query = 'SELECT count(a.id) as counter FROM adminbenutzer as a, lieferanten_dienstleister_extern_referenz as b WHERE a.lieferanten__id = b.lieferanten__id AND a.username REGEXP "^Abteilung-[0-9]+"';
        $a_result = wmd_global::database_query($s_query,true,'live');
        if($a_result[0] === FALSE){
            return '369';
        }
        if($a_result[0] == NULL){
            return '0';
        }
        return $a_result[0]['counter'];    
    }
    
    private function _get_entries_worker(){
        $s_query = "SELECT count(id) as counter FROM adminbenutzer WHERE reseller__id = 0 AND lieferanten__id = 0";
        $a_result = wmd_global::database_query($s_query,true,'live');
        if($a_result[0] === FALSE){
            return '369';
        }
        if($a_result[0] == NULL){
            return '0';
        }
        return $a_result[0]['counter'];    
    }
    
    private function _get_entries_all(){
        $s_query = "SELECT count(id) as counter FROM adminbenutzer";
        $a_result = wmd_global::database_query($s_query,true,'live');
        if($a_result[0] === FALSE){
            return '369';
        }
        if($a_result[0] == NULL){
            return '0';
        }
        return $a_result[0]['counter'];    
    }
    
    private function _get_role_and_permissions($a_users){
        $a_user_element = array();
        $s_sub_query = '';
        foreach($a_users as $a_user){
            if(preg_match('/^Abteilung-[0-9]+/',$a_user['username']) == 1){
                $a_user_element[$a_user['id']]['role'] = 'Lieferant';
            }elseif($a_user['lieferanten__id'] == 0 && $a_user['reseller__id'] == 0){
                $a_user_element[$a_user['id']]['role'] = 'Mitarbeiter';
            }elseif($a_user['reseller__id'] != 0){
                $a_user_element[$a_user['id']]['role'] = 'Reseller';
            }else{
                $a_user_element[$a_user['id']]['role'] = 'Unbekannt';
            }
            $s_sub_query .= "'".$a_user['id']."',";
            $a_user_element[$a_user['id']]['username'] = $a_user['username'];
            foreach($a_user as $key => $value){
                $a_user_element[$a_user['id']][$key] = $value;
            }
        }
        $s_query = "SELECT a.name,b.user_id FROM [table_pre]_permissions as a, [table_pre]_user_permissions as b WHERE a.id = b.perm_id AND b.user_id IN (".substr($s_sub_query,0,-1).") ORDER BY a.module_id";
        $a_result = wmd_global::database_query($s_query);
        foreach($a_result as $a_row){
            $a_user_element[$a_row['user_id']]['rights'][count($a_user_element[$a_row['user_id']]['rights'])] = $a_row['name']; 
        }
        return $a_user_element;
    }
    
    private function _get_all(){
        $s_query = 'SELECT username,id,reseller__id,lieferanten__id FROM adminbenutzer as a ORDER BY username limit '.$this->_create_pagination_limit($_GET['o'],$this->i_elements_per_page);
        $a_return = wmd_global::database_query($s_query,true,'live');
        $this->_display('list',$this->_get_role_and_permissions($a_return),$this->_get_entries_all());
    }
    
    private function _get_user(){
        $s_query = 'SELECT a.username,a.id,reseller__id,lieferanten__id FROM adminbenutzer as a WHERE reseller__id = 0 AND lieferanten__id = 0 ORDER BY username limit '.$this->_create_pagination_limit($_GET['o'],$this->i_elements_per_page);
        $a_return = wmd_global::database_query($s_query,true,'live');
        $this->_display('list',$this->_get_role_and_permissions($a_return),$this->_get_entries_worker());
    }
    
    private function _get_lieferanten(){
        if(isset($_GET['o'])){
            $i_page = $_GET['o'];
        }else{
            $i_page = 1;
        }
        $s_query = 'SELECT a.username,a.id,b.dienstleister_extern__id,a.reseller__id,a.lieferanten__id FROM adminbenutzer as a, lieferanten_dienstleister_extern_referenz as b WHERE a.lieferanten__id = b.lieferanten__id AND a.username REGEXP "^Abteilung-[0-9]+" ORDER BY username limit '.$this->_create_pagination_limit($i_page,$this->i_elements_per_page);
        $a_return = wmd_global::database_query($s_query,true,'live');
        $this->_display('list',$this->_get_role_and_permissions($a_return),$this->_get_entries_factories());
    }
    
    private function _get_reseller(){
        $s_query = 'SELECT a.username,a.id,reseller__id,lieferanten__id FROM adminbenutzer as a WHERE reseller__id != 0 ORDER BY username limit '.$this->_create_pagination_limit($_GET['o'],$this->i_elements_per_page);
        $a_return = wmd_global::database_query($s_query,true,'live');
        $this->_display('list',$this->_get_role_and_permissions($a_return),$this->_get_entries_reseller());
    }
        
    private function _display($s_type,$a_entries = NULL,$i_entry_counter = NULL){
        require_once (dirname(__FILE__).'/view/view_user_manager.php');
        $this->_o_view = new view_user_manager();
        echo $this->_o_view->create_output($s_type,$a_entries,$i_entry_counter,$this->i_elements_per_page);
    }
    
}
