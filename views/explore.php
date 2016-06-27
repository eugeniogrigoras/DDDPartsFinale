<?php require_once 'primo.php'; ?>
<title>Explore our DDDWorld</title>
<meta name="description" content="Explore">
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

    <div class="row" style="padding:24px">
    	<div class="col l3 m4 s12">
		    <div class="switch center" style="margin-bottom:48px">
		    	<label>
		      	Projects
		      		<input type="checkbox">
		      		<span class="lever my-switch"></span>
		      	Collections
		    	</label>
		  	</div>
		    <!-- <div class="input-field col s12">
		    	<select multiple>
		      		<optgroup label="Newest">
		        		<option value="1">Option 1</option>
		        		<option value="2">Option 2</option>
		      		</optgroup>
		      		<optgroup label="team 2">
		        		<option value="3">Option 3</option>
		        		<option value="4">Option 4</option>
		      		</optgroup>
		    	</select>
		   	 	<label>Optgroups</label>
		  	</div> -->
		  	<div class="input-field col s12">
		    	<select multiple>
			      	<option value="" disabled selected>Choose your option</option>
			      	<option value="1">Option 1</option>
			      	<option value="2">Option 2</option>
			      	<option value="3">Option 3</option>
		    	</select>
		    	<label>Materialize Multiple Select</label>
		  	</div>
		</div>
    </div>

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