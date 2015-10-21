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

class global_variables_library {
    
        /**
     * (private)
     * array contains global variables
     * @var array 
     */
    private $_a_global_variables;
    
    /**
     * (public)(static)
     * gives back the value of a variable
     * @param string $s_name variable name
     * @return undefined Value of the Variable
     */
    public function get_value($s_name){   
        if(isset($this->_a_global_variables[$s_name])){
            return $this->_a_global_variables[$s_name];
        }else{
            return NULL;
        }
    }
    
    /**
     * (public)(static)
     * writes value into a variable
     * @param string $s_name name of the variable
     * @param undefined $u_value value of the variable
     */
    public function set_value($s_name,$u_value){
        $this->_a_global_variables[$s_name] = $u_value;
    }
    
    
}
