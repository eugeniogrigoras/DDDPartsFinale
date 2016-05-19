<?php require_once 'primo.php'; ?>
<title>Account</title>
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
    #avatar img {
        width: 100%;
        height: 100%;
    }
    #avatar {
        overflow: hidden;
        border-radius: 50%;
        
        width: 150px;
        height: 150px;
        background-size: cover; 
        z-index: 2;
        text-align: center;
        margin:auto auto;
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

    

</style>
<?php require_once 'secondo.php'; ?>
<main>
    <div class="container main-content row z-depth-1">
    	<div class="card col s12" style="padding:0!important; margin:0!important">
	        <div class="title truncate"><i style="margin:0!important; cursor:pointer" class="activator material-icons right noselect">info_outline</i><?php echo $_SESSION["NOME"]." ".$_SESSION["COGNOME"]; ?></div>
	        <div class="background" style="padding:24px; ">
	            <div id="avatar" class="z-depth-1">
	                <img src="<?php echo requestPath()."/profile.jpg";?>" alt="">
	            </div>
	        </div>
	        <div id="profile-card" class="card-reveal" style=" text-align:left; color:#444; width:inherit!important">
	        	<span class="card-title"><i class="material-icons right noselect">close</i><?php echo $_SESSION["NOME"]." ".$_SESSION["COGNOME"]; ?> - Information</span>
	        	<p class="valign-wrapper"><i class="valign material-icons noselect" style="margin-right:20px;">email</i><?php echo requestData()["EMAIL"];?></p>
	        	<p class="valign-wrapper"><i class="valign material-icons noselect" style="margin-right:20px;">place</i><?php echo requestData()["COMUNE"];?></p>
	        	<p class="valign-wrapper"><i class="valign material-icons noselect" style="margin-right:20px;">description</i><?php echo requestData()["DESCRIZIONE"];?></p>    	
	        </div>
        </div>
        <div class="sections col s12" style="margin-bottom:0; padding:0!important">
            <div class="user-card col l2 m6 s12 waves-effect" onclick="Materialize.showStaggeredList('#staggered-test')" id="following">
                <div class="number">
                    <?php
                        $QUERY=executeQuery("select * FROM utenti_seguono_utenti where FK_UTENTE=".$_SESSION["ID"]);
                        echo $QUERY->num_rows; 
                    ?>
                </div>
                <div class="subtitle truncate" onclick="following()">FOLLOWING</div>
            </div>
            <div class="user-card col l2 m6 s12 waves-effect" id="follower">
                <div class="number">
                    <?php
                        $QUERY=executeQuery("select * FROM utenti_seguono_utenti where FK_UTENTE_SEGUITO=".$_SESSION["ID"]);
                        echo $QUERY->num_rows; 
                    ?>
                </div>
                <div class="subtitle truncate">FOLLOWERS</div>
            </div>
            <div class="user-card col l2 m6 s12 waves-effect" id="likes">
                <div class="number">
                    <?php
                        $QUERY=executeQuery("select * FROM utenti_like_progetti as p, utenti_like_collezioni as c where p.FK_UTENTE=".$_SESSION["ID"]." and c.FK_UTENTE=".$_SESSION["ID"]);
                        echo $QUERY->num_rows; 
                    ?>
                </div>
                <div class="subtitle truncate">LIKES</div>
            </div>
            <div class="user-card col l2 m6 s12 waves-effect" id="projects">
                <div class="number">
                    <?php
                        $QUERY=executeQuery("select * FROM progetti where FK_UTENTE=".$_SESSION["ID"]);
                        echo $QUERY->num_rows; 
                    ?>
                </div>
                <div class="subtitle truncate">PROJECTS</div>
            </div>
            <div class="user-card col l2 m6 s12 waves-effect" id="my-collections">
                <div class="number">
                    <?php
                        $QUERY=executeQuery("select * FROM collezioni where FK_UTENTE=".$_SESSION["ID"]);
                        echo $QUERY->num_rows; 
                    ?>
                </div>
                <div class="subtitle truncate">MY COLLECTIONS</div>
            </div>
            <div class="user-card col l2 m6 s12 waves-effect" id="followed-collections">
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

    <!-- ------------------------------------------------------------------------------------------------------------ -->
    <ul id="staggered-test">
    <div class="row users container">
        <?php 
            //$users=executeQuery('select * from utenti where utenti.ID IN (select FK_UTENTE_SEGUITO from utenti_seguono_utenti where FK_UTENTE='.$_SESSION["ID"].')');
            $users=executeQuery('select * from utenti');
            while ($user=$users->fetch_assoc()) : 
        ?>
        <li style="opacity: 0;">
        <div class="col s12 m6 l4">
            <div class="z-depth-1 card" style="background-color:white">
                <div class="background2 card-image waves-effect waves-block waves-light activator" style="padding:12px 0;">
                    <div id="avatar" class="z-depth-1 activator">
                        <img class="activator" src="<?php echo requestPathUser($user["NOME"], $user["COGNOME"], $user["EMAIL"])."/profile.jpg" ?>">
                    </div>
                </div>
                <div class="card-content" style="padding:12px 15px;">
                    <p>
                        <input 
                        <?php 
                            $data=executeQuery("select * from utenti_seguono_utenti where FK_UTENTE=".$_SESSION["ID"]." and FK_UTENTE_SEGUITO=".$user["ID"]);
                            if ($data) {
                                if ($data->num_rows > 0) {
                                    echo "checked";
                                }
                            }
                        ?> 
                        type="checkbox" id="<?php echo $user["ID"]; ?>" />
                        <label for="<?php echo $user["ID"]; ?>" style="font-weight:600; color:#424242"><?php echo $user["NOME"]." ".$user["COGNOME"] ?></label>
                    </p>
                </div>
                
                <div class="card-action" style="padding:0">
                    <div class="center-align waves-effect col s6"style="padding:6px 0;"> 
                        <div class="number" style="font-weight:600; color:#424242;">
                            <?php
                                $QUERY=executeQuery("select * FROM utenti_seguono_utenti where FK_UTENTE=".$user["ID"]);
                                echo $QUERY->num_rows; 
                            ?>
                        </div>
                        <div class="subtitle truncate" style="color:#757575">FOLLOWING</div>
                    </div>
                    <div class="center-align waves-effect" style="width:50%; padding:6px 0; border-left:1px solid #ddd">
                        <div class="number" style="font-weight:600; color:#424242;">
                            <?php
                            $QUERY=executeQuery("select * FROM utenti_seguono_utenti where FK_UTENTE_SEGUITO=".$user["ID"]);
                            echo $QUERY->num_rows; 
                        ?>
                        </div>
                        <div class="subtitle truncate" style="color:#757575">FOLLOWERS</div>
                    </div>
                </div>
                <div class="card-reveal">
                    <span class="card-title grey-text text-darken-4"><i class="material-icons right noselect">close</i><?php echo $user["NOME"]." ".$user["COGNOME"] ?></span>
                    <p class="valign-wrapper"><i class="valign material-icons noselect" style="margin-right:20px;">email</i><?php echo $user["EMAIL"] ?></p>
                    <p class="valign-wrapper"><i class="valign material-icons noselect" style="margin-right:20px;">place</i><?php echo getComune($user["FK_COMUNE"]) ?></p>
                    <p class="valign-wrapper"><i class="valign material-icons noselect" style="margin-right:20px;">description</i><?php echo $user["DESCRIZIONE"] ?></p>
                </div>
            </div>
        </div>
        </li>
        <?php endwhile; ?>
              
    </div>
    </ul>
    <!-- <div class="row projects container">
        <div class="col s12 m6 l4">
            <div class="z-depth-1 card" style="background-color:white">
                <div class="card-image waves-effect waves-block waves-light">
                    <img class="activator" src="/img/bg1.jpg">
                </div>
                <div class="card-content">
                    <span class="card-title activator grey-text text-darken-4">Card Title<i class="material-icons right">more_vert</i></span>
                    <p><a href="#">This is a link</a></p>
                </div>
                <div class="card-reveal">
                    <span class="card-title grey-text text-darken-4">Card Title<i class="material-icons right">close</i></span>
                    <p>Here is some more information about this product that is only revealed once clicked on.</p>
                </div>
            </div>
        </div>
        <div class="col s12 m6 l4">
            <div class="z-depth-1 card" style="background-color:white">
                <div class="card-image waves-effect waves-block waves-light">
                    <img class="activator" src="/img/bg2.jpg">
                </div>
                <div class="card-content">
                    <span class="card-title activator grey-text text-darken-4">Card Title<i class="material-icons right">more_vert</i></span>
                    <p><a href="#">This is a link</a></p>
                </div>
                <div class="card-reveal">
                    <span class="card-title grey-text text-darken-4">Card Title<i class="material-icons right">close</i></span>
                    <p>Here is some more information about this product that is only revealed once clicked on.</p>
                </div>
            </div>
        </div>
        <div class="col s12 m6 l4">
            <div class="z-depth-1 card" style="background-color:white">
                <div class="card-image waves-effect waves-block waves-light">
                    <img class="activator" src="/img/bg1.jpg">
                </div>
                <div class="card-content">
                    <span class="card-title activator grey-text text-darken-4">Card Title<i class="material-icons right">more_vert</i></span>
                    <p><a href="#">This is a link</a></p>
                </div>
                <div class="card-reveal">
                    <span class="card-title grey-text text-darken-4">Card Title<i class="material-icons right">close</i></span>
                    <p>Here is some more information about this product that is only revealed once clicked on.</p>
                </div>
            </div>
        </div>
    </div> -->

</main>

<?php require_once 'terzo.php'; ?>

<script>
    $(document).ready(function() {
        $("input[type=checkbox]").change(function(){
            var ajax = new XMLHttpRequest();
            var formdata = new FormData();
            formdata.append("id", this.id);

            ajax.addEventListener("load", function (event) {
                if (event.target.responseText) {
                    var t = event.target.responseText;
                    Materialize.toast(t, 2000);
                }
            }, false);

            ajax.addEventListener("error", function (event) {
                Materialize.toast("Error Occured", 2500);
            }, false);
            if (this.checked) {
                formdata.append("fx", "follow");
            } else {
                formdata.append("fx", "unfollow");
            }
            ajax.open("POST", "/follow.php");
            ajax.send(formdata);
        });
    });

    function _(el){
        return document.getElementById(el);
    }
    function following() {
        // SELECT * FROM `utenti` WHERE utenti.ID in (SELECT FK_UTENTE_SEGUITO FROM `utenti_seguono_utenti` WHERE FK_UTENTE=42);
    }

    function follower() {
        // SELECT * FROM `utenti` WHERE utenti.ID in (SELECT FK_UTENTE FROM `utenti_seguono_utenti` WHERE FK_UTENTE_SEGUITO=42);
    }
</script>

<?php require_once 'quarto.php'; ?>