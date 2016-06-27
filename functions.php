<?php
	session_start();
	//date_default_timezone_set('Europe/Rome');

	function logged () {
    	if (isset($_SESSION["ID"])) {
    		return true;
    	} else {
    		return false;
    	}	
    }

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

	function myCollection ($collectionID) {
        $userID=$_SESSION["ID"];
        $QUERY=executeQuery("select * from  collezioni where ID=$collectionID and FK_UTENTE=$userID");
        if ($QUERY->num_rows > 0) {
        	return true;
        } else {
        	return false;
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
		    		"HASH" => $riga["HASH"],
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

	function time_elapsed_posted_string($ptime) {
		$etime = time() - $ptime;

	    if ($etime < 1)
	    {
	        return '0 seconds';
	    }

	    $a = array( 365 * 24 * 60 * 60  =>  'year',
	                 30 * 24 * 60 * 60  =>  'month',
	                      24 * 60 * 60  =>  'day',
	                           60 * 60  =>  'hour',
	                                60  =>  'minute',
	                                 1  =>  'second'
	                );
	    $a_plural = array( 'year'   => 'years',
	                       'month'  => 'months',
	                       'day'    => 'days',
	                       'hour'   => 'hours',
	                       'minute' => 'minutes',
	                       'second' => 'seconds'
	                );

	    foreach ($a as $secs => $str)
	    {
	        $d = $etime / $secs;
	        if ($d >= 1)
	        {
	            $r = round($d);
	            return $r . ' ' . ($r > 1 ? $a_plural[$str] : $str). " ago";
	        }
	    }
	}

	function time_elapsed_string($ptime)
	{
	    $etime = time() - $ptime;

	    if ($etime < 1)
	    {
	        return '0 seconds';
	    }

	    $a = array( 365 * 24 * 60 * 60  =>  'year',
	                 30 * 24 * 60 * 60  =>  'month',
	                      24 * 60 * 60  =>  'day',
	                           60 * 60  =>  'hour',
	                                60  =>  'minute',
	                                 1  =>  'second'
	                );
	    $a_plural = array( 'year'   => 'years',
	                       'month'  => 'months',
	                       'day'    => 'days',
	                       'hour'   => 'hours',
	                       'minute' => 'minutes',
	                       'second' => 'seconds'
	                );

	    foreach ($a as $secs => $str)
	    {
	        $d = $etime / $secs;
	        if ($d >= 1)
	        {
	            $r = round($d);
	            return $r . ' ' . ($r > 1 ? $a_plural[$str] : $str);
	        }
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
		    		"HASH" => $riga["HASH"],
		    		"FK_COMUNE" => $riga["FK_COMUNE"],
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
		$hash = password_hash($PASSWORD, PASSWORD_BCRYPT, array("cost" => 10));
		$QUERY=executeQuery("update utenti set HASH = '$hash' where ID=".$_SESSION["ID"]);
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

	function localMail($email, $name, $surname, $hash) {
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
        $code=rawurlencode($hash);
        $mail->Body    = "Conferma il tuo account: http://localhost/activate?hash=$code";

        if(!$mail->send()) {
            echo 'Message could not be sent.';
            echo 'Mailer Error: ' . $mail->ErrorInfo;
        } else {
            echo 'Message has been sent';
        }
	}
	function altervistaMail($email, $name, $surname, $hash) {
		$to  = $email;
		$code=rawurlencode($hash);
        $subject = "Registrazione effettuata con successo!";
        $message = "Conferma il tuo account: http://dddparts.altervista.org/activate?hash=$code";
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

	function getCollections ($userId, $projectId) {
		$COLLECTIONS = executeQuery("select * from collezioni where FK_UTENTE=".$userId." order by collezioni.ID DESC");
		while ($collection=$COLLECTIONS->fetch_assoc()): ?>
			<li onclick="addProjectToCollection(<?php echo $projectId.",".$collection["ID"] ?>)" class="collection-item avatar <?php $in = inCollection($collection['ID'], $projectId); if ($in) echo 'selected-collection' ?>" style="cursor:pointer" onmouseover="this.style.backgroundColor='#e0e0e0'" onmouseout="this.style.backgroundColor='white'">
      			<?php 
      				$QUERY = executeQuery("select * from collezioni_composte_da_progetti where FK_COLLEZIONE=".$collection['ID']);
      				if ($QUERY) {
						if ($QUERY->num_rows > 0) {
							
							$MAX = executeQuery("select DISTINCT collezioni_composte_da_progetti.ID, collezioni_composte_da_progetti.FK_PROGETTO, utenti.NOME, utenti.COGNOME, utenti.EMAIL 
FROM collezioni, collezioni_composte_da_progetti, utenti, progetti
WHERE collezioni_composte_da_progetti.ID=(SELECT MAX(ID) FROM collezioni_composte_da_progetti WHERE collezioni_composte_da_progetti.FK_COLLEZIONE=".$collection['ID'].") AND progetti.FK_UTENTE=utenti.ID AND progetti.ID=collezioni_composte_da_progetti.FK_PROGETTO;");
							$ris=$MAX->fetch_assoc();
							$backgroundUrl='/users/'.$ris["NOME"].'-'.$ris["COGNOME"].'-'.$ris["EMAIL"].'/'.$ris["FK_PROGETTO"].'/projectWallpaper.jpg';
							echo "<div class='collection-image' style='background-image:url($backgroundUrl)'></div>";
						} else {
							echo "<i class='material-icons circle'>folder</i>";
						}
					} 
      			?>
      			
      			<span class="title truncate" style='margin-right:30px; color:#444;'><b><?php echo $collection["TITOLO"]; ?></b></span>
      			<p class='truncate' style='margin-right:30px; color:#444'><span>Projects: <b><?php echo getCollectionProjectsNumber($collection["ID"]); ?></b></span><br><?php echo $collection["DESCRIZIONE"]; ?></p>
      			<a class="secondary-content"><i class="material-icons <?php if ($in) {echo "deep-orange-text text-accent-2";} else {echo "grey-text text-darken-3";} ?> "><?php if ($in) {echo "done";} else {echo "add";} ?></i></a>
    		</li>
		<?php 
		endwhile;
	}

	function inCollection ($collectionID, $projectID) {
		$QUERY = executeQuery("select * from collezioni_composte_da_progetti where FK_PROGETTO=".$projectID." and FK_COLLEZIONE=".$collectionID);
		if ($QUERY) {
			if ($QUERY->num_rows > 0) {
				return true;
			} else {
				return false;
			}
		}
	}

	function getCollectionProjectsNumber ($collectionID) {
		$COLLECTIONS = executeQuery("select * from collezioni_composte_da_progetti where FK_COLLEZIONE=".$collectionID);
		echo $COLLECTIONS->num_rows;
	}

	function addCollection ($title, $description) {
		$userID=$_SESSION["ID"];
		$QUERY=executeQueryAndGetLastId("insert ignore into collezioni  (FK_UTENTE, TITOLO, DESCRIZIONE) VALUES ('$userID','$title','$description')");
		if ($QUERY) return $QUERY["id"];
	}

	function addProjectToCollection ($projectID, $collectionID) {
		$QUERY=executeQuery("insert into collezioni_composte_da_progetti  (FK_COLLEZIONE, FK_PROGETTO) VALUES ('$collectionID','$projectID')");
		if ($QUERY) return true;
	}


	function createZip ($path, $zipName) {
		$rootPath = realpath($path);
	    //$zipID = uniqid(time());
	    //$zipName = $rootPath."/".$zipID.".zip";

	    // Initialize archive object
	    $zip = new ZipArchive();
	    $zip->open($zipName, ZipArchive::CREATE | ZipArchive::OVERWRITE);

	    // Create recursive directory iterator
	    /** @var SplFileInfo[] $files */
	    $files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($rootPath), RecursiveIteratorIterator::LEAVES_ONLY);

	    foreach ($files as $name => $file) {
	        // Skip directories (they would be added automatically)
	        if (!$file->isDir()) {
	            // Get real and relative path for current file
	            $filePath = $file->getRealPath();
	            $relativePath = substr($filePath, strlen($rootPath) + 1);

	            // Add current file to archive
	            $zip->addFile($filePath, $relativePath);
	        }
	    }

	    // Zip archive will be created only after closing object
	    $zip->close();
	}


	//....................................
	//DASHBOARD
	//....................................

	function getProject($id) {
		$QUERY=executeQuery("select progetti.ID, progetti.NOME AS NOME_PROGETTO, progetti.DESCRIZIONE, progetti.FK_UTENTE, categorie_primarie.NOME AS CATEGORIA_PRIMARIA, categorie_secondarie.NOME AS CATEGORIA_SECONDARIA, COUNT(*) AS FILES, progetti.NUMERO_DOWNLOAD FROM progetti, categorie_primarie, categorie_secondarie, parti_3d WHERE progetti.FK_CATEGORIA_SECONDARIA=categorie_secondarie.ID AND categorie_secondarie.FK_CATEGORIA_PRIMARIA=categorie_primarie.ID AND progetti.ID=$id AND parti_3d.FK_PROGETTO=progetti.ID GROUP BY progetti.ID");
		if ($QUERY) {
			$project=$QUERY->fetch_assoc();
			return	$project;	
		}
		return false;
	}

	function getCollection($id) {
		$QUERY=executeQuery("SELECT collezioni.ID, collezioni.TITOLO, collezioni.DATA_CREAZIONE, collezioni.FK_UTENTE, collezioni.DESCRIZIONE, COUNT(*) as NUMERO_PROGETTI FROM collezioni, collezioni_composte_da_progetti WHERE collezioni_composte_da_progetti.FK_COLLEZIONE=collezioni.ID AND collezioni.ID=$id");
		if ($QUERY) {
			$collection=$QUERY->fetch_assoc();
			return	$collection;	
		}
		return false;
	}

?>