<?php
require ($_SERVER['DOCUMENT_ROOT'] . '/restbar/Dominio/class_chat.php');
$chat = new chat();
$chatInfo = new chatInfo();
$nickname=$_POST['nickname'];
if ($nickname){
	$chatInfo->pseudo=$nickname;
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
}
?>