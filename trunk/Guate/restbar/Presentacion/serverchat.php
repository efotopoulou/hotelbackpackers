<?php
require ($_SERVER['DOCUMENT_ROOT'] . '/restbar/Dominio/class_chat.php');

$chat = new chat();
$chatInfo = new chatInfo();
$nickname=$_POST['nickname'];
$msg=$_POST['msg'];
if ($nickname){
	$chatInfo->setPseudo($nickname);
	echo '"'.$nickname.'"';
}else if ($msg){
	$now = date('d-m-Y G:i:s');
    $msg=trim(strip_tags($_POST['msg']));
    $messages=$chat->escribirChat($chatInfo->pseudo, $msg, $now);
    $data=array('msg' => $msg, 'nickname' => $chatInfo->pseudo,'time' =>$now);
    if(!empty($msg))
        echo json_encode($data);
    else
        echo json_encode('');
}else {
    if(empty($_SESSION['last_chat_message_id'])) $_SESSION['last_chat_message_id']=0;
    $messages=$chat->leerChat($chatInfo->pseudo);
    if($messages) {
        $_SESSION['last_chat_message_id']=$messages[count($messages)-1]["id_msg"];
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