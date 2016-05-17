<?php
	require_once 'functions.php';
	if ($_REQUEST["fx"]=="follow") {
		$QUERY=executeQuery("insert into utenti_seguono_utenti (FK_UTENTE, FK_UTENTE_SEGUITO) VALUES ($_SESSION[ID], $_REQUEST[id])");
		echo "FOLLOW";
	} else {
		$QUERY=executeQuery("delete from utenti_seguono_utenti where FK_UTENTE=$_SESSION[ID] and FK_UTENTE_SEGUITO=$_REQUEST[id]");
		echo "UNFOLLOW";
	}
	exit();
?>