<?php
    require 'flight/Flight.php';
    require_once 'functions.php';

    if (isset($_COOKIE["ID"]) && !isset($_SESSION["ID"])) {
    	$_SESSION["ID"]=$_COOKIE["ID"];
		$_SESSION["NOME"]=$_COOKIE["NOME"];
		$_SESSION["COGNOME"]=$_COOKIE["COGNOME"];
		$_SESSION["EMAIL"]=$_COOKIE["EMAIL"];
    }

    Flight::route('/', function(){
    	if (logged()) {
    		Flight::render('dashboard');
    	} else {
    		Flight::render('home');
    	}	
        
    });

    Flight::route('/register(/@message)', function($message){
    	if (logged()) {
    		Flight::redirect('/account');
    	} else {
        	Flight::render('register', array('message' =>  $message));
        }
    });

    Flight::route('/login(/@message)', function($message){
    	if (logged()) {
    		Flight::redirect('/account');
    	} else {
    		Flight::render('login', array('message' =>  $message));
    	}   
    });

    Flight::route('/account(/@message)', function($message){
        if (logged()) {
            Flight::render('account', array('message' =>  $message));
        } else {
            Flight::redirect('/login');
        }   
    });

    Flight::route('/create(/@message)', function($message){
        if (logged()) {
            Flight::render('create');
        } else {
            Flight::redirect('/login');
        }   
    });

    Flight::route('/settings(/@message)', function($message){
        if (logged()) {
            Flight::render('settings', array('message' =>  $message));
        } else {
            Flight::redirect('/login');
        }   
    });

    Flight::route('/search(/@message)', function($message){
            Flight::render('search', array('message' =>  $message)); 
    });

    Flight::route('/project/@id', function($id){
            Flight::render('project', array('id' =>  $id)); 
    });

    Flight::route('/user/@id(/@message)', function($id, $message){
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
    });

/*    Flight::route('POST /settings(/@message)', function($message){
        if (logged()) {
            echo "POST";
        } else {
            Flight::redirect('/login');
        }   
    });*/

    Flight::route('/activate/@codice/@id', function($codice, $id){
        if (logged()) {
            Flight::redirect('/account');
        } else {
            $QUERY=executeQuery("select * from utenti where CODICE_CONFERMA='$codice' and ID='$id'");
            if ($QUERY && ($QUERY->num_rows > 0)) {
                $QUERY=executeQuery("update utenti SET ACCETTATO = '1' WHERE ID = '$id'");
                if ($QUERY) {
                    Flight::redirect('/login/Activated');
                }
            }
            Flight::redirect('/login/Activation Error');
        }   
    });


    Flight::start();

?>

