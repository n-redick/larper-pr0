<?php

class tracking_library {
    private $s_ipadress;
    private $s_referrer;
    private $s_datetime;
    private $s_useragent;
    private $s_sessionhash;
    
    public function track_user(){
        $this->_get_data();
        $this->_set_session();
        $this->_write_sql();
    }

    private function _get_data() {
        $this->s_ipadress = $_SERVER['REMOTE_ADDR'];
        $this->s_referrer = ''; //$_SERVER['HTTP_REFERER'];
        $this->s_datetime = time();
        $this->s_useragent = $_SERVER['HTTP_USER_AGENT'];
        $this->s_sessionhash = md5($this->s_ipadress.$this->s_referrer.$this->s_datetime.$this->s_useragent);
    }
    
    private function _set_session(){
        $_SESSION['tracker'] = $this->s_sessionhash;
    }
    
    private function _write_sql(){
    $s_query = 'INSERT INTO `[table_pre]_lychas_tracker` (`ip_address`, `session_hash`, `datetime`) VALUES ("'.$this->s_ipadress.'", "'.$this->s_sessionhash.'", NOW());';
    library::update_query($s_query);
    }

}
