<?php
	require_once 'functions.php';
	if (isset($_FILES["fileToUpload"])) {
		imageUpload();
	}
	$data=requestData();
	if ($data) {
		if (isset($_REQUEST["description"])) {
			if ($data["DESCRIZIONE"]!=$_REQUEST["description"]) {
					changeDescription($_REQUEST["description"]);
					echo "Description changed";
				}
		}
		if (isset($_REQUEST["password"])) {
			if (($data["PASSWORD"]!=$_REQUEST["password"]) && (strlen($_REQUEST["password"])!=0)) {
					changePassword($_REQUEST["password"]);
					echo "Password changed";
				}
		}
		//echo("Updated");
		exit();
	}
	echo("Update Error");
	exit();
?>