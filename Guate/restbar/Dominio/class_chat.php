<?php
require_once ('ComunicationRestBar.php');

class chat{
 
	const LEERCHAT= 'select pseudo as nickname, msg, time, id_msg from (select pseudo, msg, time,id_msg from chat_message where pseudo!=? and id_msg>? order by id_msg desc limit 50) as messages order by messages.id_msg';
	const ESCRIBIRCHAT= 'INSERT INTO chat_message VALUES (?,?, 0, ?)';
	
		function leerChat($pseudo){
		$comunication = new ComunicationRestBar();
		$PARAMS = array($pseudo, $_SESSION['last_chat_message_id']);
		$PARAMS_TYPES = array (ComunicationRestBar::$TSTRING, ComunicationRestBar::$TINT);
		$result = $comunication->query(self::LEERCHAT,$PARAMS,$PARAMS_TYPES);
	    if($result->getRecordCount()>0 ) {
	    	$i=0;
		    while($result->next()){
				$resultc=$result->getRow();
				$a[$i]=array("nickname"=>$resultc["nickname"], "msg"=>$resultc["msg"],"time"=>$resultc["time"],"id_msg"=>$resultc["id_msg"]);
		    }
		 }		
		return $a;
		}
		function escribirChat($pseudo, $msg, $time){
		$comunication = new ComunicationRestBar();
		$PARAMS = array($pseudo, $msg, $time);
		$PARAMS_TYPES = array (ComunicationRestBar::$TSTRING, ComunicationRestBar::$TSTRING, ComunicationRestBar::$TSTRING);
		$result = $comunication->query(self::ESCRIBIRCHAT,$PARAMS,$PARAMS_TYPES);
		
		return $result;
		}

}
?>
