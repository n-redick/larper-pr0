<?php
//$test = new link_handler('37','/mainprojects/_start_php/modules/test_file.php');
//$array['kndnr'] = '666';
//echo $header = $test->generate_header_info($array);
//header($header);

class link_handler{
    
    private $i_user;
    private $s_page;
    private $s_date;
    private $o_secure_params_inner;
    private $o_secure_params_outer;
    private $o_mysqli;
    
    public function __construct($id = '',$page = '') {
        $this->s_date = @date('sYHmid');
        require_once 'secure_params.php';
        $this->i_user = $id;
        $this->s_page = $page;
    }
    
    public function generate_header_info($array,$short = true){
        if($this->s_page != '' && $this->i_user != ''){
            $date = $this->s_date;
            $inner_salt = $this->generate_salt($date);
            $inner_token_lenght = mt_rand(1,10);
            $inner_token_start = $this->generate_token_start($inner_salt);

            $this->o_secure_params_inner = new link_manager($inner_salt,'',$inner_token_start,$inner_token_lenght);
            $inner_param = $this->o_secure_params_inner->obfuscate_url($array);

            $token = substr(md5($inner_param.$this->i_user.$date),$inner_token_start,$inner_token_lenght);

            $this->o_secure_params_outer = new link_manager();
            $outer_array['d'] = $date;
            $outer_array['t'] = $token;
            $outer_array['u'] = $this->i_user;
            $outer_array['p'] = $inner_param;
            $url = $this->o_secure_params_outer->obfuscate_url($outer_array);
            if($short == true){
                $this->o_mysqli = new mysqli('localhost', 'root', 'root', 'verwaltung');
                $uid = md5($token.microtime());
                $db_date = substr($date,10,2).substr($date,0,2);
                if($query = $this->o_mysqli->prepare("INSERT INTO wmd_omse_link_handling (id,value,date) VALUES (?,?,?)")){
                    $query->bind_param('sss', $uid,$url,$db_date);
                    $query->execute();
                    return "location: modules/".$this->s_page."?o=".$uid;
                }else{
                    echo $this->o_mysqli->error;
                }
            }else{
                return "location: modules/".$this->s_page."?o=".$url;
            }
        }
    }
    
    public function validate_page_call($param){
        $date = $this->s_date;
        $this->o_secure_params_outer = new link_manager();
        if(strlen($param) == 35){
        $this->o_mysqli = new mysqli('localhost', 'root', 'root', 'verwaltung');
        if($query = $this->o_mysqli->prepare("SELECT value FROM wmd_omse_link_handling WHERE id=?")){
            $query->bind_param('s', $param);
            $query->execute();
            $query->bind_result($res);
            while($query->fetch()){
                $result = $res;
            }
        }else{
            echo $this->o_mysqli->error;
        }
        $outer_array = $this->o_secure_params_outer->clear_url($result);
        }else{
            $outer_array = $this->o_secure_params_outer->clear_url($param);
        }
        print_r($outer_array);
        if(substr($date,10,2).substr($date,0,2) < ((substr($outer_array['d'],10,2).substr($outer_array['d'],0,2))+15)){
            $inner_salt = $this->generate_salt($outer_array['d']);
            $inner_token_lenght = strlen($outer_array['t']);
            $inner_token_start = $this->generate_token_start($inner_salt);
            $token = substr(md5($outer_array['p'].$outer_array['u'].$outer_array['d']),$inner_token_start,$inner_token_lenght);
            if($token == $outer_array['t']){
                $this->o_secure_params_inner = new link_manager($inner_salt,'',$inner_token_start,$inner_token_lenght);
                $inner_params = $this->o_secure_params_inner->clear_url($outer_array['p']);
                return $inner_params;
            }else{
                return false;
            }
        }else{
            return false;
        }

    }
    
    private function generate_salt($string){
        return preg_replace('/[^0-9]/','',md5($string));
    }
    
    public function generate_token_start($salt){
        if($salt != ''){
            $summe = $this->quersumme($salt);
            if($summe > 9){
                $summe = $this->generate_token_start($summe);
            }
            
            return $summe;
        }
        return false;
    }
    
    private function quersumme($int){
        $summe = 0;
        for($i = 0;$i <strlen($int);$i++){
            $summe = $summe + (int)substr($int,$i,1);
        }
        return $summe;
    }
}
?>
