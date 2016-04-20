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
</style>
<?php require_once 'secondo.php'; ?>
<main>
    <div class="container main-content row">
        <form id="create" action="/form.php" method="post" enctype="multipart/form-data" class="col s12 z-depth-1">
            <div class="row title">Project <?php if(isset($message)) echo "- ".$message ?></div>
        	<input type="hidden" value="create" name="getpage">
            <div class="row form">
                <div class="input-field col s12">
                    <input required name="title" id="title" type="text" class="validate">
                    <label data-error="Wrong!" for="title">Title</label>
                </div>
                <div class="input-field col s12">
                    <textarea id="description" class="materialize-textarea" max-length="300"></textarea>
                    <label for="description">Description</label>
                </div>
                <div class="input-field col l6 m6 s12">
                    <select required onchange="categorySelect(this)" name="category">
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
        </form>
    </div>
</main>

<?php require_once 'terzo.php'; ?>

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
            $('#submit').click();
        } else {
            Materialize.toast('Fill in all fields!', 2000)
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