<?php
	session_start();

	function deleteDir($dirPath) {
		if (!is_dir($dirPath)) {
			throw new InvalidArgumentException ("$dirPath must be a directory");
		}
		if (substr($dirPath, strlen($dirPath)-1, 1) != '/' ) {
			$dirPath ='/';
		}
		$files = glob($dirPath.'*',GLOB_MARK);
		foreach ($files as $file) {
			if (is_dir($file)) {
				self::deleteDir($file);
			} else {
				unlink($file);
			}
		}
		rmdir($dirPath);
	}

	function var2console($var, $name='', $now=true)
	{
	   if ($var === null)          $type = 'NULL';
	   else if (is_bool    ($var)) $type = 'BOOL';
	   else if (is_string  ($var)) $type = 'STRING['.strlen($var).']';
	   else if (is_int     ($var)) $type = 'INT';
	   else if (is_float   ($var)) $type = 'FLOAT';
	   else if (is_array   ($var)) $type = 'ARRAY['.count($var).']';
	   else if (is_object  ($var)) $type = 'OBJECT';
	   else if (is_resource($var)) $type = 'RESOURCE';
	   else                        $type = '???';
	   if (strlen($name)) {
	      str2console("$type $name = ".var_export($var, true).';', $now);
	   } else {
	      str2console("$type = "      .var_export($var, true).';', $now);
	   }
	}

	function str2console($str, $now=true)
	{
	   if ($now) {
	      echo "<script type='text/javascript'>\n";
	      echo "//<![CDATA[\n";
	      echo "console.log(", json_encode($str), ");\n";
	      echo "//]]>\n";
	      echo "</script>";
	   } else {
	      register_shutdown_function('str2console', $str, true);
	   }
	}

	function requestPath() {
		return "/users/".$_SESSION["NOME"]."-".$_SESSION["COGNOME"]."-".$_SESSION["EMAIL"];
	}

	function requestPathUser($NAME, $SURNAME, $EMAIL) {
		return "/users/".$NAME."-".$SURNAME."-".$EMAIL;
	}

	function requestData() {
		$data=executeQuery("select * from utenti where ID=".$_SESSION["ID"]);
		
		if ($data) {
			if ($data->num_rows > 0) {
	        	$riga=$data->fetch_assoc();
				$array = array(
		    		"ID" => $riga["ID"],
		    		"NOME" => $riga["NOME"],
		    		"COGNOME" => $riga["COGNOME"],
		    		"DESCRIZIONE" => $riga["DESCRIZIONE"],
		    		"EMAIL" => $riga["EMAIL"],
		    		"PASSWORD" => $riga["PASSWORD"],
		    		"COMUNE" => getComune($riga["FK_COMUNE"])
				);
				return $array;
			} else {
				return false;
			}
		} else {
			return false;
		}	
	}

	function requestDataUser($id) {
		$data=executeQuery("select * from utenti where ID=".$id);
		
		if ($data) {
			if ($data->num_rows > 0) {
	        	$riga=$data->fetch_assoc();
				$array = array(
		    		"ID" => $riga["ID"],
		    		"NOME" => $riga["NOME"],
		    		"COGNOME" => $riga["COGNOME"],
		    		"DESCRIZIONE" => $riga["DESCRIZIONE"],
		    		"EMAIL" => $riga["EMAIL"],
		    		"PASSWORD" => $riga["PASSWORD"],
		    		"COMUNE" => getComune($riga["FK_COMUNE"])
				);
				return $array;
			} else {
				return false;
			}
		} else {
			return false;
		}	
	}
	

	function getComune($FK_COMUNE) {
			$data=executeQuery("select comuni.nome, province.nomeprovincia, regioni.nomeregione from comuni, province, regioni where comuni.id='".$FK_COMUNE."' and comuni.idprovincia=province.idprovincia and comuni.idregione=regioni.idregione");
			if ($data) {
				if ($data->num_rows > 0) {
		        	$riga=$data->fetch_assoc();
					return $riga["nomeregione"]." - ".$riga["nomeprovincia"]." - ".$riga["nome"];
				} else {
					return false;
				}
			} else {
				return false;
			}
	}

	function getCitta($FK_COMUNE) {
			$data=executeQuery("select comuni.nome, regioni.nomeregione from comuni, province, regioni where comuni.id='".$FK_COMUNE."' and comuni.idprovincia=province.idprovincia and comuni.idregione=regioni.idregione");
			if ($data) {
				if ($data->num_rows > 0) {
		        	$riga=$data->fetch_assoc();
					return $riga["nomeregione"].", ".$riga["nome"];
				} else {
					return false;
				}
			} else {
				return false;
			}
	}

	function executeQuery($QUERY) {
		$conn= new mysqli("localhost","root","",'my_dddparts'); 

    	if ($conn->connect_error) {
        	die("Connection failed: " . $conn->connect_error);
        	mysqli_close($conn);
        	return false;
    	} else {
    		$ris=$conn->query($QUERY);
    		if ($ris) {
    			if (isset($_REQUEST["getpage"]) && $_REQUEST["getpage"]=='register') {
    				$_SESSION["LASTINSERTEDID"]=$conn->insert_id;
    			}	
    			mysqli_close($conn);
    			return $ris;
    		} else {
    			echo "Error: " . "<br>" . $conn->error;
    			mysqli_close($conn);
    			return false;
    		}
    	}
	}

	function executeQueryAndGetLastId($QUERY) {
		$conn= new mysqli("localhost","root","",'my_dddparts'); 

    	if ($conn->connect_error) {
        	die("Connection failed: " . $conn->connect_error);
        	mysqli_close($conn);
        	return false;
    	} else {
    		$ris=$conn->query($QUERY);
    		if ($ris) {
    			$ret = array(
		    		"ris" => $ris,
		    		"id" => $conn->insert_id
				);
    			mysqli_close($conn);
    			return $ret;
    		} else {
    			echo "Error: " . "<br>" . $conn->error;
    			mysqli_close($conn);
    			return false;
    		}
    	}
	}

	function changeDescription($DESCRIPTION) {
		$QUERY=executeQuery("update utenti set DESCRIZIONE = '$DESCRIPTION' where ID=".$_SESSION["ID"]);
		if($QUERY) {
			return true;
		} else {
			return false;
		}
	}
	function changePassword($PASSWORD) {
		$QUERY=executeQuery("update utenti set PASSWORD = '$PASSWORD' where ID=".$_SESSION["ID"]);
		if($QUERY) {
			return true;
		} else {
			return false;
		}
	}
	function changeName($NAME) {
		$QUERY=executeQuery("update utenti set NOME = '$NAME' where ID = ID=".$_SESSION["ID"]);
		if($QUERY) {
			return true;
		} else {
			return false;
		}
	}
	function changeSurname($SURNAME) {
		$QUERY=executeQuery("update utenti set COGNOME = '$SURNAME' where ID=".$_SESSION["ID"]);
		if($QUERY) {
			return true;
		} else {
			return false;
		}
	}

	function randomString($length) {
    	return substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, $length);
	}

	function localMail($email, $name, $surname, $randomString, $last_id) {
		require 'PHPMailer/PHPMailerAutoload.php';
		$mail = new PHPMailer;

        //$mail->SMTPDebug = 3;                               // Enable verbose debug output

        $mail->isSMTP();                                      // Set mailer to use SMTP
        $mail->Host = 'ssl://smtp.gmail.com;ssl://smtp.gmail.com';  // Specify main and backup SMTP servers
        $mail->SMTPAuth = true;                               // Enable SMTP authentication
        $mail->Username = 'info.dddparts@gmail.com';                 // SMTP username
        $mail->Password = '23dodici1996';                           // SMTP password
        $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
        $mail->Port = 465;                                    // TCP port to connect to

        $mail->setFrom('info.dddparts@gmail.com', 'DDDParts');
        $mail->addAddress($email, $name." ".$surname);     // Add a recipient             // Name is optional

        $mail->Subject = 'Attivazione acount DDDParts!';
        $mail->Body    = "Conferma il tuo account: http://localhost/activate/$randomString/$last_id";

        if(!$mail->send()) {
            echo 'Message could not be sent.';
            echo 'Mailer Error: ' . $mail->ErrorInfo;
        } else {
            echo 'Message has been sent';
        }
	}
	function altervistaMail($email, $randomString, $last_id) {
		$to  = $email;
        $subject = "Registrazione effettuata con successo!";
        $message = "Conferma il tuo account: http://dddparts.altervista.org/activate/$randomString/$last_id";
        $headers = 'From: DDDParts' . "\r\n" .
                   'Reply-To: info.dddparts@gmail.com' . "\r\n" .
                   'X-Mailer: PHP/' . phpversion();
        mail($to, $subject, $message, $headers);
	}

	function convertImage($originalImage, $outputImage, $quality)
	{
	    if(imagejpeg(imagecreatefromstring(file_get_contents($originalImage)), $outputImage, $quality)) {
	    	return true;
	    } else {
	    	return false;
	    }
	    
	}

	function imageUpload($target_file) {
		if ($_FILES['fileToUpload']['size'] == 0 || $_FILES['fileToUpload']['error'] != 0) {
            echo "Error!";     
        } else { 
            $_FILES['fileToUpload']['name']="profile.".pathinfo($_FILES['fileToUpload']['name'],PATHINFO_EXTENSION);
            $uploadOk = 1;
            $imageFileType = pathinfo($_FILES['fileToUpload']['name'],PATHINFO_EXTENSION);
            // Check if image file is a actual image or fake image
            $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
            if($check !== false) {
                echo "File is an image - " . $check["mime"] . ".";
                $uploadOk = 1;
            } else {
                echo "File is not an image.";
                $uploadOk = 0;
            }

            // Check file size
            if ($_FILES["fileToUpload"]["size"] > 5000000) {
                echo "Sorry, your file is too large.";
                $uploadOk = 0;
            }
            // Allow certain file formats
            if($imageFileType != "jpg" && $imageFileType != "jpeg") {
                echo "Sorry, only JPG & JPEG files are allowed.";
                $uploadOk = 0;
            }
            // Check if $uploadOk is set to 0 by an error
            if ($uploadOk == 0) {
                echo "Sorry, your file was not uploaded.";
            // if everything is ok, try to upload file
            } else {
            	if (convertImage($_FILES["fileToUpload"]["tmp_name"], $target_file, 100)) {
            		echo "The file ". basename( $_FILES["fileToUpload"]["name"]). " has been uploaded.";
            	}
                else {
                    echo "Sorry, there was an error uploading your file.";
                }
            }
        }
	}
	function curPageName() {
		return substr($_SERVER["SCRIPT_NAME"],strrpos($_SERVER["SCRIPT_NAME"],"/")+1);
	}
?>