<?php 
	require_once 'functions.php';
	$idregione=$_REQUEST['idregione'];
	echo "<option value='' disabled selected>Province</option>";
	$record = executeQuery("select * from province where province.idregione=$idregione"); 
 	while ($riga=$record->fetch_assoc()) {
 		echo "<option value='"
 		.$riga['idprovincia']
 		."'>".$riga['nomeprovincia']
 		."</option>";
 	}
?>