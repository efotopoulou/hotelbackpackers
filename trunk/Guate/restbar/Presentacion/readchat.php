<?php
require ($_SERVER['DOCUMENT_ROOT'] . '/restbar/Dominio/class_chat.php');

$chat = new chat();
$nickname=$_POST['nickname'];
$msg=$_POST['msg'];
if ($nickname){
	$_SESSION['pseudo']=$nickname;
	echo '"'.$nickname.'"';
}else if ($msg){
    $msg=trim(strip_tags($_POST['msg']));
    $messages=$chat->escribirChat($_SESSION['pseudo'], $msg);
    $data=array('msg' => $msg, 'pseudo' => $_SESSION['pseudo']);
    if(!empty($msg))
        echo json_encode($data);
    else
        echo json_encode('');
}else {
    if(empty($_SESSION['last_chat_message_id'])) $_SESSION['last_chat_message_id']=0;
    $chat = new chat();
    $messages=$chat->leerChat($_SESSION['pseudo']);
    if($messages->getRecordCount()>0 ) {
        $tab_messages=$messages->result_array();
        $_SESSION['last_chat_message_id']=$tab_messages[count($tab_messages)-1]->id_msg;
        echo json_encode($tab_messages);
    } else echo json_encode('');
}
?>
<?php
class chatInfo{
  public $pseudo;
}
?>
