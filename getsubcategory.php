<?php 
	require_once 'functions.php';
	$idcategory=$_REQUEST['idcategory'];
	echo "<option value='' disabled selected>Subcategory</option>";
	$record = executeQuery("select * from categorie_secondarie where categorie_secondarie.FK_CATEGORIA_PRIMARIA=$idcategory"); 
 	while ($riga=$record->fetch_assoc()) {
 		echo "<option value='"
 		.$riga['ID']
 		."'>".$riga['NOME']
 		."</option>";
 	}
?>