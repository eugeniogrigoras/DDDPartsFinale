<?php 
	require_once 'primo.php';
?>
<title>3D Viewer - <?=$thingName?></title>
<meta name="description" content="3D Preview in realtime">
<meta name="viewport" content="width=device-width, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0">
<?php
    $projects=executeQuery("select progetti.NOME, progetti.DESCRIZIONE, utenti.NOME as NOME_UTENTE, utenti.COGNOME, utenti.EMAIL from progetti, utenti where progetti.FK_UTENTE=utenti.ID and progetti.ID=$projectID");
    $project=$projects->fetch_assoc(); 
    //echo $projectID." ".$thingName;
    $projectPath = "/users/".$project["NOME_UTENTE"]."-".$project["COGNOME"]."-".$project["EMAIL"]."/".$projectID."/";
    $projectPath1 = "users/".$project["NOME_UTENTE"]."-".$project["COGNOME"]."-".$project["EMAIL"]."/".$projectID."/";
    $filePath =$projectPath.$thingName;
?>
<script src="/js/three.js"></script>


<script src="/js/STLLoader.js"></script>

<script src="/js/Detector.js"></script>
<script src="/js/stats.min.js"></script>
<script src="/js/Plane.js"></script>
<script src="/js/TrackballControls.js"></script>

<style>
  
    html, body {
        width: 100%;
        height: 100%;
        padding: 0px;
        margin: 0px;
        overflow: hidden;
    }
    #viewer {
        width: 100%;
        height: 100vh;
    }
    /* make all canvases stretch to their container. */
    canvas {
      width: 100%;
      height: 100%;
    }

    p a {
        color: rgba(255,109,64,1);
        border-bottom:0px solid #ddd;
    }

    p a:hover {
        border-bottom:1px solid rgba(255,109,64,1);
    }

     a.no_style:hover {
        border-bottom:0px solid rgba(255,109,64,1)!important;
    }

    .dropdown-content li {
      will-change: background-color;
      transition: background-color 0.1s;
    }

</style>


<?php require_once 'secondo.php'; ?>
<main style="background-color:#212121">
    <div class="row" style="text-align:center; padding-top:24px">
          <a style=" color:#fff; padding:0px 12px;" class='truncate no_style dropdown-button waves-effect btn-flat' href='#' data-activates='thingview'><i style="color:#fff" class="material-icons right">arrow_drop_down</i><?=$thingName?></a>
            <ul style="background-color:#ff6e40" id='thingview' class='dropdown-content'>
                <?php 
                    
                    foreach (glob($projectPath1."/*.stl") as $filename) {
                        //var2console($filename);
                        $file = str_replace($projectPath1."/", "", $filename);
                        if ($file==$thingName) {
                            echo "<li><a style='background-color:#f0f0f0' class='truncate no_style' href='/project/$projectID/thingview/$file'>$file</a></li>";
                        } else {
                            echo "<li><a class='truncate no_style' href='/project/$projectID/thingview/$file'>$file</a></li>";
                        }
                        
                    }
                    echo "<li class='divider'></li>";
                    echo "<li><a style='color:#212121' class='truncate no_style' href='/project/$projectID'>Project: $project[NOME]</a></li>";
                ?>
            </ul>
        </p>
        <div id="viewer" class="">
        </div>
    </div>
</main>

<?php require_once 'terzo.php'; ?>

<script>
      if ( ! Detector.webgl ) Detector.addGetWebGLMessage();

      var container, stats;

      var camera, cameraTarget, scene, renderer;

      var canvas = document.getElementById( "viewer" ) || document.body;

      init();
      animate();

      function init() {


        container = document.createElement( 'div' );
        document.getElementById('viewer').appendChild( container );

        camera = new THREE.PerspectiveCamera(45, canvas.clientWidth/ canvas.clientHeight, 1, 1000);
        this.camera.position.z = 300;
        this.camera.position.y = -500;
        this.camera.position.x = -500;
        this.camera.up = new THREE.Vector3(0,0,1);

        this.controls = new THREE.TrackballControls(this.camera,this.container);

        controls.addEventListener( 'change', render );

        //cameraTarget = new THREE.Vector3( 0, 0, 0 );

        scene = new THREE.Scene();
        //scene.fog = new THREE.Fog( 0x72645b, 0, 300 );
 

        // Ground
        this.reflectCamera = new THREE.CubeCamera(0.1,5000,512);
        this.scene.add(this.reflectCamera);



        // ASCII file

        var loader = new THREE.STLLoader();

        loader.load( '<?=$filePath?>', function ( geometry ) {

          var material = new THREE.MeshPhongMaterial( { color: 0xff5533, specular: 0x111111, shininess: 200 } );
          var mesh = new THREE.Mesh( geometry, material );

          mesh.position.set( 0, 0.5, 0 );
          mesh.rotation.set( 0, 0, 0 );
          mesh.scale.set( 1, 1, 1 );

          mesh.castShadow = true;
          mesh.receiveShadow = true;

          scene.add( mesh );

        } );


        // Lights

        scene.add( new THREE.HemisphereLight( 0xff6e40, 0x111122 ) );

        addShadowedLight( 0, 1, 1, 0xffffff, 1 );
        addShadowedLight( 0.5, 1, -1, 0xf0f0f0, 1 );
        // renderer

        renderer = new THREE.WebGLRenderer( { antialias: true } );
        renderer.setClearColor(0x212121, 1);
        renderer.setPixelRatio( window.devicePixelRatio );
        renderer.setSize( canvas.clientWidth, canvas.clientHeight );

        renderer.gammaInput = true;
        renderer.gammaOutput = true;

        renderer.shadowMap.enabled = true;
        renderer.shadowMap.renderReverseSided = false;

        container.appendChild( renderer.domElement );

        // stats

        //stats = new Stats();
        //container.appendChild( stats.dom );

        //

        window.addEventListener( 'resize', onWindowResize, false );

      }

      function addShadowedLight( x, y, z, color, intensity ) {

        var directionalLight = new THREE.DirectionalLight( color, intensity );
        directionalLight.position.set( x, y, z );
        scene.add( directionalLight );

        directionalLight.castShadow = true;

        var d = 1;
        directionalLight.shadow.camera.left = -d;
        directionalLight.shadow.camera.right = d;
        directionalLight.shadow.camera.top = d;
        directionalLight.shadow.camera.bottom = -d;

        directionalLight.shadow.camera.near = 1;
        directionalLight.shadow.camera.far = 4;

        directionalLight.shadow.mapSize.width = 1024;
        directionalLight.shadow.mapSize.height = 1024;

        directionalLight.shadow.bias = -0.005;

      }

      function onWindowResize() {

        camera.aspect = canvas.clientWidth / canvas.clientHeight;
        camera.updateProjectionMatrix();

        renderer.setSize( canvas.clientWidth, canvas.clientHeight );

        controls.handleResize();

      }

      function animate() {

        requestAnimationFrame( animate );
        controls.update();
        render();
        //stats.update();

      }

      function render() {

        camera.lookAt( scene.position );

        renderer.render( scene, camera );

      }

</script>

<?php require_once 'quarto.php'; ?>