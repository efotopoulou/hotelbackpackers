<?php
require ($_SERVER['DOCUMENT_ROOT'] . '/restbar/Dominio/class_chat.php');
session_start ();
ini_set("session.gc_maxlifetime", "18000");	//5 horas
$chat = new chat();
//ob_start();
//$chatInfo = new chatInfo();
//ob_end_clean();
$nickname=$_POST['nickname'];
$msg=$_POST['msg'];
//NICKNAME
if ($nickname){
	//$chatInfo->setPseudo($nickname);
	$_SESSION['pseudo']=$nickname;
	echo '"'.$_SESSION['pseudo'].'"';
//WRITE MESSAGE
}else if ($msg){
	$now = date('d-m-Y G:i:s');
    $msg=trim(strip_tags($_POST['msg']));
    $messages=$chat->escribirChat($_SESSION['pseudo'], $msg, $now);
    $data=array('msg' => $msg, 'nickname' => $_SESSION['pseudo'],'time' =>$now);
    if(!empty($msg))
        echo json_encode($data);
    else
        echo json_encode('');
//READ MESSAGE
}else {
    if(empty($_SESSION['last_chat_message_id'])) $_SESSION['last_chat_message_id']=0;
    $messages=$chat->leerChat($_SESSION['pseudo'],$_SESSION['last_chat_message_id']);
    if($messages) {
    	$last=$_SESSION['last_chat_message_id'];
        $_SESSION['last_chat_message_id']=$messages[count($messages)-1]["id_msg"];
        //echo $_SESSION['last_chat_message_id'];
        //$messages["lastchat"]=$last;
        echo json_encode($messages);
    } else echo json_encode('');
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