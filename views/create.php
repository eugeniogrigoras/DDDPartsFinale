<?php require_once 'primo.php'; ?>
<title>Create</title>
<style>
    div.main-content {
        padding-top: 24px;
        padding-bottom: 24px;
    }

    div.main-content form {
        background-color: white;
    }

    div.title {
        background-color: #444;
        color:white;
        padding:24px;
        font-weight: 300;
    }

    div.row {
        margin-bottom: 0!important;
    }
    div.form {
        padding:35.25px 24px!important;
    }

    div.input-field label{
        width:inherit!important;
    }
    .chip {
        margin:3px;
        border-radius: 0!important;
    }
    .tabs .indicator {
      position: absolute;
      bottom: 0;
      height: 2px;
      background-color: rgba(255,109,64,1);
      will-change: left, right;
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
    <div class="container main-content row">
        <form autocomplete="off" id="create" action="/form.php" method="post" enctype="multipart/form-data" class="col s12 z-depth-1">
            <div class="row title">Project <?php if(isset($message)) echo "- ".$message ?></div>
        	<input type="hidden" value="create" name="getpage">
            <div class="row">
                <div class="col s12" style="padding:0!important">
                    <ul class="tabs">
                        <li class="tab col s3"><a class="active" href="#files" style="color:rgba(255,109,64,1)">Files</a></li>
                        <li class="tab col s3"><a href="#details" style="color:rgba(255,109,64,1)">Details</a></li>
                    </ul>
                </div>
                <!--FILES-->
                <div id="files" class="col s12">
                    <div style="text-align:center; margin:15px 0;">
                        <a style="font-size:15px;" class="deep-orange accent-2 white-text waves-effect waves-light btn-flat" onclick="$('#filesToUpload').click();">Select
                            <i class="material-icons right">file_upload</i>
                        </a>
                    </div>
                    <ul class="collection" id="uploadedFiles"></ul>
                    <input style="display:none" multiple type="file" id="filesToUpload">
                </div>
                <!--DETAILS-->
                <div id="details" class="col s12">
                    <div class="row form">
                        <div class="col s12 m6 l4 offset-l4 card offset-m3" style="padding:0; margin-top:0; margin-bottom:35.25px">
                            <div id="projectWallpaper" class="card-image waves-effect waves-block waves-light" onclick="chooseImage()" style="background-image:url('/img/bg1.jpg')">
                                <input value="img/bg1.jpg" accept=".jpg,.jpeg" type="file" name="fileToUpload" id="fileToUpload" style="display:none;">
                            </div>
                        </div>
                        <div class="input-field col s12">
                            <input required name="title" id="title" type="text" class="validate">
                            <label data-error="Wrong!" for="title">Title</label>
                        </div>
                        <div class="input-field col s12">
                            <textarea name="description" id="description" class="materialize-textarea" max-length="300"></textarea>
                            <label for="description">Description</label>
                        </div>
                        <div class="input-field col l6 m6 s12">
                            <select id="category" required onchange="categorySelect(this)" name="category">
                                <option value="" disabled selected>Category</option>
                                <?php $record = executeQuery("select * from categorie_primarie"); ?>
                                <?php while ($riga=$record->fetch_assoc()) : ?>
                                    <option value="<?php echo $riga['ID'] ?>"><?php echo $riga['NOME']; ?></option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                        <div class="input-field col l6 m6 s12">
                            <select id="subcategory" required disabled name="subcategory">
                                <option value="" disabled selected>Subcategory</option>
                            </select>
                        </div>
                        <div class="input-field col s12">
                            <input id="tag-input" type="text" class="validate">
                            <label for="tag-input">Tag</label>
                        </div>
                        <div class="input-field col s12">
                            <div id="tags"></div>
                        </div>
                        <div class=" col l12 m12 s12">
                            <br><br>
                        </div>
                        <div class=" col l12 m12 s12">
                            <a style="font-size:15px;" class="deep-orange accent-2 white-text right waves-effect waves-light btn-flat" onclick="validate()">Create
                                <i class="material-icons right">send</i>
                            </a>
                        </div>
                        <button id="submit" type="submit" name="submit" style="display:none">
                    </div>
                </div> 
            </div>
        </form>
    </div>
</main>

<?php require_once 'terzo.php'; ?>

<script>
    function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            
            reader.onload = function (e) {
                $('#projectWallpaper').attr('style', "background-image:url("+e.target.result+")");
            }
            
            reader.readAsDataURL(input.files[0]);
        }
    }

    $("#fileToUpload").change(function(){
        readURL(this);
    });

    function chooseImage() {
        document.getElementById('fileToUpload').click();
        console.log("Choosed!");
    }

    var names = [];
    var submit = false;

    function deleteProjectFolder () {
        var xhttp = new XMLHttpRequest();
        xhttp.open("DELETE", "/deleteProjectFolder.php");
        xhttp.send();
    }

    window.onbeforeunload = function () {
        if (!submit) {
            deleteProjectFolder();
        } 
    };

    $(document).ready(function() {
        
        deleteProjectFolder();

        var arr = ['png', 'jpg', 'zip', 'stl', 'jpeg', 'pdf', 'txt', 'obj'];

        var j = 0;

        $("#filesToUpload").change(function() {
            var files = _("filesToUpload").files;
            for (var i = 0, f; f = files[i]; i++) {
                j++;
                names[j]=f.name;
                var extension = f.name.substr((f.name.lastIndexOf('.')+1));
                var sizeInMB = (f.size / (1024*1024)).toFixed(4);
                $("#uploadedFiles").append("<li id='file"+j+"' class='collection-item avatar'><i class='material-icons circle noselect'>folder</i><span class='title truncate' style='margin-right:30px'>"+f.name+"</span><p class='truncate' style='margin-right:30px'>"+sizeInMB+" MB<br><span id='"+j+"'>Status</span></p><input type='hidden' value='Status' id='status"+j+"'><a href='#!' onclick='abort("+j+")' class='secondary-content'><i class='material-icons deep-orange-text text-accent-2'>cancel</i></a></li>");
                if (jQuery.inArray( extension.toLowerCase(), arr ) != -1) {
                    uploadFile(f,j);
                } else {
                    _(j).innerHTML='Invalid Extension';
                    _("status"+j).value='Invalid Extension';
                }   
            }
        });
    });
    
    function abort (i) {
        switch(_("status"+i).value) {
            case "Uploaded":
            case "Error Deleting":
                deleteFromServer (i);
                break;

            case "Status":
                _("status"+i).value='Abort';
                break;

            case "Deleted":
            case "Upload Failed":
            case "Invalid Extension": 
            case "Unknown Error":
            case "File Already Exists":
            case "Abort": 
            default:
                //alert("Elimare Visibilmente");
                var parent = _("uploadedFiles");
                var child = _("file"+i);
                parent.removeChild(child);
                break;
        }
    }

    function deleteFromServer (i) {
        var ajax = new XMLHttpRequest();

        ajax.addEventListener("load", function (event) {
            if (event.target.responseText) {
                _("status"+i).value=event.target.responseText;
                _(i).innerHTML=event.target.responseText;
            }
        }, false);

        ajax.addEventListener("error", function (event) {
            _(i).innerHTML="Error Occured";
            _("status"+i).value="Error Occured";
        }, false);

        ajax.open("DELETE", "/deleteFile.php?name='"+names[i]+"'");
        ajax.send();
    }

    function uploadFile (file, index) {
        var ajax = new XMLHttpRequest();
        var formdata = new FormData();

        formdata.append("filesToUpload", file);  
        
        //alert(file.name+" | "+file.size+" | "+file.type);
        ajax.upload.addEventListener("progress", function (event) {
            //alert(file.name);
            //_("loaded_n_total").innerHTML = "Uploaded "+event.loaded+" bytes of "+event.total;
            if (_("status"+index).value=='Abort') {
                ajax.abort();
            } else {
                var percent = (event.loaded / event.total) * 100;
                //console.log(file.name+" "+Math.round(percent)+"%");
                _(index).innerHTML=Math.round(percent)+"%";
                //_("progressBar").style.width = Math.round(percent)+"%";
                //_("status").innerHTML = Math.round(percent)+"% uploaded... please wait";
            }         
        }, false);

        ajax.addEventListener("load", function (event) {
            if (event.target.responseText) {
                _(index).innerHTML=event.target.responseText;
                _("status"+index).value=event.target.responseText;
                //var t = event.target.responseText;
                //Materialize.toast(t, 2000,'',function(){})
            }
        }, false);

        ajax.addEventListener("error", function (event) {
            //_("status").innerHTML = "Upload Failed";
            _(index).innerHTML="Upload Failed";
            _("status"+index).value="Upload Failed";
            //Materialize.toast("Upload Failed", 2500);
        }, false);

        ajax.addEventListener("abort", function (event) {
            //_("status").innerHTML = "Upload Aborted";
            _(index).innerHTML="Upload Aborted";
            //Materialize.toast("Upload Aborted", 2500);
        }, false);

        ajax.open("POST", "/uploadFile.php");
        ajax.send(formdata);
    }

    function _(el){
        return document.getElementById(el);
    }
</script>

<script>
    function categorySelect(category) {
        document.getElementById("subcategory").innerHTML = "<option value='' disabled selected>Subcategory</option>";
        document.getElementById("subcategory").removeAttribute("disabled"); 
        var xhttp;
        xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {
        if (xhttp.readyState == 4 && xhttp.status == 200) {
            document.getElementById("subcategory").innerHTML = xhttp.responseText;
            $('select').material_select();  
        }
        };
        xhttp.open("GET", "/getsubcategory.php?idcategory="+category.options[category.selectedIndex].value, true);
        xhttp.send(); 
    }
	function validate() {
        if($('#create')[0].checkValidity()) {
            submit = true;
            $('#submit').click();
        } else {
            Materialize.toast('Fill in all fields!', 2000);
        }
    }
	$('#tag-input').keypress(function (e) {
  		if ((e.which == 13) || (e.which == 32)) {
            if (this.value) {
                $('#tags').append("<div class='chip'>#"+this.value+"<i class='material-icons'>close</i><input type='hidden' name='tags[]' value='"+this.value+"'></div>");
                this.value='';
            }
    		return false;
  		}
	});
</script>

<?php require_once 'quarto.php'; ?>