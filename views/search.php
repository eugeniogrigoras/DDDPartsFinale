<?php require_once 'primo.php'; ?>
<title>Search</title>
<style>
	.main-content {
        margin-top: 24px;
        margin-bottom: 24px!important;
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
    #avatar img {
        width: 100%;
        height: 100%;
    }
    #avatar {
        overflow: hidden;
        border-radius: 50%;
        border: 3.5px solid rgba(255, 255, 255, 0.35);
        width: 150px;
        height: 150px;
        background-size: cover; 
        z-index: 2;
        text-align: center;
        margin:auto auto;
    }

    .account {
        padding: 0;
    }

</style>
<?php require_once 'secondo.php'; ?>
<main>
    <div class="container main-content row z-depth-1">
    	<div class="card col s12" style="padding:0!important">
	        <div class="title truncate"><i style="margin:0!important; cursor:pointer" class="activator material-icons right">info_outline</i><?php echo $_SESSION["NOME"]." ".$_SESSION["COGNOME"]; ?></div>
	        <div class="" style="padding:24px; background-image:url('/img/bg2.jpg'); background-size:cover">
	            <div id="avatar">
	                <img src="<?php echo requestPath()."/profile.jpg";?>" alt="" class="z-depth-1 circle">
	            </div>
	        </div>
	        <div class="card-reveal" style=" text-align:left; color:#444; width:inherit!important">
	        	<span class="card-title"><i class="material-icons right">close</i><?php echo $_SESSION["NOME"]." ".$_SESSION["COGNOME"]; ?> - Information</span>
	        	<p class="valign-wrapper"><i class="valign material-icons" style="margin-right:20px;">email</i><?php echo requestData()["EMAIL"];?></p>
	        	<p class="valign-wrapper"><i class="valign material-icons" style="margin-right:20px;">place</i><?php echo requestData()["COMUNE"];?></p>
	        	<p class="valign-wrapper"><i class="valign material-icons" style="margin-right:20px;">description</i><?php echo requestData()["DESCRIZIONE"];?></p>    	
	        </div>
        </div>
        <div class="sections col s12" style="margin-bottom:0; padding:0!important">
            <div class="card col l2 m6 s12 waves-effect" id="following">
                <div class="number">
                    <?php
                        $QUERY=executeQuery("select * FROM utenti_seguono_utenti where FK_UTENTE=".$_SESSION["ID"]);
                        echo $QUERY->num_rows; 
                    ?>
                </div>
                <div class="subtitle truncate">FOLLOWING</div>
            </div>
            <div class="card col l2 m6 s12 waves-effect" id="follower">
                <div class="number">
                    <?php
                        $QUERY=executeQuery("select * FROM utenti_seguono_utenti where FK_UTENTE_SEGUITO=".$_SESSION["ID"]);
                        echo $QUERY->num_rows; 
                    ?>
                </div>
                <div class="subtitle truncate">FOLLOWERS</div>
            </div>
            <div class="card col l2 m6 s12 waves-effect" id="likes">
                <div class="number">
                    <?php
                        $QUERY=executeQuery("select * FROM utenti_like_progetti as p, utenti_like_collezioni as c where p.FK_UTENTE=".$_SESSION["ID"]." and c.FK_UTENTE=".$_SESSION["ID"]);
                        echo $QUERY->num_rows; 
                    ?>
                </div>
                <div class="subtitle truncate">LIKES</div>
            </div>
            <div class="card col l2 m6 s12 waves-effect" id="projects">
                <div class="number">
                    <?php
                        $QUERY=executeQuery("select * FROM progetti where FK_UTENTE=".$_SESSION["ID"]);
                        echo $QUERY->num_rows; 
                    ?>
                </div>
                <div class="subtitle truncate">PROJECTS</div>
            </div>
            <div class="card col l2 m6 s12 waves-effect" id="my-collections">
                <div class="number">
                    <?php
                        $QUERY=executeQuery("select * FROM collezioni where FK_UTENTE=".$_SESSION["ID"]);
                        echo $QUERY->num_rows; 
                    ?>
                </div>
                <div class="subtitle truncate">MY COLLECTIONS</div>
            </div>
            <div class="card col l2 m6 s12 waves-effect" id="followed-collections">
                <div class="number">
                    <?php
                        $QUERY=executeQuery("select * FROM utenti_seguono_collezioni where FK_UTENTE=".$_SESSION["ID"]);
                        echo $QUERY->num_rows; 
                    ?>
                </div>
                <div class="subtitle truncate">FOLLOWED COLLECTIONS</div>
            </div>
        </div>
    </div>
</main>

<?php require_once 'terzo.php'; ?>

<script></script>

<?php require_once 'quarto.php'; ?>