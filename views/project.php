<?php require_once 'primo.php'; ?>
<?php 
    $projects=executeQuery('select progetti.ID, progetti.NOME AS NOME_PROGETTO, progetti.DESCRIZIONE, progetti.FK_UTENTE, utenti.NOME AS NOME_UTENTE, utenti.COGNOME, utenti.EMAIL, categorie_primarie.NOME AS CATEGORIA_PRIMARIA, categorie_secondarie.NOME AS CATEGORIA_SECONDARIA, COUNT(*) AS FILES FROM progetti, utenti, categorie_primarie, categorie_secondarie, parti_3d WHERE progetti.FK_CATEGORIA_SECONDARIA=categorie_secondarie.ID AND categorie_secondarie.FK_CATEGORIA_PRIMARIA=categorie_primarie.ID AND progetti.FK_UTENTE=utenti.ID AND parti_3d.FK_PROGETTO=progetti.ID AND progetti.ID='.$id.' GROUP BY progetti.ID');
    $project=$projects->fetch_assoc();  
    //var_dump($project);
?>
<title><?php echo $project["NOME_PROGETTO"] ?> by <?php echo $project["NOME_UTENTE"]." ".$project["COGNOME"]; ?></title>
<meta name="description" content="<?php echo $project["DESCRIZIONE"] ?>">
<link rel="stylesheet" href="/css/flickity.css">
<script src="/js/flickity.pkgd.min.js"></script>

<style>
	div.main-content {
        padding-top: 24px;
    }
    div.title {
        background-color: #444;
        color:white;
        padding:24px!important;
        padding-left:12px!important;
        font-weight: 300;
    }
    p a {
        color: white;
        border-bottom:1px solid #ddd;
    }

    p a:hover {
        border-bottom:1px solid rgba(255,109,64,1);
    }
    .chip {
        margin:3px;
        border-radius: 0!important;
    }

    img {
        display: block;
        height: 200px;
    }

    .carousel {
        height:200px;
        background-color:#212121;
    }

    @media screen and (min-width: 768px) {
        img, .carousel {
            height: 400px;
        }
    }
    .flickity-prev-next-button {
        width:30px;
        height: 30px;
    }
    .progress-bar {
        height: 6px;
        width: 0;
        background-color:#ff6e40;
        background-color: rgba(255,109,64,0.75);
    }
    .fit-content {
        width: -moz-fit-content;
        width: -webkit-fit-content;
        width: fit-content;
    }
    li.download + li.download {
        border-top: 1px solid #ddd;
    }
</style>
<?php require_once 'secondo.php'; ?>
<main>
    <div class="fixed-action-btn">
        <div class="btn-floating btn-large deep-orange accent-2 waves-effect modal-trigger" href="#download-content">
            <i class="large material-icons">file_download</i>
        </div>
    </div>
    <div id="download-content" class="modal modal-fixed-footer bottom-sheet" style="max-height:100%">
        <div class="modal-content">
            <h4>What do you need?</h4>
            <ul style="border:0; margin:0; padding:24px; border:1px solid #ddd; background-color:white" class="col s12 collection" id="uploadedFiles">
                        <?php 
                            $projectPath = "users/".$project["NOME_UTENTE"]."-".$project["COGNOME"]."-".$project["EMAIL"]."/".$project["ID"];

                            $files = array_slice(scandir($projectPath), 2);
                        
                            foreach ($files as $file) {
                                echo '<li style="padding:24px 0;" class="download">'.$file.'</li>';
                            }
                            foreach (glob($projectPath."/*.jpg") as $filename) {
                                var2console($filename);
                                echo "/$filename size " . (filesize($filename) / (1024*1024));
                                echo "<br>";
                            }   
                        ?>
                    </ul>
        </div>
        <div class="modal-footer">  
            <a href="#!" style="margin-left:6px" class="modal-action waves-effect waves-light btn-flat grey darken-3 white-text">Download</a>
            <a href="#!" class="modal-action modal-close waves-effect btn-flat">Close</a>
        </div>
    </div>
    <div class="row main-content" style="margin:0">
        <div class="col l9 s12 m8" style="margin-bottom:24px">
            <div class="col s12 z-depth-1" style="height:100%; background-color:white; margin:0; padding:0">
                <div class="col s12 title">
                    <p class="truncate" style="font-size:20px; margin:0;"><?php echo $project["NOME_PROGETTO"] ?></p>
                    <p class="truncate" style="font-size:13px; margin:0">by <a href="/user/<?php echo $project["FK_UTENTE"]; ?>"><?php echo $project["NOME_UTENTE"]." ".$project["COGNOME"] ?></a></p>
                </div>
                <div class="gallery carousel">
                    <?php 
                        foreach (glob($projectPath."/*.{jpg,png,gif}", GLOB_BRACE) as $filename) : ?>
                          <img src="<?php echo "/".$filename; ?>" alt="">
                    <?php endforeach; ?>
                </div>
                <div style="background:#9e9e9e"><div class="progress-bar"></div></div>
                <div class="col s12" style="padding:24px;">
                    <h4>Description</h4>
                    <p><?php echo $project["DESCRIZIONE"] ?></p>
                    <ul style="border:0; margin:0; padding:0; display:none" class="col s12 collection" id="uploadedFiles">
                    
                        <?php 

                            $files = array_slice(scandir($projectPath), 2);
                        
                            foreach ($files as $file) {
                                echo $file;
                                echo "<br>";
                            }
                            foreach (glob($projectPath."/*.jpg") as $filename) {
                                var2console($filename);
                                echo "/$filename size " . (filesize($filename) / (1024*1024));
                                echo "<br>";
                            }   
                        ?>
                    </ul>
                </div>
            </div>
        </div>
        <div class="col l3 s12 m4" style="margin-bottom:24px;">
            <div class="row z-depth-1" style="height:100%; background-color:white; margin:0">
                <div class="col s12 title">
                    <p class="truncate" style="font-size:20px; margin:0;">Stats</p>
                    <p class="truncate" style="font-size:13px; margin:0">more about - <?php echo $project["NOME_PROGETTO"] ?></p>
                </div>
                <div class="col s12" style="padding:12px">
                    <div id="category">
                        <p style="margin:0; margin-bottom:6px">Category: <a style="color:#212121" href="#"><?php echo $project["CATEGORIA_PRIMARIA"] ?></a></p>
                        <p style="font-size:13px; color:#999; margin:0; margin-bottom:12px" class="truncate">Subcategory <a style="color:#ff6e40" href="#"><?php echo $project["CATEGORIA_SECONDARIA"]?></a></p>
                    </div>
                    <div id="tags">
                        
                        <?php 
                            $tags=executeQuery('select * FROM tag, progetti_hanno_tag where progetti_hanno_tag.FK_TAG=tag.ID and progetti_hanno_tag.FK_PROGETTO='.$project['ID']);
                            if ($tags && ($tags->num_rows > 0)) : ?>
                                <p style="margin:6px 0;">Tags:</p>
                            <?php 

                                while ($tag=$tags->fetch_assoc()) : 
                            ?>
                        <div class="chip" style="cursor:pointer">#<?php echo $tag["NOME"] ?></div>
                        <?php endwhile; endif;?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<?php require_once 'terzo.php'; ?>
<script>
    var flkty = new Flickity('.gallery', {
      imagesLoaded: true,
      freeScroll: true,
      initialIndex: 0,
      pageDots: false
    });

    var progressBar = document.querySelector('.progress-bar');

    // duck punch
    var _positionSlider = flkty.positionSlider;
    flkty.positionSlider = function() {
        _positionSlider.apply( flkty, arguments );

        var firstCell = flkty.cells[0];
        var cellsWidth = flkty.getLastCell().target - firstCell.target;
        var progress = ( -flkty.x + -firstCell.target ) / cellsWidth;
        progress = Math.max( 0, Math.min( 1, progress ) );
        progressBar.style.width = progress * 100 + '%';
    };
</script>
<?php require_once 'quarto.php'; ?>