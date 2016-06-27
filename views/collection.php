<?php require_once 'primo.php'; ?>
<?php
    $collections=executeQuery("select utenti.ID as ID_UTENTE, utenti.COGNOME as COGNOME_UTENTE, utenti.NOME as NOME_UTENTE, utenti.EMAIL as EMAIL_UTENTE, collezioni.TITOLO as TITOLO_COLLEZIONE, collezioni.DATA_CREAZIONE, collezioni.DESCRIZIONE as DESCRIZIONE_COLLEZIONE from collezioni, utenti where collezioni.ID=$id and collezioni.FK_UTENTE=utenti.ID");
    $collection=$collections->fetch_assoc();
?>
<title>
    <?php echo $collection["TITOLO_COLLEZIONE"] ?> by <?php echo $collection["NOME_UTENTE"]." ".$collection["COGNOME_UTENTE"]; ?>
</title>
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
<?php 
    $projects=executeQuery("select DISTINCT progetti.ID as ID_PROGETTO, progetti.NOME as NOME_PROGETTO, progetti.DESCRIZIONE as DESCRIZIONE_PROGETTO, progetti.FK_UTENTE as ID_UTENTE, categorie_secondarie.NOME as NOME_CATEGORIA_SECONDARIA, categorie_primarie.NOME AS NOME_CATEGORIA_PRIMARIA, utenti.NOME as NOME_UTENTE, utenti.COGNOME as COGNOME_UTENTE, utenti.EMAIL as EMAIL_UTENTE FROM collezioni_composte_da_progetti, progetti, categorie_primarie, categorie_secondarie, utenti WHERE collezioni_composte_da_progetti.FK_PROGETTO=progetti.ID AND collezioni_composte_da_progetti.FK_COLLEZIONE=$id AND categorie_secondarie.FK_CATEGORIA_PRIMARIA=categorie_primarie.ID AND utenti.ID=progetti.FK_UTENTE AND progetti.FK_CATEGORIA_SECONDARIA=categorie_secondarie.ID ORDER BY collezioni_composte_da_progetti.DATA_AGGIUNTA DESC");
 ?>
<main>
    <div class="container main-content row z-depth-1">
        <div class="card col s12" style="padding:0!important; margin:0!important; border-top:3px solid #ff6e40">
            <div class="activator waves-effect background" style="display:block; padding:48px; text-align:center; background-color:#212121;">
                <h4 class="white-text truncate activator" style="text-transform:capitalize; margin-top:0; margin-bottom:48px"><?php echo $collection["TITOLO_COLLEZIONE"]; ?></h4>
                <?php 
                    if (logged()) :
                    if (!myCollection($id)) :
                ?>
                <a id="followCollectionText" onCLick="followCollection(<?php echo $id; ?>)" style="padding:0 15px; font-size:14px;" class="deep-orange accent-2 white-text white-text waves-effect btn-flat">
                    <?php
                        if (logged()) {
                            $userID=$_SESSION["ID"];
                            $QUERY=executeQuery("select * from  utenti_seguono_collezioni where FK_COLLEZIONE=$id and FK_UTENTE=$userID");  
                            if ($QUERY->num_rows == 0) {
                                echo "Follow Collection";
                            } else {
                                echo "Unfollow Collection";
                            }
                        } else {
                            echo "Follow Collection";
                        }   
                    ?>
                </a>
                <?php else: ?>
                    <a id="editCollectionButton" onCLick="editCollection()" style="padding:0 15px; font-size:14px;" class="deep-orange accent-2 white-text white-text waves-effect btn-flat">Edit Collection</a>
                <?php endif; ?>

                <?php else: ?>
                    <a id="followCollectionText" onCLick="followCollection(<?php echo $id; ?>)" style="padding:0 15px; font-size:14px;" class="deep-orange accent-2 white-text white-text waves-effect btn-flat">Follow Collection</a>
                <?php endif; ?>
            </div>
            <div style="padding:12px; background-color:#fff">
                <div class="valign-wrapper">
                <div id="avatar" class="valign" style="margin:0; margin-right:12px; padding:0; width:40px; height:40px; background-image:url('<?php echo requestPathUser($collection["NOME_UTENTE"], $collection["COGNOME_UTENTE"], $collection["EMAIL_UTENTE"])."/profile.jpg";?>')">   
                </div>
                <div class="valign truncate" style="color:#999;"><span id="collectionProjectsNumber"><?php echo $projects->num_rows; ?></span> posts by <p style="margin:0; display:inline"><b><a href="/user/<?php echo $collection["ID_UTENTE"] ?>"><?php echo $collection["NOME_UTENTE"]." ".$collection["COGNOME_UTENTE"]; ?></a></b></p></div>
                </div>
            </div>
            <div class="card-reveal" style=" text-align:left; color:#444; width:inherit!important">
                <span class="card-title"><i class="material-icons right noselect">close</i><?php echo $collection["TITOLO_COLLEZIONE"] ?> - Information</span>
                <p class="valign-wrapper"><i class="valign material-icons noselect" style="margin-right:20px;">supervisor_account</i>Followers: 
                <b style="margin-left:6px" id="collectionFollowersNumber">
                <?php 
                    $FOLLOW = executeQuery("select * from utenti_seguono_collezioni where FK_COLLEZIONE=".$id); 
                    echo $FOLLOW->num_rows;
                ?>
                </b>
                </p>
                <p class="valign-wrapper"><i class="valign material-icons noselect" style="margin-right:20px;">description</i><?php echo $collection["DESCRIZIONE_COLLEZIONE"];?></p>         
            </div>
        </div>
    </div>
    <div id="accountCardsResponse">
        <ul id="projects">
            <div class="row projects container">
                <?php
                    while ($project=$projects->fetch_assoc()) : 
                ?>
                <li>
                    <div class="col s12 m6 l4" onmouseover="$('#save<?php echo $project["ID_PROGETTO"] ?>').show()" onmouseout="$('#save<?php echo $project["ID_PROGETTO"] ?>').hide()">
                        <div class="z-depth-1 card hoverable" style="background-color:white">
                            <div class="card-image waves-effect waves-block waves-light activator" style="padding:12px 15px; background-image:url('<?php echo "/users/".$project["NOME_UTENTE"]."-".$project["COGNOME_UTENTE"]."-".$project["EMAIL_UTENTE"]."/".$project["ID_PROGETTO"]; ?>/projectWallpaper.jpg')">
                                <a data-projectid="<?php echo $project["ID_PROGETTO"] ?>" onClick="save(this.id, this.name)" name="<?php echo $project["NOME_PROGETTO"] ?>" id="save<?php echo $project["ID_PROGETTO"] ?>" class="white-text right waves-effect waves-light btn-flat" style="background-color:rgba(33,33,33,0.5); padding:0 15px; font-size:12px; display:none">Save
                                    <i class="material-icons left" style="font-size:17px">add</i>
                                </a>
                            </div>
                            <div class="card-content truncate" style="padding:12px 15px;">
                                <p class="truncate" style="font-size:20px; margin-bottom:6px;"><a href="/project/<?php echo $project["ID_PROGETTO"] ?>" style="color:#212121!important"><?php echo $project["NOME_PROGETTO"] ?></a></p>
                                <p class="truncate" style="font-size:13px">by <a href="/user/<?php echo $project["ID_UTENTE"]; ?>"><?php echo $project["NOME_UTENTE"]." ".$project["COGNOME_UTENTE"] ?></a></p>
                            </div>
                            <div class="card-content truncate" style="padding:12px 15px; border-top:1px solid #ddd">
                                <p style="font-size:13px; color:#999; margin-bottom:12px" class="truncate">Submitted in <a href="#"><?php echo $project["NOME_CATEGORIA_SECONDARIA"]?></a></p>
                                <div class="col s4 truncate" style="padding:0;">
                                    <?php 
                                        $LIKES = executeQuery("select * from utenti_like_progetti where FK_PROGETTO=".$project["ID_PROGETTO"]);
                                        if (logged()) $USER_LIKE = executeQuery("select * from utenti_like_progetti where FK_PROGETTO=".$project["ID_PROGETTO"]." and FK_UTENTE=".$_SESSION["ID"]);
                                    ?>
                                    <p class="center-align left valign-wrapper" style="color:#777; cursor:pointer" onClick="likeProject(<?php echo $project["ID_PROGETTO"]; ?>)">
                                        <i id="projectLikeIcon<?php echo $project["ID_PROGETTO"]; ?>" class="material-icons noselect valign" style="margin-right:5px; font-size:15px;
                                        <?php 
                                            if ((logged()) && ($USER_LIKE->num_rows > 0)) echo "color:#ff6e40";
                                        ?>
                                        ">
                                        favorite
                                        </i>
                                        <span class="valign" style="font-size:13px" id="projectLikes<?php echo $project["ID_PROGETTO"]; ?>">
                                            <?php  echo $LIKES->num_rows; ?>
                                        </span>
                                    </p>
                                </div>
                                <div class="col s4 truncate" style="padding:0;">
                                    <?php $link= 'http://dddparts.altervista.org/project/'.$project["ID_PROGETTO"]."#disqus_thread" ?>
                                    <p class="center-align valign-wrapper fit-content no_style" style="cursor:pointer; color:#777; margin:auto" onClick="location.href='<?php echo $link ?>'">
                                        <i class="material-icons noselect valign" style="margin-right:5px; font-size:15px">comment</i>
                                        <a class="" href="<?php echo $link; ?>" style="font-size:13px">0</a>
                                    </p>
                                </div>
                                <div class="col s4 truncate" style="padding:0;">
                                    <p class="center-align right valign-wrapper" style="color:#777;">
                                        <i class="material-icons noselect valign" style="margin-right:5px; font-size:15px">move_to_inbox
                                        </i>
                                        <span id="inCollection<?php echo $project["ID_PROGETTO"]; ?>" class="valign" style="font-size:13px">
                                            <?php $COLLECTION = executeQuery("select * from collezioni_composte_da_progetti where FK_PROGETTO=".$project["ID_PROGETTO"]); echo $COLLECTION->num_rows; ?>
                                        </span>
                                    </p>
                                </div>
                            </div>
                            <div class="card-reveal">
                                <span class="card-title"><i class="material-icons right noselect">close</i><?php echo $project["NOME_PROGETTO"]?></span>
                                <p class="valign-wrapper"><i class="valign material-icons noselect" style="margin-right:20px;">insert_drive_file</i>Files: <b style="margin-left:6px"><?php 
                                        $QUERY=executeQuery("select * from parti_3d where FK_PROGETTO=".$project["ID_PROGETTO"]); 
                                        echo $QUERY->num_rows;
                                    ?></b></p>
                                <p class="valign-wrapper"><i class="valign material-icons noselect" style="margin-right:20px;">file_download</i>Downloads: <b style="margin-left:6px"><?php 
                                        $QUERY=executeQuery("select NUMERO_DOWNLOAD from progetti where ID=".$project["ID_PROGETTO"]); 
                                        $numeroDownload=$QUERY->fetch_assoc();
                                        echo $numeroDownload["NUMERO_DOWNLOAD"];
                                    ?></b></p>
                            </div>
                        </div>
                    </div>
                </li>
                <?php endwhile; ?>
            </div>
        </ul>
    </div>

    <?php if (logged()): ?>

    <div id="collections" class="modal"></div>

    <div id="add_to_collection" style="display:none">
        <div class="modal-content" style="text-align: center; padding: 15px; background-color:#f6f7f9">
            <p id="collections_title" style="margin:6px 0; font-size: 18px"></p>
            <a style="font-size: 14px; color:#ff6e40" href="/account?fx=myCollections">Go to my Collections</a>
        </div>
        <ul class="modal-content collection" id="collections-container" style="padding:0; margin:0 15px">

        </ul>
        <div class="modal-content" style="text-align:center">
            <a onCLick="newCollection()" style="font-size: 14px; color:#ff6e40; margin:6px 0; cursor:pointer">Create a new Collection</a>
        </div>
    </div>

    <div id="new_collection" style="display:none">
        <div class="modal-content" style="text-align: center; padding: 15px; background-color:#f6f7f9">
            <p style="margin:6px 0; font-size: 18px">Create a new Collection</p>
            <a style="font-size: 14px; color:#ff6e40" href="/user/<?php echo $_SESSION["ID"]; ?>?fx=myColections">Go to my Collections</a>
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

    <?php endif; ?>
</main>

<?php require_once 'terzo.php'; ?>
<script>
    function _(el){
        return document.getElementById(el);
    }
</script>

<?php if (logged()) : ?>

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
                if ($collectionID==<?php echo $id ?>) {
                    _("collectionProjectsNumber").innerHTML=res.collectionProjectsNumber;
                }
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
                var res = jQuery.parseJSON(t);
                _("projectLikes"+index).innerHTML=res.likes;
                if (res.like) {
                    _("projectLikeIcon"+index).style.color="#ff6e40";
                } else {
                    _("projectLikeIcon"+index).style.color="#777";
                }   
            }
        }, false);
        ajax.addEventListener("error", function (event) {
            Materialize.toast('Error occured!', 2000);
        }, false);
        ajax.open("POST", "/likeProject");
        ajax.send(formdata);
    }
</script>

<script>
    function followCollection(collectionID) {
        var ajax = new XMLHttpRequest();
        var formdata = new FormData();
        formdata.append("collectionID", collectionID);
        ajax.addEventListener("load", function (event) {
            var t = event.target.responseText;
            if (t) {
                var res = $.parseJSON(t);
                if (res.follow) {
                    _("followCollectionText").innerHTML="Unfollow Collection";
                } else {
                    _("followCollectionText").innerHTML="Follow Collection";
                }  
                _('collectionFollowersNumber').innerHTML=res.collectionFollowersNumber;
                //_("userFollowedCollectionsNumber").innerHTML=res.userFollows;
            }
        }, false);
        ajax.addEventListener("error", function (event) {
            Materialize.toast('Error occured!', 2000);
        }, false);
        ajax.open("POST", "/followCollection");
        ajax.send(formdata);
    }

    function editCollection() {
        Materialize.toast('Under Developement!', 2000);
    }
</script>

<?php else: ?>
<script>
    function save(id, name) {
        Materialize.toast("You need to login first", 2000);
    }
    function likeProject(index) {
        Materialize.toast("You need to login first", 2000);
    }
    function followCollection(collectionID) {
        Materialize.toast("You need to login first", 2000);
    }
    function editCollection() {
        Materialize.toast("You need to login first", 2000);
    }
</script>
<?php endif; ?>

<?php
    if (isset($_REQUEST["fx"])) {
        if ($_REQUEST["fx"]=="edit") echo "<script>_('editCollectionButton').click();</script>";
    }
?>

<?php require_once 'quarto.php'; ?>