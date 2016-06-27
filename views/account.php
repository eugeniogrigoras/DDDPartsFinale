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
        background-color: #ddd!important;
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

    @media only screen and (max-width : 600px) {
        .user-card {
            border-right:0px solid #ddd!important;
        }
        .user-card:nth-of-type(5) {
            border-bottom:1px solid #ddd!important;
        }
    }

    @media only screen and (max-width : 992px) {
        .user-card:nth-of-type(5) {
            border-bottom:0px solid #ddd;
        }
        .user-card:nth-of-type(6) {
            border-bottom:0px solid #ddd!important;
        }
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

    i {
        moz-transition: color 0.25s;
        transition: color 0.25s;
        webkit-transition : color 0.25s;
    }

    .card-reveal {
        color:#212121;
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
            <?php $user=requestData(); ?>
            <div id="profile-card" class="card-reveal" style=" text-align:left; color:#444; width:inherit!important">
                <span class="card-title"><i class="material-icons right noselect">close</i><?php echo $_SESSION["NOME"]." ".$_SESSION["COGNOME"]; ?> - Information</span>
                <p class="valign-wrapper"><i class="valign material-icons noselect" style="margin-right:20px;">email</i><?php echo $user["EMAIL"];?></p>
                <p class="valign-wrapper"><i class="valign material-icons noselect" style="margin-right:20px;">place</i><?php echo $user["COMUNE"];?></p>
                <p class="valign-wrapper"><i class="valign material-icons noselect" style="margin-right:20px;">description</i><?php echo $user["DESCRIZIONE"];?></p>        
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
            <div class="user-card col l2 m6 s12 waves-effect" id="userLikesCard" onclick="likes()">
                <div class="number" id="userLikesNumber">
                    <?php
                        $QUERY=executeQuery("select * FROM utenti_like_progetti where FK_UTENTE=".$_SESSION["ID"]);
                        echo $QUERY->num_rows; 
                    ?>
                </div>
                <div class="subtitle truncate">LIKES</div>
            </div>
            <div id="userProjectsCard" class="user-card col l2 m6 s12 waves-effect" onclick="projects()">
                <div class="number" id="userProjectsNumber">
                    <?php
                        $QUERY=executeQuery("select * FROM progetti where FK_UTENTE=".$_SESSION["ID"]);
                        echo $QUERY->num_rows; 
                    ?>
                </div>
                <div class="subtitle truncate">PROJECTS</div>
            </div>
            <div id="userMyCollectionsCard" class="user-card col l2 m6 s12 waves-effect" onclick="myCollections()">
                <div id="userMyCollectionsNumber" class="number" id="userMyCollectionsNumber">
                    <?php
                        $QUERY=executeQuery("select * FROM collezioni where FK_UTENTE=".$_SESSION["ID"]);
                        echo $QUERY->num_rows; 
                    ?>
                </div>
                <div class="subtitle truncate">MY COLLECTIONS</div>
            </div>
            <div class="user-card col l2 m6 s12 waves-effect" id="userFollowedCollectionsCard" onclick="followedCollections()">
                <div class="number" id="userFollowedCollectionsNumber">
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

    <div id="collections" class="modal"></div>

    <div id="add_to_collection" style="display:none">
        <div class="modal-content" style="text-align: center; padding: 15px; background-color:#f6f7f9; margin-bottom:15px">
            <p id="collections_title" style="margin:6px 0; font-size: 18px"></p>
            <a style="font-size: 14px; color:#ff6e40" href="/account?fx=myCollections">Go to my Collections</a>
        </div>
        <ul class="modal-content collection" id="collections-container" style="padding:0; margin:0 15px;">

        </ul>
        <div class="modal-content" style="text-align:center">
            <a onCLick="newCollection()" style="font-size: 14px; color:#ff6e40; margin:6px 0; cursor:pointer">Create a new Collection</a>
        </div>
    </div>

    <div id="new_collection" style="display:none">
        <div class="modal-content" style="text-align: center; padding: 15px; background-color:#f6f7f9">
            <p style="margin:6px 0; font-size: 18px">Create a new Collection</p>
            <a style="font-size: 14px; color:#ff6e40" href="/user/<?php echo $_SESSION["ID"]; ?>?fx=myCollections">Go to my Collections</a>
        </div>
        <div class="modal-content" style="text-align:center">
            <div class="row">
                <form autocomplete="off" novalidate class="col s12" id="create_collection">
                    <div class="input-field col s12">
                        <input required placeholder="Collection Name" id="collection_name" type="text" class="validate">
                        <label for="collection_name">Name</label>
                    </div>
                    <div class="input-field col s12">
                        <textarea placeholder="Collection Description" id="collection_description" class="materialize-textarea" length="300" maxlength="300"></textarea>
                        <label for="collection_description">Description</label>
                    </div>
                </form>
            </div>
        </div>
        <div class="modal-footer">
            <a id="save_collection_button" onClick="submitCollection()" style="margin-left:6px" class="modal-action modal-close waves-effect waves-light btn-flat grey darken-3 white-text">Save</a>
            <a onClick="cancelCollectionCreate()" class="waves-effect btn-flat ">Cancel</a>
        </div>
    </div>

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

        <?php if(isset($_REQUEST["fx"]) && $_REQUEST["fx"]=="myCollections"): ?>
            _('userMyCollectionsCard').click();
        <?php endif; ?>

        <?php if(isset($_REQUEST["fx"]) && $_REQUEST["fx"]=="followedCollections"): ?>
            _('userFollowedCollectionsCard').click();
        <?php endif; ?>

        <?php if(isset($_REQUEST["fx"]) && $_REQUEST["fx"]=="likes"): ?>
            _('userLikesCard').click();
        <?php endif; ?>
    });

    //ACCOUNT CARDS
    function projects() {
        _('userFollowingCard').style.backgroundColor="#fff";
        _('userFollowersCard').style.backgroundColor="#fff";
        _('userProjectsCard').style.backgroundColor="#f5f5f5";
        _('userMyCollectionsCard').style.backgroundColor="#fff";
        _('userFollowedCollectionsCard').style.backgroundColor="#fff";
        _('userLikesCard').style.backgroundColor="#fff";
        var ajax = new XMLHttpRequest();
        var formdata = new FormData();
        formdata.append("fx", "projects");
        formdata.append("id", <?php echo $_SESSION["ID"] ?>);
        ajax.addEventListener("load", function (event) {
            var t = event.target.responseText;
            _("accountCardsResponse").innerHTML=t;
            Materialize.showStaggeredList('#projects');
            DISQUSWIDGETS.getCount({reset: true});
        }, false);

        ajax.addEventListener("error", function (event) {
            Materialize.toast("Error Occured", 2500);
        }, false);
        ajax.open("POST", "/accountCards.php");
        ajax.send(formdata);
    }

    function following() {
        _('userFollowingCard').style.backgroundColor="#f5f5f5";
        _('userFollowersCard').style.backgroundColor="#fff";
        _('userProjectsCard').style.backgroundColor="#fff";
        _('userMyCollectionsCard').style.backgroundColor="#fff";
        _('userFollowedCollectionsCard').style.backgroundColor="#fff";
        _('userLikesCard').style.backgroundColor="#fff";
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
        _('userFollowingCard').style.backgroundColor="#fff";
        _('userFollowersCard').style.backgroundColor="#f5f5f5";
        _('userProjectsCard').style.backgroundColor="#fff";
        _('userMyCollectionsCard').style.backgroundColor="#fff";
        _('userFollowedCollectionsCard').style.backgroundColor="#fff";
        _('userLikesCard').style.backgroundColor="#fff";
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

    function myCollections() {
        _('userFollowingCard').style.backgroundColor="#fff";
        _('userFollowersCard').style.backgroundColor="#fff";
        _('userProjectsCard').style.backgroundColor="#fff";
        _('userMyCollectionsCard').style.backgroundColor="#f5f5f5";
        _('userFollowedCollectionsCard').style.backgroundColor="#fff";
        _('userLikesCard').style.backgroundColor="#fff";
        var ajax = new XMLHttpRequest();
        var formdata = new FormData();
        formdata.append("fx", "myCollections");
        formdata.append("id", <?php echo $_SESSION["ID"] ?>);
        ajax.addEventListener("load", function (event) {
            var t = event.target.responseText;
            _("accountCardsResponse").innerHTML=t;
            Materialize.showStaggeredList('#myCollections');
        }, false);

        ajax.addEventListener("error", function (event) {
            Materialize.toast("Error Occured", 2500);
        }, false);
        ajax.open("POST", "/accountCards.php");
        ajax.send(formdata);
    }

    function followedCollections() {
        _('userFollowingCard').style.backgroundColor="#fff";
        _('userFollowersCard').style.backgroundColor="#fff";
        _('userProjectsCard').style.backgroundColor="#fff";
        _('userMyCollectionsCard').style.backgroundColor="#fff";
        _('userFollowedCollectionsCard').style.backgroundColor="#f5f5f5";
        _('userLikesCard').style.backgroundColor="#fff";
        var ajax = new XMLHttpRequest();
        var formdata = new FormData();
        formdata.append("fx", "followedCollections");
        formdata.append("id", <?php echo $_SESSION["ID"] ?>);
        ajax.addEventListener("load", function (event) {
            var t = event.target.responseText;
            _("accountCardsResponse").innerHTML=t;
            Materialize.showStaggeredList('#followedCollections');
        }, false);

        ajax.addEventListener("error", function (event) {
            Materialize.toast("Error Occured", 2500);
        }, false);
        ajax.open("POST", "/accountCards.php");
        ajax.send(formdata);
    }

    function likes() {
        _('userFollowingCard').style.backgroundColor="#fff";
        _('userFollowersCard').style.backgroundColor="#fff";
        _('userProjectsCard').style.backgroundColor="#fff";
        _('userMyCollectionsCard').style.backgroundColor="#fff";
        _('userFollowedCollectionsCard').style.backgroundColor="#fff";
        _('userLikesCard').style.backgroundColor="#f5f5f5";
        var ajax = new XMLHttpRequest();
        var formdata = new FormData();
        formdata.append("fx", "likes");
        formdata.append("id", <?php echo $_SESSION["ID"] ?>);
        ajax.addEventListener("load", function (event) {
            var t = event.target.responseText;
            _("accountCardsResponse").innerHTML=t;
            Materialize.showStaggeredList('#likes');
        }, false);

        ajax.addEventListener("error", function (event) {
            Materialize.toast("Error Occured", 2500);
        }, false);
        ajax.open("POST", "/accountCards.php");
        ajax.send(formdata);
    }
    
</script>

<script>
    function save(id, name) {
        updateCollections($("#"+id).data('projectid'));
        //alert ($("#"+id).data('projectid'));
        $('#collections').html('');
        $("#collections_title").text('Add "'+name+'" to a collection');
        $('#collections').html($('#add_to_collection').html());
        $('#save_collection_button').attr("data-selectedproject", $("#"+id).data('projectid'));
        
        $('#collections').openModal();
    }
    function newCollection () {
        $('#collections').html($('#new_collection').html());
    }

    function cancelCollectionCreate () {
        updateCollections($("#save_collection_button").data('selectedproject'));
        $('#collections').html($('#add_to_collection').html());
    }

    function submitCollection () {
        if($('#create_collection')[0].checkValidity()) {
            var ajax = new XMLHttpRequest();
            var formdata = new FormData();
            formdata.append("name", _("collection_name").value);
            formdata.append("description", _("collection_description").value);
            formdata.append("project_id", $("#save_collection_button").data('selectedproject'));
            formdata.append("user_id", <?php echo $_SESSION["ID"]; ?>);

            ajax.addEventListener("load", function (event) {
                var t = event.target.responseText;
                if (t) {
                    var res = $.parseJSON(t);
                    $('#collections').closeModal();
                    _("inCollection"+res.projectID).innerHTML=res.inCollection;
                    _("userMyCollectionsNumber").innerHTML=res.myCollections;
                    Materialize.toast(res.message, 2000);
                }
            }, false);
            ajax.addEventListener("error", function (event) {
                Materialize.toast('Error occured!', 2000);
            }, false);
            ajax.open("POST", "/createCollection");
            ajax.send(formdata);
        } else {
            Materialize.toast('Fill in all fields!', 2000)
        }
    }
    function updateCollections ($projectID) {
        var ajax = new XMLHttpRequest();
        var formdata = new FormData();
        formdata.append("projectID", $projectID);
        ajax.addEventListener("load", function (event) {
            var t = event.target.responseText;
            if (t) {
                $('#collections-container').html(t);
            }
        }, false);
        ajax.addEventListener("error", function (event) {
            Materialize.toast('Error occured!', 2000);
        }, false);
        ajax.open("POST", "/getCollections");
        ajax.send(formdata);
    }

    function addProjectToCollection ($projectID, $collectionID) {
        var ajax = new XMLHttpRequest();
        var formdata = new FormData();
        formdata.append("projectID", $projectID);
        formdata.append("collectionID", $collectionID);
        ajax.addEventListener("load", function (event) {
            var t = event.target.responseText;
            if (t) {
                var res = $.parseJSON(t);
                $('#collections').closeModal();
                _("inCollection"+res.projectID).innerHTML=res.inCollection;
                Materialize.toast(res.message, 2000);
            }
        }, false);
        ajax.addEventListener("error", function (event) {
            Materialize.toast('Error occured!', 2000);
        }, false);
        ajax.open("POST", "/addProjectToCollection");
        ajax.send(formdata);
    }

    function likeProject(index) {
        var ajax = new XMLHttpRequest();
        var formdata = new FormData();
        formdata.append("projectID", index);
        ajax.addEventListener("load", function (event) {
            var t = event.target.responseText;
            if (t) {
                //alert(t);
                var res = jQuery.parseJSON(t);
                _("projectLikes"+index).innerHTML=res.likes;
                if (res.like) {
                    _("projectLikeIcon"+index).style.color="#ff6e40";
                } else {
                    _("projectLikeIcon"+index).style.color="#777";
                }  
                _("userLikesNumber").innerHTML=res.userLikes;
            }
        }, false);
        ajax.addEventListener("error", function (event) {
            Materialize.toast('Error occured!', 2000);
        }, false);
        ajax.open("POST", "/likeProject");
        ajax.send(formdata);
    }

    function followCollection(collectionID) {
        var ajax = new XMLHttpRequest();
        var formdata = new FormData();
        formdata.append("collectionID", collectionID);
        ajax.addEventListener("load", function (event) {
            var t = event.target.responseText;
            if (t) {
                var res = $.parseJSON(t);
                if (res.follow) {
                    _("followCollectionText"+collectionID).innerHTML="Unfollow";
                    _("followCollectionIcon"+collectionID).innerHTML="clear";
                } else {
                    _("followCollectionText"+collectionID).innerHTML="Follow";
                    _("followCollectionIcon"+collectionID).innerHTML="add";
                }  
                try {
                    if (res.collectionFollowersNumber == 0) {
                        _('collectionFollowersNumber'+collectionID).innerHTML="";
                    } else {
                        _('collectionFollowersNumber'+collectionID).innerHTML="• <b>"+res.collectionFollowersNumber+"</b> FOLLOWERS";
                    }     
                } catch (err) {
                }
                _("userFollowedCollectionsNumber").innerHTML=res.userFollows;
            }
        }, false);
        ajax.addEventListener("error", function (event) {
            Materialize.toast('Error occured!', 2000);
        }, false);
        ajax.open("POST", "/followCollection");
        ajax.send(formdata);
    }

    function editCollection(id) {
        window.location.replace("/collection/"+id+"?fx=edit");
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
        ajax.open("POST", "/follow");
        ajax.send(formdata);
    });
</script>

<?php require_once 'quarto.php'; ?>