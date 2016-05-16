<?php
	require_once 'functions.php';
	$destinationPath = "users/".$_SESSION["NOME"]."-".$_SESSION["COGNOME"]."-".$_SESSION["EMAIL"]."/"."Project/";
	if (file_exists($destinationPath)) {
		deleteDir($destinationPath);
	}
	
?>