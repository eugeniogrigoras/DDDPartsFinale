<?php
	require_once 'functions.php';

	// A list of permitted file extensions
	$allowed = array('png', 'jpg', 'gif','zip');

	if(isset($_FILES['fileToUpload']) && $_FILES['fileToUpload']['error'] == 0){

		$extension = pathinfo($_FILES['fileToUpload']['name'], PATHINFO_EXTENSION);

		if(!in_array(strtolower($extension), $allowed)){
			echo 'Invalid Extension';
			exit;
		}

		$destinationPath = "users/".$_SESSION["NOME"]."-".$_SESSION["COGNOME"]."-".$_SESSION["EMAIL"]."/"."Project/";

		if (!file_exists($destinationPath)) {
            mkdir($destinationPath, 0777, true);
       	 	//copy('img/default.jpg', "users/".$name."-".$surname."-".$email."/profile.jpg");
   	 	}

   	 	if (file_exists($destinationPath.$_FILES['fileToUpload']['name'])) {
   	 		echo 'File Already Exists';
   	 		exit;
   	 	}

		if(move_uploaded_file($_FILES['fileToUpload']['tmp_name'], $destinationPath.$_FILES['fileToUpload']['name'])){
			echo 'Uploaded';
			exit;
		}
	}

	echo 'Unknown Error';
	exit;
	
?>
