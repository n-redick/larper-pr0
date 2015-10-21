<?
class mysqli_handler
{
    public $o_mysqli;
    public function __construct($database,$host = '192.168.92.150',$user = 'usr_k988_ds2',$password = '4m3avCuH') {
        $this->mysqli = new mysqli($host, $user, $password, $database);
    }
}
?>