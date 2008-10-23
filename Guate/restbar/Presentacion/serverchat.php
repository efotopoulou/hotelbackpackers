<?php
require ($_SERVER['DOCUMENT_ROOT'] . '/restbar/Dominio/class_chat.php');
require ($_SERVER['DOCUMENT_ROOT'] . '/recepcion/Dominio/MensajeJSON.php');
$chat = new chat();
$mensaje = new MensajeJSON();

$serv=$_POST['serv'];
$nick=$_POST['nick'];
$msg=$_POST['msg'];
$lastchatid=$_POST['lastchatid'];

switch ($serv) {
 case 1:
  //READ MESSAGES
  if(empty($lastchatid)) $lastchatid=0;
    $messages=$chat->leerChat($nick,$lastchatid);
    if($messages) {
        $response["lastchatid"]=$messages[count($messages)-1]["id_msg"];
        $response["Mensajes"]=$messages;
    }
  break;
  case 2:
    //WRITE MESSAGES  
	$now = date('G:i:s');
    $msg=trim(strip_tags($msg));
    $messages=$chat->escribirChat($nick, $msg, $now);
    $data=array('msg' => $msg, 'nickname' => $nick,'time' =>$now);
    $response["Mensajes"]=$data;
    break;
 }
$mensaje->setDatos($response);
echo($mensaje->encode());

/*
//WRITE MESSAGE
if ($msg){
	$now = date('G:i:s');
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
    } 
}*/
?>
