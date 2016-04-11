<?php require_once 'primo.php'; ?>
<title>Create</title>
<?php require_once 'secondo.php'; ?>
<main>
    <div class="container main-content row">
        <h1>Create</h1>
        <form id="create" action="/form.php" method="post" enctype="multipart/form-data" class="col s12 z-depth-1">
        	<input type="hidden" value="create" name="getpage">
        	<div class="input-field col s12">
	          	<input id="tag-input" type="text" class="validate">
	          	<label for="tag-input">Tag</label>
	        </div>
        	<div id="tags"></div>
        	<a style="font-size:15px;" class="deep-orange accent-2 white-text right waves-effect waves-light btn-flat" onclick="validate()">Create
                <i class="material-icons right">send</i>
            </a>
            <button id="submit" type="submit" name="submit" style="display:none">
        </form>
    </div>
</main>

<?php require_once 'terzo.php'; ?>

<script>
	function validate() {
        if($('#create')[0].checkValidity()) {
            $('#submit').click();
        } else {
            Materialize.toast('Fill in all fields!', 2000)
        }
    }
	$('#tag-input').keypress(function (e) {
  		if (e.which == 13) {
  			$('#tags').append("<div class='chip'>"+this.value+"<i class='material-icons'>close</i><input type='hidden' name='tags[]' value='"+this.value+"'></div>");
    		this.value='';
    		return false;
  		}
	});
</script>

<?php require_once 'quarto.php'; ?>