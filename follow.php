<?php
	require_once 'functions.php';
	$data=[];
	if ($_REQUEST["fx"]=="follow") {
		$QUERY=executeQuery("insert into utenti_seguono_utenti (FK_UTENTE, FK_UTENTE_SEGUITO) VALUES ($_SESSION[ID], $_REQUEST[id])");
		$data['message']="FOLLOW";
	} else {
		$QUERY=executeQuery("delete from utenti_seguono_utenti where FK_UTENTE=$_SESSION[ID] and FK_UTENTE_SEGUITO=$_REQUEST[id]");
		$data['message']="UNFOLLOW";	
	}
	$QUERY=executeQuery("select * FROM utenti_seguono_utenti where FK_UTENTE=".$_REQUEST["id"]);
	$data['following'] = $QUERY->num_rows;
	$QUERY=executeQuery("select * FROM utenti_seguono_utenti where FK_UTENTE_SEGUITO=".$_REQUEST["id"]);
	$data['followers'] = $QUERY->num_rows;
	$data['userID'] = $_REQUEST["id"];
	$QUERY=executeQuery("select * FROM utenti_seguono_utenti where FK_UTENTE=".$_SESSION["ID"]);
	$data['userFollowing'] = $QUERY->num_rows;
	$QUERY=executeQuery("select * FROM utenti_seguono_utenti where FK_UTENTE_SEGUITO=".$_SESSION["ID"]);
	$data['userFollowers'] = $QUERY->num_rows;
	echo json_encode($data);
	exit();
?>