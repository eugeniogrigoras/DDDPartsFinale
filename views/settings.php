<?php require_once 'primo.php'; ?>

<title>Settings</title>

<style>
    div.main-content {
        padding-top: 24px;
    }

    div.main-content form {

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

    a {
        color:white;
        font-size: 11px;
        letter-spacing: 0.5px;
    }

    div.form {
        padding:35.25px 24px!important;
    }

    div.input-field label{
        width:inherit!important;
    }

    @media only screen and (max-width : 600px) {
        div.password {
            width: calc(100% - 30px)!important;
        }
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
        cursor: pointer;
        margin:auto auto;
        @apply(--shadow-elevation-4dp);
    }


</style>
<?php require_once 'secondo.php'; ?>
<main>
    <div class="container main-content row" style="margin-bottom:24px!important">
        <!-- <h4 id="status"></h4>
        <p id="loaded_n_total"></p> -->
        <div class="progress" style="margin:0!important; border-radius:0!important; background-color:#fe9da0">
            <div id="progressBar" class="determinate" style="width:0%; background-color:#e44a3e"></div>
        </div>
        <form novalidate id="upload" method="post" enctype="multipart/form-data" class="col s12 z-depth-1">
            <input type="hidden" value="settings" name="getpage">
            <input accept=".jpg,.jpeg" type="file" name="fileToUpload" id="fileToUpload" style="display:none;">
            <div class="row title">Settings <?php if(isset($message)) echo "- ".$message ?></div>
            <div class="row" style="padding:24px; background-image:url('/img/bg2.jpg'); background-size:cover">
                <div id="avatar" onclick="chooseImage()">
                    <img id="preview" src="<?php echo requestPath()."/profile.jpg";?>" >
                </div>
            </div>
            <div class="row form">
                <div class="input-field col l6 m6 s12">
                    <input type="hidden" id="actual-password-hidden" value="<?php $data=requestData(); echo $data["PASSWORD"]; ?>">
                    <input id="actual-password" type="password">
                    <label for="actual-password">Actual Password</label>
                </div>
                <div class="password input-field col l6 m6 s12" style="width: calc(50% - 30px);">
                    <input disabled name="password" maxlength="30" style="margin-right:30px!important;" id="password" type="password" class="validate" pattern=".{5,}[a-z0-9A-Z]">
                    <label data-error="Letters & numbers!" for="password">New Password</label>
                    <i id="passwordIcon" onclick="showPassword();" style="cursor:pointer; color: #444; position:inherit; z-index:24; left:30px; top:-50px; background-color:white" class="material-icons right">visibility</i>
                </div>

                <div class=" col l12 m12 s12">
                    <button id="submit" type="submit" class="deep-orange accent-2 white-text right waves-effect waves-light btn-flat" name="submit">Submit
                        <i class="material-icons right">send</i>
                    </button>
                </div>
            </div>
        </form>
    </div>
</main>

<?php require_once 'terzo.php'; ?>

<script>


    $(document).ready(function() {
        
        $("#upload").submit(function(e) {
            e.preventDefault();  
            var ajax = new XMLHttpRequest();
            var formdata = new FormData();
            var file = _("fileToUpload").files[0];
            if(file || _("password").value.length!=0) {
                ajax.upload.addEventListener("progress", progressHandler, false);
            }
            if (file) {
                //alert(file.name+" | "+file.size+" | "+file.type);
                formdata.append("fileToUpload", file);
            }
            if (_("password").value.length!=0) {
                if($('#password')[0].checkValidity()) {
                    formdata.append("password", _("password").value);
                } else {
                    Materialize.toast('Invalid Password!', 2000);
                    return;
                } 
            }
            ajax.addEventListener("load", completeHandler, false);
            ajax.addEventListener("error", errorHandler, false);
            ajax.addEventListener("abort", abortHandler, false);
            ajax.open("POST", "/settings.php");
            ajax.send(formdata);
        });
        
    });

    function _(el){
        return document.getElementById(el);
    }

    function completeHandler(event){
        //alert("Ciao");
        if (event.target.responseText) {
            var t = event.target.responseText;
            Materialize.toast(t, 2000,'',function(){location.reload()})
            
        } else {
            Materialize.toast("No Updates", 2500)
        }
        //_("status").innerHTML = event.target.responseText;
        //_("progressBar").value = 0;
    }

    function progressHandler(event){
        //_("loaded_n_total").innerHTML = "Uploaded "+event.loaded+" bytes of "+event.total;
        var percent = (event.loaded / event.total) * 100;
        _("progressBar").style.width = Math.round(percent)+"%";
        //_("status").innerHTML = Math.round(percent)+"% uploaded... please wait";
    }
    
    function errorHandler(event){
        //_("status").innerHTML = "Upload Failed";
        alert("Upload Failed");
    }
    function abortHandler(event){
        //_("status").innerHTML = "Upload Aborted";
        alert("Upload Aborted");
    }
</script>

<script>
    function showPassword() {
        var password = document.getElementById("password");
        var passwordIcon = document.getElementById("passwordIcon");
        if (password.type=="password") {
            password.type="text";
            passwordIcon.innerHTML="visibility_off";
        } else {
            password.type="password";
            passwordIcon.innerHTML="visibility";
        }
    }

    function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            
            reader.onload = function (e) {
                $('#preview').attr('src', e.target.result);
                $('#avatar').attr('style', "background-image:url("+e.target.result+")");
            }
            
            reader.readAsDataURL(input.files[0]);
        }
    }

    $("#fileToUpload").change(function(){
        _("progressBar").style.width = "0%";
        readURL(this);
    });

    function chooseImage() {
        document.getElementById('fileToUpload').click();
        console.log("Choosed!");
    }

    $("#actual-password").change(function(){
        if(this.value == document.getElementById("actual-password-hidden").value) {
            document.getElementById("password").removeAttribute("disabled"); 
        }
    });
</script>

<?php require_once 'quarto.php'; ?>