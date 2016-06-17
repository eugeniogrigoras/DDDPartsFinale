<?php require_once 'primo.php'; ?>
<?php
    $collections=executeQuery("select utenti.ID as ID_UTENTE, utenti.COGNOME as COGNOME_UTENTE, utenti.NOME as NOME_UTENTE, utenti.EMAIL as EMAIL_UTENTE, collezioni.TITOLO as TITOLO_COLLEZIONE, collezioni.DATA_CREAZIONE, collezioni.DESCRIZIONE as DESCRIZIONE_COLLEZIONE from collezioni, utenti where collezioni.ID=$id and collezioni.FK_UTENTE=utenti.ID");
    $collection=$collections->fetch_assoc();
?>
<title><?php echo $collection["TITOLO_COLLEZIONE"] ?> by <?php echo $collection["NOME_UTENTE"]." ".$collection["COGNOME_UTENTE"]; ?></title>
<meta name="description" content="<?php echo $collection["DESCRIZIONE_COLLEZIONE"] ?>">

<style>
    div.main-content {
        margin-top: 24px;
        margin-bottom: 24px!important;
    }

    div.main-content form {

    }

    div.title {
        background-color: #444;
        color:white;
        padding:24px;
        font-weight: 300;
        text-transform: uppercase;
        text-align: left;
    }

    div.row {
        margin-bottom: 0;
    }
    #avatar {
        overflow: hidden;
        border-radius: 50%;
        
        width: 150px;
        height: 150px;
        margin:auto auto;
        background-position: 50% 50%;
        background-repeat:   no-repeat;
        background-size:     cover;
    }

    .collection-image {
        position: absolute;
        overflow: hidden;
        border-radius: 50%;
        
        width: 42px;
        height: 42px;
        background-position: 50% 50%;
        background-repeat:   no-repeat;
        background-size:     cover;
        left: 15px;
        display: inline-block;
        vertical-align: middle;
    }

    div.account {
        padding: 0px;
    }

    .number {
        color: #444;
        margin-bottom: 5px;
    }

    .subtitle {
        color: #686868;
        font-size: 10px;
    }
    .user-card:hover {
        background-color: #ddd;
    }

    .card-action .center-align:hover {
        background-color: #ddd;
    }

    .user-card {
        padding:12px 24px!important;
        text-align: center;
        position: relative;
        display: inline-block;
        background-color: #fff;
        box-shadow: 0 0 0 #fff;
        margin:0;
        moz-transition: background-color 0.25s;
        transition: background-color 0.25s;
        webkit-transition : background-color 0.25s;
    }

    .user-card +.user-card {
        border-left:1px solid #ddd;
    }

    div#profile-card {
        border-bottom:1px solid #ddd!important;
    }

    @media only screen and (min-width : 993px) {
        
    }

    @media only screen and (max-width : 992px) {
        div.user-card:nth-of-type(1) {
            border-right:1px solid #ddd;
        }
        div.user-card:nth-of-type(3) {
            border-right:1px solid #ddd;
        }
        div.user-card:nth-of-type(5) {
            border-right:1px solid #ddd;
        }
        .user-card +.user-card {
            border-left:0px solid #ddd;
        }
        .user-card {
            border-bottom:1px solid #ddd;
        }
        #followed-collection {
            border-bottom:0px solid #ddd;
        }
        #my-collection {
            border-bottom:0px solid #ddd;
        }
    }

    .user-box + .user-box {

    }

    .following-button:hover {
        cursor:pointer;
    }

    .fit-content {
        width: -moz-fit-content;
        width: -webkit-fit-content;
        width: fit-content;
    }

    p a {
        color: rgba(255,109,64,1);
        border-bottom:0px solid #ddd;
    }

    p a:hover {
        border-bottom:1px solid rgba(255,109,64,1);
    }

    button#save {
        display: none;
    }

    .card-image {
        height: 150px;
        background-position: 50% 50%;
        background-repeat:   no-repeat;
        background-size:     cover;
    }

    .selected-collection {
        background-color: red;
    }

    .no_style a {
        color:#777;
    }

    .no_style a:hover {
        border-bottom:0px solid rgba(255,109,64,1)!important;
    }

    .no_style:hover {
        color:rgba(255,109,64,1)!important;
    }

    .no_style:hover a{
        color:rgba(255,109,64,1)!important;
    }

</style>
<?php require_once 'secondo.php'; ?>
<main>
    <div class="container main-content row z-depth-1" style="margin:0;">
        <div class="col l9 s12 m8" style="margin-bottom:24px">
            <div class="col s12 z-depth-1" style="height:100%; background-color:white; margin:0; padding:0">
                <div class="col s12 title">
                    <p class="truncate" style="font-size:20px; margin:0;"><?php echo $project["NOME_PROGETTO"] ?></p>
                    <p class="truncate" style="font-size:13px; margin:0">by <a href="/user/<?php echo $project["FK_UTENTE"]; ?>"><?php echo $project["NOME_UTENTE"]." ".$project["COGNOME"] ?></a></p>
                </div>
                <div style="background:#9e9e9e"><div class="progress-bar"></div></div>
                <div class="col s12" style="padding:24px;">
                    <h5>Description</h5>
                    <p><?php echo $project["DESCRIZIONE"] ?></p>
                    <ul style="border:0; margin:0; padding:0; display:none" class="col s12 collection">
                    
                        <?php 

                            $files = array_slice(scandir($projectPath), 2);
                        
                            foreach ($files as $file) {
                                echo $file;
                                echo "<br>";
                            }
                            foreach (glob($projectPath."/*.jpg") as $filename) {
                                //var2console($filename);
                                echo "/$filename size " . (filesize($filename) / (1024*1024));
                                echo "<br>";
                            }   
                        ?>
                    </ul>
                </div>
            </div>
    </div>
    <?php 
        $projects = executeQuery("select progetti.ID as ID_PROGETTO, progetti.FK_UTENTE as ID_UTENTE, utenti.NOME as NOME_UTENTE, utenti.COGNOME as COGNOME_UTENTE, utenti.EMAIL as EMAIL_UTENTE FROM progetti, collezioni_composte_da_progetti, utenti WHERE collezioni_composte_da_progetti.FK_PROGETTO=progetti.ID and progetti.FK_UTENTE=utenti.ID and collezioni_composte_da_progetti.FK_COLLEZIONE=$id order by collezioni_composte_da_progetti.DATA_AGGIUNTA DESC");
        while ($project=$projects->fetch_assoc()):
            var2console($project);
    ?>
        
    <?php endwhile; ?>
</main>

<?php require_once 'terzo.php'; ?>
<script>
    function _(el){
        return document.getElementById(el);
    }
</script>
<?php require_once 'quarto.php'; ?>