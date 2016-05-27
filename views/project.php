<?php require_once 'primo.php'; ?>
<?php 
    $projects=executeQuery('select progetti.ID, progetti.NOME AS NOME_PROGETTO, progetti.DESCRIZIONE, progetti.FK_UTENTE, utenti.NOME AS NOME_UTENTE, utenti.COGNOME, utenti.EMAIL, categorie_primarie.NOME AS CATEGORIA_PRIMARIA, categorie_secondarie.NOME AS CATEGORIA_SECONDARIA, COUNT(*) AS FILES FROM progetti, utenti, categorie_primarie, categorie_secondarie, parti_3d WHERE progetti.FK_CATEGORIA_SECONDARIA=categorie_secondarie.ID AND categorie_secondarie.FK_CATEGORIA_PRIMARIA=categorie_primarie.ID AND progetti.FK_UTENTE=utenti.ID AND parti_3d.FK_PROGETTO=progetti.ID AND progetti.ID='.$id.' GROUP BY progetti.ID');
    $project=$projects->fetch_assoc();  
    //var_dump($project);
?>
<title><?php echo $project["NOME_PROGETTO"] ?></title>
<meta name="description" content="<?php echo $project["DESCRIZIONE"] ?>">

<style>
	div.main-content {
        padding-top: 24px;
    }
    div.title {
        background-color: #444;
        color:white;
        padding:24px!important;
        font-weight: 300;
    }
</style>
<?php require_once 'secondo.php'; ?>
<main>
    <div class="row main-content">
        <div class="col l7">
            <div class="col s12 title"><?php echo $project["NOME_PROGETTO"];  if(isset($message)) echo "- ".$message ?></div>
            <ul class="col s12 collection" id="uploadedFiles">
                <?php 
                    
                ?>

            </ul>
        </div>
        <div class="col l1">&nbsp</div>
        <div class="col l4">
            <div class="col s12 title">Stats</div>
        </div>
    </div>
</main>

<?php require_once 'terzo.php'; ?>
<script>

</script>
<?php require_once 'quarto.php'; ?>