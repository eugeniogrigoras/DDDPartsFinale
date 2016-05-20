<?php
	require_once 'functions.php';

	// A list of permitted file extensions
	$allowed = array('png', 'jpg', 'gif','zip');

	if(isset($_FILES['filesToUpload']) && $_FILES['filesToUpload']['error'] == 0){

		$extension = pathinfo($_FILES['filesToUpload']['name'], PATHINFO_EXTENSION);

		if(!in_array(strtolower($extension), $allowed)){
			echo 'Invalid Extension';
			exit;
		}

		$destinationPath = "users/".$_SESSION["NOME"]."-".$_SESSION["COGNOME"]."-".$_SESSION["EMAIL"]."/"."Project/";

		if (!file_exists($destinationPath)) {
            mkdir($destinationPath, 0777, true);
            copy('img/bg1.jpg', $destinationPath."projectWallpaper.jpg");
   	 	}

   	 	if (file_exists($destinationPath.$_FILES['filesToUpload']['name'])) {
   	 		echo 'File Already Exists';
   	 		exit;
   	 	}

		if(move_uploaded_file($_FILES['filesToUpload']['tmp_name'], $destinationPath.$_FILES['filesToUpload']['name'])){
			echo 'Uploaded';
			exit;
		}
	}

	echo 'Unknown Error';
	exit;
	
?>
