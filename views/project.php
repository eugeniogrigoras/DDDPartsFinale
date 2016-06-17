<?php require_once 'primo.php'; ?>
<?php 
    $projects=executeQuery('select progetti.ID, progetti.NOME AS NOME_PROGETTO, progetti.DESCRIZIONE, progetti.FK_UTENTE, utenti.ID as ID_UTENTE, utenti.NOME AS NOME_UTENTE, utenti.COGNOME, utenti.EMAIL, categorie_primarie.NOME AS CATEGORIA_PRIMARIA, categorie_secondarie.NOME AS CATEGORIA_SECONDARIA, COUNT(*) AS FILES FROM progetti, utenti, categorie_primarie, categorie_secondarie, parti_3d WHERE progetti.FK_CATEGORIA_SECONDARIA=categorie_secondarie.ID AND categorie_secondarie.FK_CATEGORIA_PRIMARIA=categorie_primarie.ID AND progetti.FK_UTENTE=utenti.ID AND parti_3d.FK_PROGETTO=progetti.ID AND progetti.ID='.$id.' GROUP BY progetti.ID');
    $project=$projects->fetch_assoc();  
    //var_dump($project);
?>


<?php
    if (logged()) {
        define('DISQUS_SECRET_KEY', 'CozAtaIAQX396ibUCFJVwDBLtEHHWa0ivdLpbp40RNtJHZ05PwMQydhS2yvsjVKt');
        define('DISQUS_PUBLIC_KEY', 'BX2lFc2XkPoapSoPAt6Q5K6W89CD5gfyFSjV3LP3IpThpFlE1TpUNCyYynHXGYDt');
         
        $data = array(
                "id" => $_SESSION["ID"],
                "username" => $_SESSION["NOME"]." ".$_SESSION["COGNOME"],
                "avatar" => "http://dddparts.altervista.org".requestPath()."/profile.jpg",
                "email" => $_SESSION["EMAIL"]
            );
        var2console ($data);
         
        function dsq_hmacsha1($data, $key) {
            $blocksize=64;
            $hashfunc='sha1';
            if (strlen($key)>$blocksize)
                $key=pack('H*', $hashfunc($key));
            $key=str_pad($key,$blocksize,chr(0x00));
            $ipad=str_repeat(chr(0x36),$blocksize);
            $opad=str_repeat(chr(0x5c),$blocksize);
            $hmac = pack(
                        'H*',$hashfunc(
                            ($key^$opad).pack(
                                'H*',$hashfunc(
                                    ($key^$ipad).$data
                                )
                            )
                        )
                    );
            return bin2hex($hmac);
        }
         
        $message = base64_encode(json_encode($data));
        $timestamp = time();
        $hmac = dsq_hmacsha1($message . ' ' . $timestamp, DISQUS_SECRET_KEY);
    }
?>
<script type="text/javascript">
    var disqus_config = function() {
        <?php if (logged()): ?>
        this.page.remote_auth_s3 = "<?php echo "$message $hmac $timestamp"; ?>";
        this.page.api_key = "<?php echo DISQUS_PUBLIC_KEY; ?>";
        <?php endif; ?>
        this.page.identifier = '<?php echo $project['ID']; ?>'; 
    }
</script>
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
        border-bottom:0px solid #ddd;
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
    .small-icon {
        font-size:1.25rem;
    }
    b {
        margin-left: 6px;
        font-weight: 400;
    }
    .informations p {
        width:100%;
    }
    .informations i {
        color:#686868;
    }
</style>
<?php require_once 'secondo.php'; ?>
<?php 
    $path="users/".$project["NOME_UTENTE"]."-".$project["COGNOME"]."-".$project["EMAIL"]."/".$project["ID"];
    $projectName=$project['NOME_PROGETTO'];
    $zipName = $path."/$projectName.zip";
    $zipDownload = "/".$zipName;
    if (!file_exists($zipName)) createZip($path, $zipName);
?>
<main>
    <div class="fixed-action-btn">
        <div class="btn-floating btn-large deep-orange accent-2 waves-effect modal-trigger" href="#download-content">
            <i class="large material-icons">file_download</i>
        </div>
    </div>
    <div id="download-content" class="modal modal-fixed-footer bottom-sheet" style="max-height:100%">
        <div class="modal-content">
            <h4>What do you need?</h4>
            <ul style="border:0; margin:0; padding:0; border:1px solid #ddd; background-color:white" class="col s12 collection">
                <?php 
                    $projectID=$project['ID']; 
                    $projectPath = "users/".$project["NOME_UTENTE"]."-".$project["COGNOME"]."-".$project["EMAIL"]."/".$project["ID"];
                    echo "<script>var names = []; </script>";
                    $j=0;
                    $files = array_slice(scandir($projectPath), 2);
                    foreach ($files as $file) {
                        echo "<script> names[$j]='$file'; </script>";
                        //var2console($file);
                        //var2console($projectID);
                        $QUERY=executeQuery("select * from parti_3d where FK_PROGETTO=$projectID and NOME='$file'");
                        $fileData=$QUERY->fetch_assoc();
                        //var2console ($file);
                        //number_format($number, 2, '.', '')
                        $size = number_format(filesize($projectPath."/".$file)/(1024*1024), 4, '.', '');
                        //var2console($size);
                        $extension = substr($file, strrpos($file, '.')+1);
                        //var2console ($extension);
                        //$sizeInMB = ($file.size / (1024*1024)).toFixed(4);
                        //echo '<li style="padding:24px 0;" class="download">'.$file.'</li>';
                        $fileDownload="/".$path."/".$file;
                        if ($file!=$projectName.".zip") {
                            echo "<li class='collection-item avatar'><i class='material-icons circle noselect'>folder</i><span class='title truncate' style='margin-right:30px'>".$file."</span><p class='truncate' style='margin-right:30px'>".$size." MB<br><span id='downloadNumber$j'>".$fileData["NUMERO_DOWNLOAD"]." Downloads</span></p><a onclick='downloadFile($j)' download href='$fileDownload' class='secondary-content'><i class='material-icons deep-orange-text text-accent-2'>file_download</i></a></li>";
                        }
                        $j++;
                    }
                    /*foreach (glob($projectPath."/*.jpg") as $filename) {
                        var2console($filename);
                        echo "/$filename size " . (filesize($filename) / (1024*1024));
                        echo "<br>";
                    }*/   
                ?>
            </ul>
        </div>
        <div class="modal-footer">  
            <a download onclick="downloadProject()" href="<?php echo $zipDownload; ?>" style="margin-left:6px" class="waves-effect waves-light btn-flat grey darken-3 white-text">Download</a>
            <a class="modal-action modal-close waves-effect btn-flat">Close</a>
        </div>
    </div>
    <div class="row main-content" style="margin:0;">
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
                    <h5>Description</h5>
                    <p><?php echo $project["DESCRIZIONE"] ?></p>
                    <ul style="border:0; margin:0; padding:0; display:none" class="col s12 collection">
                    
                        <?php 

                            $files = array_slice(scandir($projectPath), 2);
                        
                            foreach ($files as $file) {
                                echo $file;
                                echo "<br>";
                            }
                            foreach (glob($projectPath."/*.jpg") as $filename) {
                                //var2console($filename);
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
                    <p class="truncate" style="font-size:13px; margin:0">information about - <?php echo $project["NOME_PROGETTO"] ?></p>
                </div>
                <div class="col s12" style="padding:12px">
                    <div id="category">
                        <p style="margin:0; margin-bottom:6px">Category:</p>
                        <p style="font-size:13px; margin:0;" class="truncate"><a style="color:#212121" href="#"><?php echo $project["CATEGORIA_PRIMARIA"] ?></a> / <a style="color:#ff6e40" href="#"><?php echo $project["CATEGORIA_SECONDARIA"]?></a></p>
                    </div>
                    
                    <div id="tags">
                        
                        <?php 
                            $tags=executeQuery('select * FROM tag, progetti_hanno_tag where progetti_hanno_tag.FK_TAG=tag.ID and progetti_hanno_tag.FK_PROGETTO='.$project['ID']);
                            if ($tags && ($tags->num_rows > 0)) : ?>
                                <hr>
                                <p style="margin:0; margin-bottom:6px">Tags:</p>
                            <?php 

                                while ($tag=$tags->fetch_assoc()) : 
                            ?>
                        <div class="chip" style="cursor:pointer">#<?php echo $tag["NOME"] ?></div>
                        <?php endwhile; endif;?>
                    </div>
                    <hr>
                    <div class="informations">
                        <div class="valign-wrapper" style="color:#444">
                            <i class="small-icon material-icons noselect" style="margin-right:12px">favorite</i>
                            <p class="truncate" style="margin:0;">Likes:<b>
                                <?php 
                                    $QUERY=executeQuery("select * from utenti_like_progetti where FK_PROGETTO=$projectID"); 
                                    echo $QUERY->num_rows;
                                ?>
                                </b>
                            </p>
                        </div>
                        <hr>
                        <div class="valign-wrapper" style="color:#444">
                            <i class="small-icon material-icons noselect" style="margin-right:12px">file_download</i>
                            <p class="truncate" style="margin:0;">Downloads:<b>
                                <?php 
                                    $QUERY=executeQuery("select NUMERO_DOWNLOAD from progetti where ID=$projectID"); 
                                    $numeroDownload=$QUERY->fetch_assoc();
                                    echo $numeroDownload["NUMERO_DOWNLOAD"];
                                ?>
                                </b>
                            </p>
                        </div>
                        <hr>
                        <div class="valign-wrapper" style="color:#444">
                            <i class="small-icon material-icons noselect" style="margin-right:12px">move_to_inbox</i>
                            <p class="truncate" style="margin:0;">In collection:<b>
                                <?php 
                                    $COLLECTION = executeQuery("select * from collezioni_composte_da_progetti where FK_PROGETTO=".$project["ID"]); 
                                    echo $COLLECTION->num_rows; 
                                ?>
                                </b>
                            </p>
                        </div>
                        <hr>
                        <div class="valign-wrapper" style="color:#444">
                            <i class="small-icon material-icons noselect" style="margin-right:12px">insert_drive_file</i>
                            <p class="truncate" style="margin:0;">Files:<b>
                                <?php 
                                    $QUERY=executeQuery("select * from parti_3d where FK_PROGETTO=$projectID"); 
                                    echo $QUERY->num_rows;
                                ?>
                                </b>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col l9 s12 m8" style="margin-bottom:24px">
            <div class="col s12 z-depth-1" style="height:100%; background-color:white; margin:0; padding:24px">
                <!-- <h5>Comments</h5>
                <form autocomplete="off" novalidate id="upload" method="post" enctype="multipart/form-data" class="col s12">
                    <div class="input-field col s12">
                        <textarea id="comment" class="materialize-textarea" length="300" maxlength="300"></textarea>
                        <label for="comment">Comment</label>
                    </div>
                </form> -->
                <div id="disqus_thread"></div>
                <script>
                    /**
                     *  RECOMMENDED CONFIGURATION VARIABLES: EDIT AND UNCOMMENT THE SECTION BELOW TO INSERT DYNAMIC VALUES FROM YOUR PLATFORM OR CMS.
                     *  LEARN WHY DEFINING THESE VARIABLES IS IMPORTANT: https://disqus.com/admin/universalcode/#configuration-variables
                     */
                    
                    (function() {  // DON'T EDIT BELOW THIS LINE
                        var d = document, s = d.createElement('script');
                        
                        s.src = '//dddparts.disqus.com/embed.js';
                        
                        s.setAttribute('data-timestamp', +new Date());
                        (d.head || d.body).appendChild(s);
                    })();
                </script>

                <noscript>Please enable JavaScript to view the <a href="https://disqus.com/?ref_noscript" rel="nofollow">comments powered by Disqus.</a></noscript>
            </div>
        </div>
    </div>
</main>

<?php require_once 'terzo.php'; ?>
<script>
    function _(el){
        return document.getElementById(el);
    }

    function downloadFile (index) {
        //alert(names[index]);

        var ajax = new XMLHttpRequest();
        var formdata = new FormData();
        formdata.append("name", names[index]);
        formdata.append("projectID", <?php echo $project["ID"] ?>);
        ajax.addEventListener("load", function (event) {
            _("downloadNumber"+index).innerHTML=event.target.responseText;
            //alert("bella");
        }, false);

        ajax.addEventListener("error", function (event) {
            alert("error");
        }, false);

        ajax.open("POST", "/downloadFile");
        ajax.send(formdata);
    }

    function downloadProject () {
        //alert(names[index]);

        var ajax = new XMLHttpRequest();
        var formdata = new FormData();
        formdata.append("projectID", <?php echo $project["ID"] ?>);
        ajax.addEventListener("load", function (event) {
            //_("downloadNumber"+index).innerHTML=event.target.responseText;
            //alert("bella");
        }, false);

        ajax.addEventListener("error", function (event) {
            //alert("error");
        }, false);

        ajax.open("POST", "/downloadProject");
        ajax.send(formdata);
    }

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