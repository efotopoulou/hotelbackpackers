<?php
require_once ('ComunicationRestBar.php');

class chat{
 
	const LEERCHAT= 'select pseudo as nickname, msg, time, id_msg from (select pseudo, msg, time,id_msg from chat_message where pseudo!=? and id_msg>? order by id_msg desc limit 50) as messages order by messages.id_msg';
	const ESCRIBIRCHAT= 'INSERT INTO chat_message VALUES (?,?, 0, ?)';
	const ELIMINARCHAT= 'DELETE FROM chat_message WHERE 1=1';
	
		function leerChat($pseudo,$lastchat){
		$comunication = new ComunicationRestBar();
		$PARAMS = array($pseudo, $lastchat);
		//echo $lastchat;
		$PARAMS_TYPES = array (ComunicationRestBar::$TSTRING, ComunicationRestBar::$TINT);
		$result = $comunication->query(self::LEERCHAT,$PARAMS,$PARAMS_TYPES);
	    if($result->getRecordCount()>0 ) {
	    	$i=0;
		    while($result->next()){
				$resultc=$result->getRow();
				$a[$i]=array("nickname"=>$resultc["nickname"], "msg"=>$resultc["msg"],"time"=>$resultc["time"],"id_msg"=>$resultc["id_msg"]);
				$i++;
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
		function delete_chat(){
		$comunication = new ComunicationRestBar();
		$PARAMS = array();
		$PARAMS_TYPES = array ();
		$result = $comunication->query(self::ELIMINARCHAT,$PARAMS,$PARAMS_TYPES);
		
		return $result;
		}


}
?>
