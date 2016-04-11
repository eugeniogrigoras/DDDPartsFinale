<?php require_once 'primo.php'; ?>

<title>Register</title>

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
        <form id="register" action="/form.php" method="post" style="padding:0!important" enctype="multipart/form-data" class="col s12 z-depth-1">
            <input type="hidden" value="register" name="getpage">
            <input value="img/default.jpg" accept=".jpg,.jpeg" type="file" name="fileToUpload" id="fileToUpload" style="display:none;">
            <div class="title">ABOUT YOU <?php if(isset($message)) echo "- ".$message ?></div>
            <div class="" style="padding:24px; background-image:url('/img/bg2.jpg'); background-size:cover">
                <div id="avatar" onclick="chooseImage()">
                    <img id="preview" src="/img/default.jpg" >
                </div>
            </div>
            <div class="row form">
                <div class="input-field col l6 m6 s12">
                    <input required name="first-name" id="first_name" type="text" class="validate">
                    <label for="first_name">First Name</label>
                </div>
                <div class="input-field col l6 m6 s12">
                    <input required name="last-name" id="last-name" type="text" class="validate">
                    <label for="last">Last Name</label>
                </div>
                <div class="input-field col l6 m6 s12">
                    <input required name="email" id="email" type="email" class="validate" pattern="[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,3}$">
                    <label data-error="Wrong Email!" for="email">Email</label>
                </div>
                <div class="input-field col l6 m6 s12">
                    <select required onchange="regionSelect(this)">
                        <option value="" disabled selected>Region</option>
                        <?php $record = executeQuery("select * from regioni order by regioni.nomeregione"); ?>
                        <?php while ($riga=$record->fetch_assoc()) : ?>
                            <option value="<?php echo $riga['idregione'] ?>"><?php echo $riga['nomeregione']; ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <div class="input-field col l6 m6 s12">
                    <select id="province" required disabled onchange="provinceSelect(this)">
                        <option value="" disabled selected>Province</option>
                    </select>
                </div>
                <div class="input-field col l6 m6 s12">
                    <select id="city" required disabled name="city">
                        <option value="" disabled selected>City</option>
                    </select>
                </div>
                <div class="password input-field col l6 m6 s12" style="width: calc(50% - 30px);">
                    <input required name="password" maxlength="30" style="margin-right:30px!important;" id="password" type="password" class="validate" pattern=".{5,}[a-z0-9A-Z]*">
                    <label data-error="Letters & numbers!" for="password">Password</label>
                    <i id="passwordIcon" onclick="showPassword();" style="cursor:pointer; color: #444; position:inherit; z-index:24; left:30px; top:-50px; background-color:white" class="material-icons right">visibility</i>
                </div>
                <div class=" col l12 m12 s12">
                    <a style="font-size:15px;" class="deep-orange accent-2 white-text right waves-effect waves-light btn-flat" onclick="validate()">Submit
                        <i class="material-icons right">send</i>
                    </a>
                    <!-- Modal Structure -->
                    <div id="policy" class="modal modal-fixed-footer">
                        <div class="modal-content">
                            <h4>Terms of Usage</h4>
                            <p>"Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo. Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit, sed quia consequuntur magni dolores eos qui ratione voluptatem sequi nesciunt. Neque porro quisquam est, qui dolorem ipsum quia dolor sit amet, consectetur, adipisci velit, sed quia non numquam eius modi tempora incidunt ut labore et dolore magnam aliquam quaerat voluptatem. Ut enim ad minima veniam, quis nostrum exercitationem ullam corporis suscipit laboriosam, nisi ut aliquid ex ea commodi consequatur? Quis autem vel eum iure reprehenderit qui in ea voluptate velit esse quam nihil molestiae consequatur, vel illum qui dolorem eum fugiat quo voluptas nulla pariatur?""But I must explain to you how all this mistaken idea of denouncing pleasure and praising pain was born and I will give you a complete account of the system, and expound the actual teachings of the great explorer of the truth, the master-builder of human happiness. No one rejects, dislikes, or avoids pleasure itself, because it is pleasure, but because those who do not know how to pursue pleasure rationally encounter consequences that are extremely painful. Nor again is there anyone who loves or pursues or desires to obtain pain of itself, because it is pain, but because occasionally circumstances occur in which toil and pain can procure him some great pleasure. To take a trivial example, which of us ever undertakes laborious physical exercise, except to obtain some advantage from it? But who has any right to find fault with a man who chooses to enjoy a pleasure that has no annoying consequences, or one who avoids a pain that produces no resultant pleasure?"</p>
                        </div>
                        <div class="modal-footer">
                            <button class="white accent-2 right waves-effect btn-flat" type="submit" name="submit">Agree</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</main>

<?php require_once 'terzo.php'; ?>

<script>
    function validate() {
        if($('#register')[0].checkValidity()) {
            $('#policy').openModal();
        } else {
            Materialize.toast('Fill in all fields!', 2000)
        }
    }
    function regionSelect(region) {
        document.getElementById("city").innerHTML = "<option value='' disabled selected>City</option>";
        document.getElementById("city").setAttribute("disabled",""); 
        document.getElementById("province").removeAttribute("disabled"); 
        var xhttp;
        xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {
        if (xhttp.readyState == 4 && xhttp.status == 200) {
                document.getElementById("province").innerHTML = xhttp.responseText;
                $('select').material_select();  
            }
        };
        xhttp.open("GET", "/getprovince.php?idregione="+region.options[region.selectedIndex].value, true);
        xhttp.send(); 
    }

    function provinceSelect(province) {
        document.getElementById("city").removeAttribute("disabled"); 
        var xhttp;
        xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {
        if (xhttp.readyState == 4 && xhttp.status == 200) {
                document.getElementById("city").innerHTML = xhttp.responseText;
                $('select').material_select();
            }
        };
        xhttp.open("GET", "/getcomune.php?idprovincia="+province.options[province.selectedIndex].value, true);
        xhttp.send();   
    }

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
        readURL(this);
    });

    function chooseImage() {
        document.getElementById('fileToUpload').click();
        console.log("Choosed!");
    }


</script>

<?php require_once 'quarto.php'; ?>