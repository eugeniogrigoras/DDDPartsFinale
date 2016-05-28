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

    .fit-content {
        width: -moz-fit-content;
        width: -webkit-fit-content;
        width: fit-content;
    }

    p a {
        color: rgba(255,109,64,1)!important;
        border-bottom:1px solid #ddd;
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

</style>
<?php require_once 'secondo.php'; ?>
<main>
    <div class="container main-content row z-depth-1">
    	<div class="card col s12" style="padding:0!important; margin:0!important">
	        <div class="title truncate"><i style="margin:0!important; cursor:pointer" class="activator material-icons right noselect">info_outline</i><?php echo $_SESSION["NOME"]." ".$_SESSION["COGNOME"]; ?></div>
	        <div class="background" style="padding:24px; ">
	            <div id="avatar" class="z-depth-1" style="background-image:url('<?php echo requestPath()."/profile.jpg";?>')">
	                
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
            <div id="userFollowingCard" class="user-card col l2 m6 s12 waves-effect" onclick="following()">
                <div class="number" id="userFollowingNumber">
                    <?php
                        $QUERY=executeQuery("select * FROM utenti_seguono_utenti where FK_UTENTE=".$_SESSION["ID"]);
                        echo $QUERY->num_rows; 
                    ?>
                </div>
                <div class="subtitle truncate">FOLLOWING</div>
            </div>
            <div id="userFollowersCard" class="user-card col l2 m6 s12 waves-effect" onclick="followers()">
                <div class="number" id="userFollowersNumber">
                    <?php
                        $QUERY=executeQuery("select * FROM utenti_seguono_utenti where FK_UTENTE_SEGUITO=".$_SESSION["ID"]);
                        echo $QUERY->num_rows; 
                    ?>
                </div>
                <div class="subtitle truncate">FOLLOWERS</div>
            </div>
            <div class="user-card col l2 m6 s12 waves-effect">
                <div class="number">
                    <?php
                        $QUERY=executeQuery("select * FROM utenti_like_progetti as p, utenti_like_collezioni as c where p.FK_UTENTE=".$_SESSION["ID"]." and c.FK_UTENTE=".$_SESSION["ID"]);
                        echo $QUERY->num_rows; 
                    ?>
                </div>
                <div class="subtitle truncate">LIKES</div>
            </div>
            <div id="userProjectsCard" class="user-card col l2 m6 s12 waves-effect" onclick="projects()">
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



    <div id="accountCardsResponse"></div>

</main>

<?php require_once 'terzo.php'; ?>

<script>

    function _(el){
        return document.getElementById(el);
    }

    $(document).ready(function() {
        <?php if(isset($_REQUEST["fx"]) && $_REQUEST["fx"]=="following"): ?>
            _('userFollowingCard').click();
        <?php endif; ?>

        <?php if(isset($_REQUEST["fx"]) && $_REQUEST["fx"]=="followers"): ?>
            _('userFollowersCard').click();
        <?php endif; ?>

        <?php if(isset($_REQUEST["fx"]) && $_REQUEST["fx"]=="projects"): ?>
            _('userProjectsCard').click();
        <?php endif; ?>
    });

    //ACCOUNT CARDS
    function projects() {
        var ajax = new XMLHttpRequest();
        var formdata = new FormData();
        formdata.append("fx", "projects");
        formdata.append("id", <?php echo $_SESSION["ID"] ?>);
        ajax.addEventListener("load", function (event) {
            var t = event.target.responseText;
            _("accountCardsResponse").innerHTML=t;
            Materialize.showStaggeredList('#projects');
        }, false);

        ajax.addEventListener("error", function (event) {
            Materialize.toast("Error Occured", 2500);
        }, false);
        ajax.open("POST", "/accountCards.php");
        ajax.send(formdata);
    }

    function following() {
        var ajax = new XMLHttpRequest();
        var formdata = new FormData();
        formdata.append("fx", "following");
        formdata.append("id", <?php echo $_SESSION["ID"] ?>);
        ajax.addEventListener("load", function (event) {
            var t = event.target.responseText;
            _("accountCardsResponse").innerHTML=t;
            Materialize.showStaggeredList('#following');
        }, false);

        ajax.addEventListener("error", function (event) {
            Materialize.toast("Error Occured", 2500);
        }, false);
        ajax.open("POST", "/accountCards.php");
        ajax.send(formdata);
    }

    function followers() {
        var ajax = new XMLHttpRequest();
        var formdata = new FormData();
        formdata.append("fx", "followers");
        formdata.append("id", <?php echo $_SESSION["ID"] ?>);
        ajax.addEventListener("load", function (event) {
            var t = event.target.responseText;
            _("accountCardsResponse").innerHTML=t;
            Materialize.showStaggeredList('#followers');
        }, false);

        ajax.addEventListener("error", function (event) {
            Materialize.toast("Error Occured", 2500);
        }, false);
        ajax.open("POST", "/accountCards.php");
        ajax.send(formdata);
    }
    
</script>

<script>

    $("#accountCardsResponse").on("change", "input[type=checkbox]", function(){
        var ajax = new XMLHttpRequest();
        var formdata = new FormData();
        formdata.append("id", this.id);

        ajax.addEventListener("load", function (event) {
            var res = $.parseJSON(event.target.responseText);
            Materialize.toast(res.message, 2000);
            _('usersFollowingNumber'+res.requestID).innerHTML=res.usersFollowingNumber;
            _('usersFollowersNumber'+res.requestID).innerHTML=res.usersFollowersNumber;
            _('userFollowingNumber').innerHTML=res.userFollowingNumber;
            _('userFollowersNumber').innerHTML=res.userFollowersNumber;
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
</script>

<?php require_once 'quarto.php'; ?>