<?php
class database_handler {
    private $o_pdo;
    
    public function __construct() {
        try {
            $this->o_pdo = new PDO('mysql:host=localhost;dbname=test', 'root','root');
            $this->o_pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $e) {
            echo 'ERROR: ' . $e->getMessage();
        }
    }
    
    public function get_query($s_query, $a_parameter = NULL){
        if($a_parameter == NULL || !is_array($a_parameter) || strpos($s_query,':') === false){
            //use normal query
            echo "!";
            $a_result = $this->o_pdo->query($s_query,PDO::FETCH_ASSOC);
            
        }else{
            //use Prep statement
            $o_prep = $this->o_pdo->prepare($s_query);
            $o_prep->execute($a_parameter);
            # get array of results
            $a_result = $o_prep->fetchALL(PDO::FETCH_ASSOC);
        }
 
        # One or more rows
        if ( count($a_result) ) {
            $a_return = array();
            foreach($a_result as $a_row) {
                $i_int = count($a_return);
                if(is_array($a_row)){
                    foreach($a_row as $s_id=>$s_element){
                        if(!is_array($s_element)){
                            $a_return[$i_int][$s_id] = $s_element;
                        }else{
                            //error strange result
                            return false;
                        }
                    }
                }else{
                    //error strange result
                    return false;
                }
            }
            return $a_return;
        }else{
            //error -> zero results
            return false;
        }
        
    }
}
?>
