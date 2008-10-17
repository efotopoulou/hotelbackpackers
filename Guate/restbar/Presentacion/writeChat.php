<?php
require ($_SERVER['DOCUMENT_ROOT'] . '/restbar/Dominio/class_chat.php');
$chat = new chat();
$chatInfo = new chatInfo();
$nickname=$_POST['nickname'];
if ($nickname){
	$chatInfo->setPseudo($nickname);
	echo '"'.$nickname.'"';
}else {
    $msg=trim(strip_tags($_POST['msg']));
    $messages=$chat->escribirChat($chatInfo->pseudo, $msg);
    $data=array('msg' => $msg, 'pseudo' => $chatInfo->pseudo);
    if(!empty($msg))
        echo json_encode($data);
    else
        echo json_encode('');
}
?>
<?php
class chatInfo{
  public $pseudo;
   	public function __construct (){
   		session_start ();
		ini_set("session.gc_maxlifetime", "18000");	//5 horas
		$this->pseudo=$_SESSION['pseudo'];
   	}
   	public function setPseudo($nick){
   		$_SESSION['pseudo']=$nick;
   		$this->pseudo=$nick;
   	}
}
?>
