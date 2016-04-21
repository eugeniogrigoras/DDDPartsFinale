<?php
    require 'flight/Flight.php';
    require_once 'functions.php';

    if (isset($_COOKIE["ID"]) && !isset($_SESSION["ID"])) {
    	$_SESSION["ID"]=$_COOKIE["ID"];
		$_SESSION["NOME"]=$_COOKIE["NOME"];
		$_SESSION["COGNOME"]=$_COOKIE["COGNOME"];
		$_SESSION["EMAIL"]=$_COOKIE["EMAIL"];
    }

    function logged () {
    	if (isset($_SESSION["ID"])) {
    		return true;
    	} else {
    		return false;
    	}	
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
            Flight::render('account');
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

