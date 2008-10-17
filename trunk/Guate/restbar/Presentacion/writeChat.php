<?php
require ($_SERVER['DOCUMENT_ROOT'] . '/restbar/Dominio/class_chat.php');
$chat = new chat();
$nickname=$_POST['nickname'];
if ($nickname){
	$_SESSION['pseudo']=$nickname;
	echo '"'.$nickname.'"';
}else {
    $msg=trim(strip_tags($_POST['msg']));
    $messages=$chat->escribirChat($_SESSION['pseudo'], $msg);
    $data=array('msg' => $msg, 'pseudo' => $_SESSION['pseudo']);
    if(!empty($msg))
        echo json_encode($data);
    else
        echo json_encode('');
}
?>
