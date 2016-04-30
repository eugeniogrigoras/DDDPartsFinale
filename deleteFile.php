<?php
	require_once 'functions.php';
	$file = $_REQUEST["name"];
	$file = substr($file, 1);
	$file = substr($file, 0,-1);
	$destinationPath = "users/".$_SESSION["NOME"]."-".$_SESSION["COGNOME"]."-".$_SESSION["EMAIL"]."/"."Project/".$file;
	if (!unlink($destinationPath))
	  	{
	  		echo ("Error deleting $file");
	  	}
	else
	  	{
	  		echo ("Deleted $file");
	  	}
?>