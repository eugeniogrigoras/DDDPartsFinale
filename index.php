<?php
    require 'flight/Flight.php';
    require_once 'functions.php';

    if (isset($_COOKIE["ID"]) && !isset($_SESSION["ID"])) {
        $_SESSION["ID"]=$_COOKIE["ID"];
        $_SESSION["NOME"]=$_COOKIE["NOME"];
        $_SESSION["COGNOME"]=$_COOKIE["COGNOME"];
        $_SESSION["EMAIL"]=$_COOKIE["EMAIL"];
    }

    Flight::route('GET /', function(){
        if (logged()) {
            Flight::render('dashboard');
        } else {
            Flight::render('home');
        }   
        exit();
    });

    Flight::route('GET /explore', function(){
        Flight::render('explore');
        exit();
    });
    Flight::route('GET /explore/collections', function(){
        Flight::render('explore');
        exit();
    });

    Flight::route('GET /explore/category(/subcategory)', function(){
        Flight::render('explore');
        exit();
    });

    Flight::route('GET /register(/@message)', function($message){
        if (logged()) {
            Flight::redirect('/account');
        } else {
            Flight::render('register', array('message' =>  $message));
        }
        exit();
    });

    Flight::route('GET /login(/@message)', function($message){
        if (logged()) {
            Flight::redirect('/account');
        } else {
            Flight::render('login', array('message' =>  $message));
        }  
        exit(); 
    });

    Flight::route('GET /account(/@message)', function($message){
        if (logged()) {
            Flight::render('account', array('message' =>  $message));
        } else {
            Flight::redirect('/login');
        }  
        exit(); 
    });

    Flight::route('GET /create(/@message)', function($message){
        if (logged()) {
            Flight::render('create');
        } else {
            Flight::redirect('/login');
        }   
        exit();
    });

    Flight::route('GET /settings(/@message)', function($message){
        if (logged()) {
            Flight::render('settings', array('message' =>  $message));
        } else {
            Flight::redirect('/login');
        }   
        exit();
    });



    Flight::route('GET /search(/@message)', function($message){
            Flight::render('search', array('message' =>  $message)); 
            exit();
    });

    Flight::route('GET /project/@id(/thingview/@thingName)', function($id, $thingName){
            if (isset($thingName)) {
                Flight::render('thingview', array('projectID' =>  $id, 'thingName' => $thingName));
            } else {
                Flight::render('project', array('id' =>  $id));
            }   
            exit();     
    });
    Flight::route('GET /collection/@id', function($id){
            Flight::render('collection', array('id' =>  $id)); 
            exit();
    });

    Flight::route('GET /user/@id(/@message)', function($id, $message){
            $QUERY=executeQuery("select * FROM utenti where ID=".$id);
            $num = $QUERY->num_rows;
            if ($num!=0) {    
                if (logged()) {
                    if ($_SESSION["ID"]==$id) {
                        if (isset($_REQUEST["fx"])) {
                            Flight::redirect('/account?fx='.$_REQUEST["fx"]);
                        } else {
                            Flight::redirect('/account');
                        }
                    } else {
                        Flight::render('user', array('message' =>  $message, 'id' => $id));
                    }
                } else {
                    Flight::render('user', array('message' =>  $message, 'id' => $id));
                }              
            } else {
                echo "NO USER IN DATABASE;";
            }   
            exit();     
    });

    Flight::route('GET /activate', function() {
        if (isset($_REQUEST["hash"])) {
            $code=$_REQUEST["hash"];
           if (logged()) {
                Flight::redirect('/account');
            } else {
                $hash=rawurldecode($code);
                var2console($hash);
                $QUERY=executeQuery("select * from utenti where HASH='$hash'");
                if ($QUERY && ($QUERY->num_rows > 0)) {
                    $QUERY=executeQuery("update utenti SET ACCETTATO = '1' WHERE HASH = '$hash'");
                    if ($QUERY) {
                        Flight::redirect('/login/Activated');
                    }
                }
                //Flight::redirect('/login/Activation Error');
            } 
        } else {
            Flight::redirect('/login/Invalid Link');
        }
        exit();
           
    });

    //*********************************************************************
    //POST METHODS
    //*********************************************************************

    Flight::route('POST /login', function(){
        $email = $_POST['email'];
        $pass = $_POST['password'];
        if (!isset($email) || !isset($pass)) {
            Flight::redirect('/login/Data missing');
            exit();
        } else {
            $ris=executeQuery("select HASH as HASHCODE from utenti where utenti.EMAIL='$email'");
            $riga=$ris->fetch_assoc();
            $hash=$riga['HASHCODE'];
            if (!password_verify($pass, $hash)) {
                Flight::redirect('/login/Input Error');
                exit();
            }
            $ris=executeQuery("select * from utenti where utenti.EMAIL='$email' AND utenti.HASH='$hash'");
            if ($ris && ($ris->num_rows > 0)) {
                $riga=$ris->fetch_assoc();
                if($riga['ACCETTATO']=="0"){
                    Flight::redirect('/login/Activate your account');
                    exit();
                }
                $_SESSION["ID"]=$riga['ID'];
                $_SESSION["NOME"]=$riga['NOME'];
                $_SESSION["COGNOME"]=$riga['COGNOME'];
                $_SESSION["EMAIL"]=$riga['EMAIL'];
                if ((isset($_POST['remember'])) && ($_POST['remember']==true)) {
                    setcookie("ID", $riga['ID'], time() + (86400 * 30), "/");
                    setcookie("NOME", $riga['NOME'], time() + (86400 * 30), "/");
                    setcookie("COGNOME", $riga['COGNOME'], time() + (86400 * 30), "/");
                    setcookie("EMAIL", $riga['EMAIL'], time() + (86400 * 30), "/");
                    echo "true";
                }
                Flight::redirect('/');
                exit();
            } else {
                Flight::redirect('/login/Input error');
                exit();
            }
        }
        exit();
    });

    Flight::route('POST /create', function() {
        print_r($_POST);
        $title=$_POST['title'];
        $description=$_POST['description'];
        $subcategory=$_POST['subcategory'];

        $QUERY=executeQueryAndGetLastId("insert into progetti  (DESCRIZIONE, FK_CATEGORIA_SECONDARIA, FK_UTENTE, NOME) VALUES ('$description', $subcategory, $_SESSION[ID], '$title')");
        $lastId=$QUERY['id'];

        $projectPath = "users/".$_SESSION["NOME"]."-".$_SESSION["COGNOME"]."-".$_SESSION["EMAIL"]."/"."Project/";

        if (!file_exists($projectPath)) {
            mkdir($projectPath, 0777, true);
            copy('img/bg1.jpg', $projectPath."projectWallpaper.jpg");
        }

        $files = array_slice(scandir($projectPath), 2);
    
        foreach ($files as $file) {
            $QUERY=executeQuery("insert into parti_3d  (FK_PROGETTO, NOME) VALUES ('$lastId','$file')");
        }

        if (isset($_POST['tags'])) {
            $tags=$_POST['tags'];
            foreach ($tags as $tag) {
                $QUERY=executeQuery("insert ignore into tag (NOME) VALUES ('$tag')");
            }

            foreach ($tags as $tag) {
                $QUERY=executeQuery("select * from tag where NOME='$tag'");
                if ($QUERY && ($QUERY->num_rows > 0)) {
                    $riga=$QUERY->fetch_assoc();
                    $fkTag = $riga['ID'];
                    $QUERY=executeQuery("select * from progetti_hanno_tag where FK_PROGETTO=$lastId and FK_TAG=$fkTag");
                    if ($QUERY->num_rows == 0) {
                        $QUERY=executeQuery("insert into progetti_hanno_tag (FK_PROGETTO, FK_TAG) VALUES ('$lastId', '$fkTag')");
                    }
                }
            }
        }

        
        imageUpload($projectPath."projectWallpaper.jpg");

        rename ($projectPath,"users/".$_SESSION["NOME"]."-".$_SESSION["COGNOME"]."-".$_SESSION["EMAIL"]."/".$lastId."/");
        Flight::redirect("/project/$lastId");
        exit();
    });

    Flight::route('POST /register', function() {
        $name = $_POST['first-name'];
        $surname = $_POST['last-name'];
        $email = $_POST['email'];
        $city = $_POST['city'];
        $password = $_POST['password'];
        if (!isset($name) || !isset($surname) || !isset($email) || !isset($city) || !isset($password)) {
            Flight::redirect("/register/Data missing");
            exit();
        } else {
            $hash = password_hash($password, PASSWORD_BCRYPT, array("cost" => 10));
            if (!password_verify($password, $hash)) {
                Flight::redirect("/register/Error occured");
                exit();
            }
            $QUERY=executeQueryAndGetLastId("insert ignore into utenti  (NOME, COGNOME, EMAIL, DESCRIZIONE, ACCETTATO, FK_COMUNE, HASH) VALUES ('$name', '$surname', '$email', NULL, 'FALSE', '$city', '$hash')");
            if ($QUERY["id"]==0) {
                Flight::redirect("/register/Mail already exists");
                exit();
            } else {
                echo "New record created successfully. Last inserted ID is: " . $QUERY["id"];
                if (!file_exists("users/".$name."-".$surname."-".$email)) {
                    mkdir("users/".$name."-".$surname."-".$email, 0777, true);
                    copy('img/default.jpg', "users/".$name."-".$surname."-".$email."/profile.jpg");
                }

                //localMail($email, $name, $surname, $hash);
                altervistaMail($email, $name, $surname, $hash);

                imageUpload("users/".$name."-".$surname."-".$email."/profile.jpg");

                Flight::redirect("/login/Activate your account");
                exit();
            }
        }
        exit();
    });

    Flight::route('POST /controlPassword', function() {
        $email=$_SESSION["EMAIL"];
        $data=[];
        $ris=executeQuery("select HASH as HASHCODE from utenti where utenti.EMAIL='$email'");
        $riga=$ris->fetch_assoc();
        $hash=$riga['HASHCODE'];
        $pass=$_POST["password"];
        if (password_verify($pass, $hash)) {
            $data["status"]=true;
            //echo "OK";
        } else {
            $data["status"]=false;
        }
        echo json_encode($data, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP);
        //echo "OKOKOK";
        exit();
    });

    Flight::route('POST /settings', function() {
        if (isset($_FILES["fileToUpload"])) {
            imageUpload("users/".$_SESSION["NOME"]."-".$_SESSION["COGNOME"]."-".$_SESSION["EMAIL"]."/profile.jpg");
        }
        $data=requestData();
        if ($data) {
            if (isset($_POST["description"])) {
                if ($data["DESCRIZIONE"]!=$_POST["description"])  {
                        if(changeDescription($_POST["description"])) {
                            echo "Description changed";
                        }   
                    }
            }
            if (isset($_POST["password"])) {
                $pass=$_POST["password"];
                $hash=$data["HASH"];
                if (strlen($_POST["password"])!=0) {
                    if (!password_verify($pass, $hash)) {
                        if (changePassword($_POST["password"])) {
                            echo "Password changed";
                        }

                    }
                }
            }
            exit();
        }
        echo("Update Error");
        exit();
    });

    Flight::route('POST /logout', function() {
        $_SESSION=array();
        session_unset();
        session_destroy();
        setcookie("ID", "", time() - 3600);
        setcookie("NOME", "", time() - 3600);
        setcookie("COGNOME", "", time() - 3600);
        setcookie("EMAIL", "", time() - 3600);
        Flight::redirect("/login");
        exit();
    });

    Flight::route('POST /uploadFile', function() {
        // A list of permitted file extensions
        $allowed = array('png', 'jpg', 'zip', 'stl', 'jpeg', 'pdf', 'txt', 'obj');

        if(isset($_FILES['filesToUpload']) && $_FILES['filesToUpload']['error'] == 0){

            $extension = pathinfo($_FILES['filesToUpload']['name'], PATHINFO_EXTENSION);

            if(!in_array(strtolower($extension), $allowed)){
                echo 'Invalid Extension';
                exit();
            }

            $destinationPath = "users/".$_SESSION["NOME"]."-".$_SESSION["COGNOME"]."-".$_SESSION["EMAIL"]."/"."Project/";

            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0777, true);
                copy('img/bg1.jpg', $destinationPath."projectWallpaper.jpg");
            }

            if (file_exists($destinationPath.$_FILES['filesToUpload']['name'])) {
                echo 'File Already Exists';
                exit();
            }

            if(move_uploaded_file($_FILES['filesToUpload']['tmp_name'], $destinationPath.$_FILES['filesToUpload']['name'])){
                echo 'Uploaded';
                exit();
            }
        }

        echo 'Unknown Error';
        exit();
    });

    Flight::route('POST /deleteFile', function() {
        $file = $_POST["name"];
        $destinationPath = "users/".$_SESSION["NOME"]."-".$_SESSION["COGNOME"]."-".$_SESSION["EMAIL"]."/"."Project/".$file;
        if (!unlink($destinationPath)) {
            echo ("Error Deleting");
        } else {
            echo ("Deleted");
        }
        exit();
    });

    Flight::route('POST /deleteProjectFolder', function() {
        $destinationPath = "users/".$_SESSION["NOME"]."-".$_SESSION["COGNOME"]."-".$_SESSION["EMAIL"]."/"."Project/";
        if (file_exists($destinationPath)) {
            deleteDir($destinationPath);
        }
        exit();
    });

    Flight::route('POST /createCollection', function() {
        $data=[];
        $userID=$_POST["user_id"];
        $projectID=$_POST["project_id"];
        $description=$_POST["description"];
        $title=$_POST["name"];
        $collectionID = addCollection($title, $description);
        if ($collectionID) {
            sleep(1);
            $addProject=addProjectToCollection($projectID, $collectionID);
            if ($addProject) $data["message"] = $title." was created";
        }
        $COLLECTION = executeQuery("select * from collezioni_composte_da_progetti where FK_PROGETTO=".$projectID); 
        $QUERY=executeQuery("select * FROM collezioni where FK_UTENTE=".$_SESSION["ID"]);
        $data["myCollections"] = "$QUERY->num_rows"; 

        $data["projectID"] = $projectID;
        $data["inCollection"] = "$COLLECTION->num_rows";
        echo json_encode($data, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP);
        exit();
    });

    Flight::route('POST /getCollections', function() {
        echo getCollections($_SESSION["ID"], $_POST["projectID"]);
        exit();
    });

    Flight::route('POST /addProjectToCollection', function() {
        $data=[];
        $collectionID = $_POST["collectionID"];
        $projectID = $_POST["projectID"];
        if (inCollection($collectionID, $projectID)) {
            $QUERY = executeQuery("delete from collezioni_composte_da_progetti where FK_COLLEZIONE=".$collectionID." and FK_PROGETTO=".$projectID);
            $data["message"] = "Project was removed from selected collection";
        } else {
            $QUERY = executeQuery ("insert into collezioni_composte_da_progetti (FK_COLLEZIONE, FK_PROGETTO) values ('$collectionID','$projectID')");
            $data["message"] = "Project was added to selected collection";
        }
        $COLLECTION = executeQuery("select * from collezioni_composte_da_progetti where FK_PROGETTO=".$projectID); 

        $CollectionProjects = executeQuery("select DISTINCT COUNT(*) AS NUMERO FROM collezioni_composte_da_progetti, progetti, categorie_primarie, categorie_secondarie, utenti WHERE collezioni_composte_da_progetti.FK_PROGETTO=progetti.ID AND collezioni_composte_da_progetti.FK_COLLEZIONE=$collectionID AND categorie_secondarie.FK_CATEGORIA_PRIMARIA=categorie_primarie.ID AND utenti.ID=progetti.FK_UTENTE AND progetti.FK_CATEGORIA_SECONDARIA=categorie_secondarie.ID");

        $data["projectID"] = $projectID;
        $data["inCollection"] = "$COLLECTION->num_rows";
        $data["collectionProjectsNumber"] = $CollectionProjects->fetch_assoc()["NUMERO"];
        echo json_encode($data, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP);
        exit();
    });

    Flight::route('POST /follow', function() {
        $data=[];
        $requestID=$_POST["id"];
        $sessionID=$_SESSION["ID"];
        $fx=$_POST["fx"];

        if ($fx=="follow") {
            $QUERY=executeQuery("insert into utenti_seguono_utenti (FK_UTENTE, FK_UTENTE_SEGUITO) VALUES ($sessionID, $requestID)");
            $data['message']="FOLLOW";
        } else {
            $QUERY=executeQuery("delete from utenti_seguono_utenti where FK_UTENTE=$sessionID and FK_UTENTE_SEGUITO=$requestID");
            $data['message']="UNFOLLOW";    
        }
        $QUERY=executeQuery("select * FROM utenti_seguono_utenti where FK_UTENTE=".$sessionID);
        $data['userFollowingNumber'] = "$QUERY->num_rows";
        $QUERY=executeQuery("select * FROM utenti_seguono_utenti where FK_UTENTE_SEGUITO=".$sessionID);
        $data['userFollowersNumber'] = "$QUERY->num_rows";
        $data['sessionID'] = $sessionID;
        $QUERY=executeQuery("select * FROM utenti_seguono_utenti where FK_UTENTE=".$requestID);
        $data['usersFollowingNumber'] = "$QUERY->num_rows";
        $QUERY=executeQuery("select * FROM utenti_seguono_utenti where FK_UTENTE_SEGUITO=".$requestID);
        $data['usersFollowersNumber'] = "$QUERY->num_rows";
        $data['requestID'] = "$requestID";
        echo json_encode($data, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP);
        exit();
    });

    Flight::route('POST /getSubcategory', function() {
        $idcategory=$_POST['idcategory'];
        echo "<option value='' disabled selected>Subcategory</option>";
        $record = executeQuery("select * from categorie_secondarie where categorie_secondarie.FK_CATEGORIA_PRIMARIA=$idcategory"); 
        while ($riga=$record->fetch_assoc()) {
            echo "<option value='"
            .$riga['ID']
            ."'>".$riga['NOME']
            ."</option>";
        }
        exit();
    });

    Flight::route('POST /getProvince', function() {
        $idregione=$_POST['idregione'];
        echo "<option value='' disabled selected>Province</option>";
        $record = executeQuery("select * from province where province.idregione=$idregione"); 
        while ($riga=$record->fetch_assoc()) {
            echo "<option value='"
            .$riga['idprovincia']
            ."'>".$riga['nomeprovincia']
            ."</option>";
        }
        exit();
    });

    Flight::route('POST /downloadFile', function() {
        $name=$_POST["name"];
        $data=[];
        $projectID=$_POST["projectID"];
        $QUERY=executeQuery("update parti_3d set NUMERO_DOWNLOAD = (NUMERO_DOWNLOAD + 1) where FK_PROGETTO=$projectID and NOME='$name'");
        $QUERY=executeQuery("select NUMERO_DOWNLOAD from  parti_3d where FK_PROGETTO=$projectID and NOME='$name'");
        $riga=$QUERY->fetch_assoc();
        $data["downloads"]=$riga["NUMERO_DOWNLOAD"];
        echo json_encode($data, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP);
        exit();
    });

    Flight::route('POST /likeProject', function() {
        $data=[];
        $projectID=$_POST["projectID"];
        $userID=$_SESSION["ID"];
        $QUERY=executeQuery("select * from  utenti_like_progetti where FK_PROGETTO=$projectID and FK_UTENTE=$userID");  
        if ($QUERY->num_rows == 0) {
            $data["like"]=true;
            $QUERY=executeQuery("insert into utenti_like_progetti (FK_UTENTE, FK_PROGETTO) values ($userID, $projectID)");
        } else {
            $data["like"]=false;
            $QUERY=executeQuery("delete from utenti_like_progetti where FK_PROGETTO=$projectID and FK_UTENTE=$userID");
        }  
        $QUERY=executeQuery("select * from  utenti_like_progetti where FK_PROGETTO=$projectID");
        $data["likes"]="$QUERY->num_rows";
        $QUERY=executeQuery("select * from  utenti_like_progetti where FK_UTENTE=$userID");
        $data["userLikes"]="$QUERY->num_rows";
        //var2console($data);
        //echo var_dump(json_encode($data)), json_last_error());
        echo json_encode($data, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP);
        exit();
    });

    Flight::route('POST /downloadProject', function() {
        $projectID=$_POST["projectID"];
        $data=[];
        $QUERY=executeQuery("update progetti set NUMERO_DOWNLOAD = (NUMERO_DOWNLOAD + 1) where ID=$projectID");
        $QUERY=executeQuery("select NUMERO_DOWNLOAD from  progetti where ID=$projectID");
        $riga=$QUERY->fetch_assoc();
        $data["downloads"]=$riga["NUMERO_DOWNLOAD"];
        //var2console($data);
        echo json_encode($data, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP);
        exit();
    });

    Flight::route('POST /getComune', function() {
        $idprovincia=$_POST['idprovincia'];
        echo "<option value='' disabled selected>City</option>";
        $record = executeQuery("select * from comuni where comuni.idprovincia=$idprovincia"); 
        while ($riga=$record->fetch_assoc()) {
            echo "<option value='"
            .$riga['id']
            ."'>".$riga['nome']
            ."</option>";
        }
        exit();
    });

    Flight::route('POST /followCollection', function() {
        $data=[];
        $collectionID=$_POST["collectionID"];
        $userID=$_SESSION["ID"];
        $QUERY=executeQuery("select * from  utenti_seguono_collezioni where FK_COLLEZIONE=$collectionID and FK_UTENTE=$userID");  
        if ($QUERY->num_rows == 0) {
            $data["follow"]=true;
            $QUERY=executeQuery("insert into utenti_seguono_collezioni (FK_UTENTE, FK_COLLEZIONE) values ($userID, $collectionID)");
        } else {
            $data["follow"]=false;
            $QUERY=executeQuery("delete from utenti_seguono_collezioni where FK_COLLEZIONE=$collectionID and FK_UTENTE=$userID");
        }
        $QUERY=executeQuery("select * from  utenti_seguono_collezioni where FK_UTENTE=$userID");
        $data["userFollows"]="$QUERY->num_rows";
        $QUERY=executeQuery("select * from  utenti_seguono_collezioni where FK_COLLEZIONE=$collectionID");
        $data["collectionFollowersNumber"]="$QUERY->num_rows";
        echo json_encode($data, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP);
        exit();
    });

    Flight::start();

?>