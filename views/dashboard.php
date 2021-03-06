<?php require_once 'primo.php'; ?>
<title>Dashboard - What's New?</title>
<meta name="description" content="Dashboard – Look into things you're interested in">
<?php
	$items_per_group = 10;
	$userID=$_SESSION["ID"];

	$get_total_rows = 0;
	$results = executeQuery("SELECT 'PROGETTO' as TYPE, progetti.ID as ID, 'NULL' as ID_COLLEZIONE, progetti.FK_UTENTE as UTENTE, progetti.DATA_CREAZIONE as DATA FROM progetti, utenti_seguono_utenti WHERE utenti_seguono_utenti.FK_UTENTE=$userID AND progetti.FK_UTENTE=utenti_seguono_utenti.FK_UTENTE_SEGUITO UNION SELECT 'COLLEZIONE' AS TYPE, collezioni.ID AS ID, 'NULL' as ID_COLLEZIONE, collezioni.FK_UTENTE as UTENTE, collezioni.DATA_CREAZIONE as DATA FROM collezioni, utenti_seguono_utenti WHERE utenti_seguono_utenti.FK_UTENTE=$userID AND collezioni.FK_UTENTE=utenti_seguono_utenti.FK_UTENTE_SEGUITO UNION SELECT 'LIKE' AS TYPE, utenti_like_progetti.FK_PROGETTO AS ID, 'NULL' as ID_COLLEZIONE, utenti_like_progetti.FK_UTENTE AS UTENTE, utenti_like_progetti.DATA AS DATA_CREAZIONE FROM utenti_like_progetti, utenti_seguono_utenti, utenti WHERE utenti_seguono_utenti.FK_UTENTE=$userID AND utenti_like_progetti.FK_UTENTE=utenti_seguono_utenti.FK_UTENTE_SEGUITO UNION SELECT 'PROGETTO_COLLEZIONE' AS TYPE, collezioni_composte_da_progetti.FK_PROGETTO AS ID, collezioni_composte_da_progetti.FK_COLLEZIONE AS ID_COLLEZIONE, utenti.ID AS UTENTE, collezioni_composte_da_progetti.DATA_AGGIUNTA AS DATA
FROM collezioni_composte_da_progetti, utenti, collezioni
WHERE collezioni_composte_da_progetti.FK_COLLEZIONE IN (SELECT utenti_seguono_collezioni.FK_COLLEZIONE FROM utenti_seguono_collezioni WHERE utenti_seguono_collezioni.FK_UTENTE=$userID)
AND collezioni.FK_UTENTE=utenti.ID
AND collezioni.ID=collezioni_composte_da_progetti.FK_COLLEZIONE
UNION SELECT 'FOLLOW' AS TYPE, utenti_seguono_utenti.FK_UTENTE_SEGUITO AS ID, 'NULL' AS ID_COLLEZIONE, utenti_seguono_utenti.FK_UTENTE AS UTENTE, utenti_seguono_utenti.DATA AS DATA FROM utenti_seguono_utenti WHERE utenti_seguono_utenti.FK_UTENTE IN (SELECT utenti_seguono_utenti.FK_UTENTE_SEGUITO FROM utenti_seguono_utenti WHERE utenti_seguono_utenti.FK_UTENTE=$userID) UNION
SELECT 'FOLLOW_COLLEZIONE' AS TYPE, utenti_seguono_collezioni.FK_COLLEZIONE AS ID, 'NULL' AS ID_COLLEZIONE, utenti_seguono_collezioni.FK_UTENTE AS UTENTE, utenti_seguono_collezioni.DATA AS DATA FROM utenti_seguono_utenti, utenti_seguono_collezioni WHERE utenti_seguono_utenti.FK_UTENTE=$userID AND utenti_seguono_collezioni.FK_UTENTE=utenti_seguono_utenti.FK_UTENTE_SEGUITO ORDER by DATA DESC");

	if($results){
		$get_total_rows = $results->num_rows; 
	}
	//break total records into pages
	$total_groups= ceil($get_total_rows/$items_per_group);	
?>
<style>
	a {
		color:#ff6e40;
	}
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
    .card-panel {
    	padding: 12px;
    }
</style>
<?php require_once 'secondo.php'; ?>
<main>
    <div class="container main-content row">
    	<div id="accountCardsResponse"></div>
    	<div id="loader" class="center-align col s12" style="text-align: center; display:none">
			<div class="preloader-wrapper active">
			    <div class="spinner-layer spinner-blue">
			        <div class="circle-clipper left">
			          	<div class="circle"></div>
			        </div>
			        <div class="gap-patch">
			          	<div class="circle"></div>
			        </div>
			        <div class="circle-clipper right">
			          	<div class="circle"></div>
			        </div>
			    </div>

			    <div class="spinner-layer spinner-red">
			        <div class="circle-clipper left">
			          	<div class="circle"></div>
			        </div>
			        <div class="gap-patch">
			          	<div class="circle"></div>
			        </div>
			        <div class="circle-clipper right">
			          	<div class="circle"></div>
			        </div>
			    </div>

			    <div class="spinner-layer spinner-yellow">
			        <div class="circle-clipper left">
			          	<div class="circle"></div>
			        </div>
			        <div class="gap-patch">
			          	<div class="circle"></div>
			        </div>
			        <div class="circle-clipper right">
			          	<div class="circle"></div>
			        </div>
			    </div>

			    <div class="spinner-layer spinner-green">
			        <div class="circle-clipper left">
			          	<div class="circle"></div>
			        </div>
			        <div class="gap-patch">
			          	<div class="circle"></div>
			        </div>
			        <div class="circle-clipper right">
			          	<div class="circle"></div>
			        </div>
			    </div>
			</div>
        </div>
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

    function getElementsById(elementID){
	    var elementCollection = new Array();
	    var allElements = document.getElementsByTagName("*");
	    for(i = 0; i < allElements.length; i++){
	        if(allElements[i].id == elementID)
	            elementCollection.push(allElements[i]);

	    }
	    return elementCollection;
	}

	$(document).ready(function() {
		var track_load = 0; //total loaded record group(s)
		var loading  = false; //to prevents multipal ajax loads
		var total_groups = <?=$total_groups;?>; //total record group(s)
		
		$('#accountCardsResponse').load("/autoload_process.php", {'group_no':track_load}, function() {
			track_load++;
		}); //load first group
		
		$(window).scroll(function() { //detect page scroll
			
			if($(window).scrollTop() + $(window).height() == $(document).height())  //user scrolled to bottom of the page?
			{
				
				if(track_load <= total_groups && loading==false) //there's more data to load
				{
					loading = true; //prevent further ajax loading
					$('#loader').show(); //show loading image
					
					//load data from the server using a HTTP POST request
					$.post('/autoload_process.php',{'group_no': track_load}, function(data){
										
						$("#accountCardsResponse").append(data); //append received data into the element

						//hide loading image
						$('#loader').hide(); //hide loading image once data is received
						
						track_load++; //loaded group increment
						loading = false; 
					
					}).fail(function(xhr, ajaxOptions, thrownError) { //any errors?
						
						alert(thrownError); //alert with HTTP error
						$('#loader').hide(); //hide loading image
						loading = false;
					
					});
					
				}
			}
		});
	});
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
                    var elements = getElementsById("inCollection"+res.projectID);
                    for (var i = 0; i < elements.length; i++) {
                    	elements[i].innerHTML=res.inCollection;
                    }
                   // _("inCollection"+res.projectID).innerHTML
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
                var elements = getElementsById("inCollection"+res.projectID);
                for (var i = 0; i < elements.length; i++) {
                	elements[i].innerHTML=res.inCollection;
                }
                //_("inCollection"+res.projectID).innerHTML=res.inCollection;
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
                var res = $.parseJSON(t);
                var elements = getElementsById("projectLikes"+index);
                for (var i = 0; i < elements.length; i++) {
                	elements[i].innerHTML=res.likes;
                }
                if (res.like) {
                	var elements = getElementsById("projectLikeIcon"+index);
	                for (var i = 0; i < elements.length; i++) {
	                	elements[i].style.color="#ff6e40";
	                }
                    //_("projectLikeIcon"+index).style.color="#ff6e40";
                } else {
                	var elements = getElementsById("projectLikeIcon"+index);
	                for (var i = 0; i < elements.length; i++) {
	                	elements[i].style.color="#777";
	                }
                    //_("projectLikeIcon"+index).style.color="#777";
                }   
            }
        }, false);
        ajax.addEventListener("error", function (event) {
            Materialize.toast('Error occured!', 2000);
        }, false);
        ajax.open("POST", "/likeProject");
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

            var elements = getElementsById('usersFollowingNumber'+res.requestID);
            for (var i = 0; i < elements.length; i++) {
            	elements[i].innerHTML=res.usersFollowingNumber;
            }
            var elements = getElementsById('usersFollowersNumber'+res.requestID);
            for (var i = 0; i < elements.length; i++) {
            	elements[i].innerHTML=res.usersFollowersNumber;
            }
            try {
            	var elements = getElementsById('usersFollowingNumber'+res.sessionID);
	            for (var i = 0; i < elements.length; i++) {
	            	elements[i].innerHTML=res.usersFollowingNumber;
	            }
	            var elements = getElementsById('usersFollowersNumber'+res.sessionID);
	            for (var i = 0; i < elements.length; i++) {
	            	elements[i].innerHTML=res.usersFollowersNumber;
	            }
            } catch (err) {
                //alert(err);
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
        ajax.open("POST", "/follow");
        ajax.send(formdata);
    }); 

    $("input[type=checkbox]").change(function(){
        var ajax = new XMLHttpRequest();
        var formdata = new FormData();
        formdata.append("id", this.id);

        ajax.addEventListener("load", function (event) {
            var res = $.parseJSON(event.target.responseText);
            Materialize.toast(res.message, 2000);
            
            var elements = getElementsById('userFollowingNumber'+res.requestID);
            for (var i = 0; i < elements.length; i++) {
            	elements[i].innerHTML=res.usersFollowingNumber;
            }
            var elements = getElementsById('userFollowersNumber'+res.requestID);
            for (var i = 0; i < elements.length; i++) {
            	elements[i].innerHTML=res.usersFollowersNumber;
            }
            try {
            	var elements = getElementsById('userFollowingNumber'+res.sessionID);
	            for (var i = 0; i < elements.length; i++) {
	            	elements[i].innerHTML=res.usersFollowingNumber;
	            }
	            var elements = getElementsById('userFollowersNumber'+res.sessionID);
	            for (var i = 0; i < elements.length; i++) {
	            	elements[i].innerHTML=res.usersFollowersNumber;
	            }
            } catch (err) {
                //alert(err);
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
        ajax.open("POST", "/follow");
        ajax.send(formdata);
    });

    function followCollection(collectionID) {
        var ajax = new XMLHttpRequest();
        var formdata = new FormData();
        formdata.append("collectionID", collectionID);
        ajax.addEventListener("load", function (event) {
            var t = event.target.responseText;
            if (t) {
                var res = $.parseJSON(t);
                if (res.follow) {
                	var elements = getElementsById("followCollectionText"+collectionID);
		            for (var i = 0; i < elements.length; i++) {
		            	elements[i].innerHTML="Unfollow";
		            }
		            var elements = getElementsById("followCollectionIcon"+collectionID);
		            for (var i = 0; i < elements.length; i++) {
		            	elements[i].innerHTML="clear";
		            }
                    //_("followCollectionText"+collectionID).innerHTML="Unfollow";
                    //_("followCollectionIcon"+collectionID).innerHTML="clear";
                } else {
                	var elements = getElementsById("followCollectionText"+collectionID);
		            for (var i = 0; i < elements.length; i++) {
		            	elements[i].innerHTML="Follow";
		            }
		            var elements = getElementsById("followCollectionIcon"+collectionID);
		            for (var i = 0; i < elements.length; i++) {
		            	elements[i].innerHTML="add";
		            }
                   	// _("followCollectionText"+collectionID).innerHTML="Follow";
                    //_("followCollectionIcon"+collectionID).innerHTML="add";
                }  
                try {
                    if (res.collectionFollowersNumber == 0) {
                    	var elements = getElementsById('collectionFollowersNumber'+collectionID);
			            for (var i = 0; i < elements.length; i++) {
			            	elements[i].innerHTML="";
			            }
                        //_('collectionFollowersNumber'+collectionID).innerHTML="";
                    } else {
                    	var elements = getElementsById('collectionFollowersNumber'+collectionID);
			            for (var i = 0; i < elements.length; i++) {
			            	elements[i].innerHTML="• <b>"+res.collectionFollowersNumber+"</b> FOLLOWERS";
			            }
                        //_('collectionFollowersNumber'+collectionID).innerHTML="• <b>"+res.collectionFollowersNumber+"</b> FOLLOWERS";
                    }     
                } catch (err) {
                }
                //_("userFollowedCollectionsNumber").innerHTML=res.userFollows;
            }
        }, false);
        ajax.addEventListener("error", function (event) {
            Materialize.toast('Error occured!', 2000);
        }, false);
        ajax.open("POST", "/followCollection");
        ajax.send(formdata);
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
    function editCollection(id) {
        Materialize.toast("You need to login first", 2000);
    }
</script>
<?php endif; ?>

<?php require_once 'quarto.php'; ?>