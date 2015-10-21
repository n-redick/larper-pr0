<?php
// created by N.Redick
// Jan. 2013
class link_manager
{   
    private $salt;
    private $switch;
    private $sst_start;
    private $sst_lenght;
    private $splitter;
    private $hashing_method;
    
    function switch_char($str,$reverse = 0)
    {
        if($reverse == 1)
        {
            $c = 0;
            $a_tmp = array();
            for($i = 0;$i < count($this->switch);$i++)
            {
                foreach($this->switch[count($this->switch)-$i-1] as $key => $value)
                {
                    $a_tmp[$i][$key] = $value;
                }
            }
        }
        else
        {
            $a_tmp = $this->switch;
        }
        foreach($a_tmp as $element)
        {
            foreach($element as $key => $value)
            {
                if(strlen($str) > $key && strlen($str) > $value)
                {
                    $a_str = array();
                    for($i = 0;$i < strlen($str);$i++)
                    {
                        $a_str[$i] = substr($str,$i,1);
                    }
                    if($reverse == 0)
                    {
                        $tmp = $a_str[$key];
                        $a_str[$key] = $a_str[$value];
                        $a_str[$value] = $tmp;
                    }
                    else
                    {
                        $tmp = $a_str[$value];
                        $a_str[$value] = $a_str[$key];
                        $a_str[$key] = $tmp;
                    }
                }
                $str = '';
                foreach($a_str as $char)
                {
                    $str .= $char;
                }
            }
        }
        if($reverse == 0)
        {
            return str_replace('=','_',$str);
        }
        else
        {
            return str_replace('_','=',$str);
        }            
    }
    
    
    public function __construct($s_salt = '+*23Ada4vI[%da',$a_switch = '',$s_sst_start = 4,$s_sst_lenght = 4,$s_splitter = '|',$s_hashtype = 'md5') {
        $this->salt = $s_salt;
        if($a_switch == '')
        {
            $a_switch[0][0] = 1;
            $a_switch[1][5] = 6;
            $a_switch[2][1] = 3;
            $a_switch[3][9] = 5;
            $a_switch[4][15] = 3;
            $a_switch[5][2] = 3;
            $a_switch[6][6] = 5;
            $a_switch[7][4] = 2;
            $a_switch[8][7] = 2;
            $a_switch[9][4] = 2;
            $a_switch[10][11] = 8;
            $a_switch[11][12] = 11;
            $a_switch[12][10] = 9;
            
        }
        $this->switch = $a_switch;
        $this->sst_start = $s_sst_start;
        $this->sst_lenght = $s_sst_lenght;
        $this->splitter = $s_splitter;
        $this->hashing_method = $s_hashtype;
    }
    
    function obfuscate_url($array,$page = '')
    {
        $link = '';
        foreach($array as $key => $value)
        {
            switch($this->hashing_method)
            {
                case 'md5' :
                    $secure_token = md5($key.$this->splitter.$value);
                    $salted_secure_token = substr(md5($this->salt.$secure_token),$this->sst_start,$this->sst_lenght).substr(md5($this->sst_start.'md5'.$this->salt),$this->sst_lenght,2);
                    break;
            }
            $param = $this->switch_char(base64_encode($key.$this->splitter.$value.$this->splitter.$salted_secure_token));
            if($link == '')
            {
                $link .=$page.''.$param; 
            }
            else
            {
                $link .= '|'.$param; 
            }
        }
        return $link;
    }
    
    function clear_url($url)
    {
        if($url != '')
        {
            $a_param = explode('|', $url);
            $error = 0;
            $return = array();
            foreach($a_param as $element)
            {
                $decoded = base64_decode($this->switch_char($element,1));
                $a_decoded_elements = explode('|',$decoded);
                if(count($a_decoded_elements == 3))
                {
                    if(strlen($a_decoded_elements[2]) == $this->sst_lenght+2)
                    {
                        switch(substr($a_decoded_elements[2],-2))
                        {
                            case substr(md5($this->sst_start.'md5'.$this->salt),$this->sst_lenght,2) :
                                $secure_token = md5($a_decoded_elements[0].$this->splitter.$a_decoded_elements[1]);
                                $salted_secure_token = substr(md5($this->salt.$secure_token),$this->sst_start,$this->sst_lenght);
                                if($salted_secure_token != substr($a_decoded_elements[2],0,-2))
                                {
                                    $error = 1;
                                }
                                else
                                {
                                    $return[$a_decoded_elements[0]] = $a_decoded_elements[1];
                                }
                                break;
                        }
                    }
                }    
            }
        }
        else
        {
            $error = 1;
        }
        if($error == 1)
        {
            $return = array();
            return FALSE;
        }
        else
        {
            return $return;
        }
    }
    
    function crypt_url($array,$string = NULL ,$iteration = NULL){
        if($string == NULL || strlen != 22){
            $string = 'SHixLwMZlCgJruAVRhhsfC';
        }
        if($iteration == NULL || $iteration < 10 || $iteration > 31){
            $string = "14";
        }
        
    }
}
$obj = new link_manager();
$array['ct'] = '6';
echo $obj->obfuscate_url($array);
?>
