<?php
	require_once 'functions.php';
	$data=[];
	$requestID=$_REQUEST["id"];
	$sessionID=$_SESSION["ID"];
	$fx=$_REQUEST["fx"];

	if ($fx=="follow") {
		$QUERY=executeQuery("insert into utenti_seguono_utenti (FK_UTENTE, FK_UTENTE_SEGUITO) VALUES ($sessionID, $requestID)");
		$data['message']="FOLLOW";
	} else {
		$QUERY=executeQuery("delete from utenti_seguono_utenti where FK_UTENTE=$sessionID and FK_UTENTE_SEGUITO=$requestID");
		$data['message']="UNFOLLOW";	
	}
	$QUERY=executeQuery("select * FROM utenti_seguono_utenti where FK_UTENTE=".$sessionID);
	$data['userFollowingNumber'] = $QUERY->num_rows;
	$QUERY=executeQuery("select * FROM utenti_seguono_utenti where FK_UTENTE_SEGUITO=".$sessionID);
	$data['userFollowersNumber'] = $QUERY->num_rows;
	$data['sessionID'] = $sessionID;
	$QUERY=executeQuery("select * FROM utenti_seguono_utenti where FK_UTENTE=".$requestID);
	$data['usersFollowingNumber'] = $QUERY->num_rows;
	$QUERY=executeQuery("select * FROM utenti_seguono_utenti where FK_UTENTE_SEGUITO=".$requestID);
	$data['usersFollowersNumber'] = $QUERY->num_rows;
	$data['requestID'] = $requestID;
	echo json_encode($data);
	exit();
?>