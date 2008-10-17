<?php
require ($_SERVER['DOCUMENT_ROOT'] . '/restbar/Dominio/class_chat.php');

    if(empty($_SESSION['last_chat_message_id'])) $_SESSION['last_chat_message_id']=0;
    $chat = new chat();
    $messages=$chat->leerChat($_SESSION['pseudo']);
    if($messages->getRecordCount()>0 ) {
        $tab_messages=$messages->result_array();
        $_SESSION['last_chat_message_id']=$tab_messages[count($tab_messages)-1]->id_msg;
        echo json_encode($tab_messages);
    } else echo json_encode('');
?>