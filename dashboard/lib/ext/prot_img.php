<?php
/**
* Obfuscates Image-Path
* Every internal function(private) start with an underscore 
* at the end of the class are public debug functions.
* The names of the function are the same as the functions they should test
* just without the underscore!
* 
* @author Ni.Re 
* @copyright Copyright 2013
* @license MIT 
*/
class image_handler
{
    /**
    * (private) Path to imagefolder root
    * @see __construct() 
    */
    private $_s_img_path;
    
    /**
    * (private) Path to thumbnail root
    * @see __construct() 
    */
    private $_s_thumb_path;
    
    /**
    * (private) Maximum Thubnail witdh
    * @see __construct() 
    */
    private $_i_thumb_width;
    
    private $_o_error_handler;
    
    public function __construct($s_image_path,$s_thumb_path,$i_thumb_width,$o_error_handler = null) {
        if($o_sql_data != null && $o_sql_data != false){
            $this->o_sql_data = $o_sql_data;
        }else{
            include "../../../../../htdocs_site/php/db_connect_class.php";
            $this->o_sql_data = new db_data();
        }
        $this->database_connect();
    }
    
    private function _get_type($i_extension){
            switch($i_extension){
            case 1 : 
                $a_return['extension'] = "jpg";
                $a_return['type'] = 'image/jpeg';
                break;
            case 2 : 
                $a_return['extension'] = "gif";
                $a_return['type'] = 'image/gif';
                break;
            case 3 :
                $a_return['extension'] = "png";
                $a_return['type'] = 'image/png';
                break;
            default :
                //error
                return false;
            }
            return $a_return;
        }
    public function create_thumbnail($filename){
        
    }
        
    public function display_image_path($i_extension = false,$s_filename = false)
    {
        //see if all values are set, and if we can solve the extension ID
        if ($i_extension != false && $s_filename != false && $a_imgdata = $this->_get_type($i_extension) != false) {
            //build filepath and filename and see if it exists, and if, display it
            if (file_exists($this->_s_img_path . $s_filename . "." . $a_imgdata['extension'])) {
                header("Content-Type: ".$a_imgdata['type']);
                readfile($this->_s_img_path . $s_filename . "." . $a_imgdata['extension']);
            }     
        }
    }
}
$image_prot = new protect_image_path;
$image_prot->display_image($_GET['type'], $_GET['fl']);
?>
