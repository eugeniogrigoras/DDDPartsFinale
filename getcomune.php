<?php 
	require_once 'functions.php';
	$idprovincia=$_REQUEST['idprovincia'];
	echo "<option value='' disabled selected>City</option>";
	$record = executeQuery("select * from comuni where comuni.idprovincia=$idprovincia"); 
 	while ($riga=$record->fetch_assoc()) {
 		echo "<option value='"
 		.$riga['id']
 		."'>".$riga['nome']
 		."</option>";
 	}
?>