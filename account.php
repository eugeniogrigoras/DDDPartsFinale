<?php require_once 'primo.php'; ?>
<title>Account</title>
<style>
	div.main-content {
        padding-top: 24px;
        padding-bottom: 24px;
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
        margin-bottom: 0!important;
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
        @apply(--shadow-elevation-4dp);
    }

    div.account {
        padding: 0px;
    }

    .number {
        @apply(--paper-font-title);
        color: #444;
        margin-bottom: 5px;
    }

    .subtitle {
        @apply(--paper-font-caption);
        color: #686868;
        font-size: 10px;
    }
    .card:hover {
        background-color: #ddd;
    }

    .card {
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

    .card +.card {
        border-left:1px solid #ddd;
    }

    @media only screen and (min-width : 993px) {
    	div.card-reveal {
    		border-bottom:1px solid #ddd!important;
    	}
    }

    @media only screen and (max-width : 992px) {
        div.card:nth-of-type(1) {
            border-right:1px solid #ddd;
        }
        div.card:nth-of-type(3) {
            border-right:1px solid #ddd;
        }
        div.card:nth-of-type(5) {
            border-right:1px solid #ddd;
        }
        .card +.card {
            border-left:0px solid #ddd;
        }
        .card {
            border-bottom:1px solid #ddd;
        }
        #followed-collection {
            border-bottom:0px solid #ddd;
        }
        #my-collection {
            border-bottom:0px solid #ddd;
        }
    }
</style>
<?php require_once 'secondo.php'; ?>
<main>
    <div class="container main-content row ">
    	<div class="z-depth-1 row" >
    	<div class="card col s12" style="padding:0!important">
	        <div class="title truncate"><i style="margin:0!important; cursor:pointer" class="activator material-icons right">info_outline</i><?php echo $_SESSION["NOME"]." ".$_SESSION["COGNOME"]; ?></div>
	        <div class="" style="padding:24px; background-image:url('/img/bg2.jpg'); background-size:cover">
	            <div id="avatar">
	                <img src="<?php echo requestPath()."/profile.jpg";?>" alt="" class="z-depth-1 circle">
	            </div>
	        </div>
	        <div class="card-reveal" style=" text-align:left; color:#444; width:inherit!important">
	        	<span class="card-title"><i class="material-icons right">close</i><?php echo $_SESSION["NOME"]." ".$_SESSION["COGNOME"]; ?> - Information</span>
	        	<p class="valign-wrapper"><i class="valign material-icons" style="margin-right:20px;">email</i><?php $data=requestData(); echo $data["EMAIL"];?></p>
	        	<p class="valign-wrapper"><i class="valign material-icons" style="margin-right:20px;">place</i><?php echo $data["COMUNE"];?></p>
	        	<p class="valign-wrapper"><i class="valign material-icons" style="margin-right:20px;">description</i><?php echo $data["DESCRIZIONE"];?></p>    	
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
    </div>
</main>

<?php require_once 'terzo.php'; ?>

<script></script>

<?php require_once 'quarto.php'; ?>