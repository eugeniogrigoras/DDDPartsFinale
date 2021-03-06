<?php require_once 'primo.php'; ?>
<title>DDDParts - The social network for sharing and downloading printable 3D parts and projects</title>
<meta name="description" content="Explore the most popular printable 3D projects of the moment and create your favourite objects.">
<style>
	html {
		background-color: #212121;
	}
	main {
		background-color: #212121;
	}
	.header {
		/*padding-top: 24px;*/
		/*background-image: url('/img/elephante.png');*/
		background-repeat: no-repeat;
		background-size: 15%;
		background-position: center top;
		background-origin:padding-box;
		color:white;
		text-align: center;
		font-weight: 300;
		font-size: 24px;
	}
	.content {
		padding: 24px;
	}
	div.title {
        color:white;
        padding:24px;
        font-weight: 300;
    }
    div.row {
        margin-bottom: 0!important;
    }
    a {
        color:white;
        font-size: 24px;
        letter-spacing: 0.5px;
    }
    .container1 {
    	margin: 0 auto;
		max-width: 1280px;
		width: 90%;
    }
    @media only screen and (min-width: 601px) {
	  	.container1 {
	    	width: 60%;
	  	}
	}

	@media only screen and (min-width: 993px) {
	  	.container1 {
	    	width: 35%;
	  	}
	}
</style>
<?php require_once 'secondo.php'; ?>
<main>
	<div class="header">
		<div class="content" style="background-color: #212121">
			<div class="container1"><img src="/img/bg9.png" style=" width:80% " ></div>
			<!-- <p style="margin:0">THE PLACE TO BE FOR 3D</p> -->
			<h3>What is DDDParts?</h3>
			<p class="container flow-text" style="font-weight: 200">"3D Parts, Projects and Collections in a platform for DDDusers"</p>
		
            <a href="/login" class="deep-orange accent-2 grey-text text-darken-4 center waves-effect btn-flat btn-large" style="margin-top: 1.168rem;">Try Now!</a>
        </div>
		</div>
	</div>
</main>

<?php require_once 'terzo.php'; ?>

<script></script>

<?php require_once 'quarto.php'; ?>