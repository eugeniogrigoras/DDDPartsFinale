<?php require_once 'primo.php'; ?>
<title>Dashboard - What's New?</title>
<meta name="description" content="Dashboard – Look into things you're interested in">
<?php require_once 'secondo.php'; ?>
<main>
    <div class="container main-content row">
        <h1>Dashboard</h1>
        <!-- <a class="like-button">
      		<span class="like-icon">
        		<div class="heart-animation-1"></div>
        		<div class="heart-animation-2"></div>
      		</span>
      		<span id="likes">0</span>
    	</a> -->
    </div>
</main>

<?php require_once 'terzo.php'; ?>

<!-- <script>
	$('a.like-button').on('click', function() {
		$likes=parseInt($('span#likes').html());
    if ($('a.like-button').hasClass('liked')) {
        $likes--;
    } else {
        $likes++;
    }
		$('span#likes').text($likes);
  	$(this).toggleClass('liked');
	});
</script> -->

<?php require_once 'quarto.php'; ?>