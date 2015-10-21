<?php
class module_core{
    private $o_sql_data;
    protected $s_debug_text;
    
    public function __construct($o_sql_data = null) {
        if($o_sql_data != null && $o_sql_data != false){
            $this->o_sql_data = $o_sql_data;
        }else{
            include "../../../../../htdocs_site/php/db_connect_class.php";
            $this->o_sql_data = new db_data();
        }
        $this->database_connect();
    }
    
    protected function get_timestamp(){
        $i_tmp_time = microtime(); 
        $i_tmp_time = explode(" ",$i_tmp_time);
        $i_tmp_time = $i_tmp_time[1] + $i_tmp_time[0]; 
        return $i_tmp_time;
    }
    
    protected function database_query($s_query,$b_result = true){

        $i_starttime = $this->get_timestamp();
        
        $o_result = mysql_query($s_query);
        if(!$o_result){
            $a_return[0] = FALSE;
            $a_return[1] = mysql_error($this->o_sql_data->o_sql_connection);
            $a_return[2] = mysql_errno($this->o_sql_data->o_sql_connection);
            $a_return[3] = $s_query;
        }elseif($b_result == true){
            $i_counter = 0;
            while($row = mysql_fetch_assoc($o_result)){
                $a_return[$i_counter] = $row;
                $i_counter++;
            }
        }else{
           $a_return[0] = TRUE; 
        }
        //timelog: end// 
        $i_endtime = $this->get_timestamp(); 
        $i_totaltime = ($i_endtime - $i_starttime);  
        if (substr($i_totaltime,0,2)!='0.'){
            $b_critical=true; 
            $s_debug_log .= '<font color="red">';
        }; 
        $s_debug_log .= '/------------------------------------------------/<br>';
        $s_debug_log .= wordwrap($s_query,250,'<br>');
        $s_debug_log .= '<br>Exec-Time: '.$i_totaltime;
        if($a_return[0] != FALSE){
            if($a_return[0] === TRUE){
                $s_debug_log .= '<br>Aff. Rows: '.  mysql_affected_rows($this->o_sql_data->o_sql_connection);
            }else{
                $s_debug_log .= '<br>Num. Rows: '.  mysql_num_rows($o_result);
            }
        }
        $s_debug_log .= '<br>/------------------------------------------------/<br>';
        if ($b_critical){
            $s_debug_log .= '</font>';
        };     
        //------------//
        $this->s_debug_text .= $s_debug_log;
        return $a_return;
    }
    
    private function database_connect() {
        $this->o_sql_data->o_sql_connection = mysql_connect($this->o_sql_data->wmd_sql_server,$this->o_sql_data->wmd_sql_user,$this->o_sql_data->wmd_sql_pw);
	mysql_select_db($this->o_sql_data->wmd_sql_db);  
    }
}
?>
