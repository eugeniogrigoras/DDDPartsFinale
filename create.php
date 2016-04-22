<?php
	require_once 'functions.php';

	// A list of permitted file extensions
	$allowed = array('png', 'jpg', 'gif','zip');

	if(isset($_FILES['fileToUpload']) && $_FILES['fileToUpload']['error'] == 0){

		$extension = pathinfo($_FILES['fileToUpload']['name'], PATHINFO_EXTENSION);

		if(!in_array(strtolower($extension), $allowed)){
			echo '{"status":"invalid extension"}';
			exit;
		}

		$destinationPath = "users/".$_SESSION["NOME"]."-".$_SESSION["COGNOME"]."-".$_SESSION["EMAIL"]."/";

		if(move_uploaded_file($_FILES['fileToUpload']['tmp_name'], $destinationPath.$_FILES['fileToUpload']['name'])){
			echo '{"status":"success"}';
			exit;
		}
	}

	echo '{"status":"error"}';
	exit;
	
?>
