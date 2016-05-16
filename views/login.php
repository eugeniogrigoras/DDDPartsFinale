<?php require_once 'primo.php'; ?>

<title>Login</title>

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


</style>
<?php require_once 'secondo.php'; ?>
<main>
    <div class="container main-content row" style="margin-bottom:24px!important">
        <form autocomplete="off" style="background-color:white;" id="login" action="/form.php" method="post" enctype="multipart/form-data" class="col s12 z-depth-1">
            <input type="hidden" value="login" name="getpage">
            <div class="row title">LOGIN <?php if(isset($message)) echo "- ".$message ?></div>
            <div class="row form">
                <div class="input-field col l12 m12 s12">
                    <input required name="email" id="email" type="email" class="validate" pattern="[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,3}$">
                    <label data-error="Wrong Email!" for="email">Email</label>
                </div>
                <div class="password input-field col l12 m12 s12" style="width: calc(100% - 30px);">
                    <input required name="password" maxlength="30" style="margin-right:30px!important;" id="password" type="password">
                    <label for="password">Password</label>
                    <i id="passwordIcon" onclick="showPassword();" style="cursor:pointer; color: #444; position:inherit; z-index:24; left:30px; top:-50px; background-color:white" class="material-icons right noselect">visibility</i>
                </div>
                <div class=" col l12 m12 s12">
                    <br>
                </div>
                <div class=" col l12 m12 s12">
                    <p>
                        <input class="filled-in" name="remember" type="checkbox" id="remember" />
                        <label for="remember" style="font-size:12px; height:21px; line-height:21px">Remember Me</label>
                    </p>
                </div>
                <div class=" col l12 m12 s12">
                    <br><br>
                </div>
                <div class=" col l12 m12 s12">
                    <a style="font-size:15px;" class="deep-orange accent-2 white-text right waves-effect waves-light btn-flat" onclick="validate()">Login
                        <i class="material-icons right">send</i>
                    </a>
                    <button id="submit" type="submit" name="submit" style="display:none">
                </div>
            </div>
            <div class="row title">
                <a class="left">LOST YOUR PASSWORD?</a>
                <a href="/register" class="right">REGISTER!</a>
            </div>
        </form>
    </div>
</main>

<?php require_once 'terzo.php'; ?>

<script>
    function validate() {
        if($('#login')[0].checkValidity()) {
            $('#submit').click();
        } else {
            Materialize.toast('Fill in all fields!', 2000)
        }
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
    $('#password,#email').keypress(function(e){
        if (e.which==13) {
            validate();
        }
    })
</script>

<?php require_once 'quarto.php'; ?>