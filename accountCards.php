<?php
	require_once 'functions.php';

	switch ($_POST["fx"]) {
		case 'following': ?>
			<ul id="following">
			    <div class="row users container">
			        <?php 
			            $users=executeQuery('select * from utenti where utenti.ID IN (select FK_UTENTE_SEGUITO from utenti_seguono_utenti where FK_UTENTE='.$_POST["id"].')');
			            //$users=executeQuery('select * from utenti');
			            while ($user=$users->fetch_assoc()) :
			        ?>
			        <li style="opacity: 0;">
			        <div class="col s12 m6 l4">
			            <div class="z-depth-1 card hoverable" style="background-color:white">
			                <div class="background2 waves-effect waves-block waves-light activator" style="padding:12px 0;">
			                    <div id="avatar" class="z-depth-1 activator" style="background-image:url('<?php echo requestPathUser($user["NOME"], $user["COGNOME"], $user["EMAIL"])."/profile.jpg"?>')">
			                    	
			                    </div>
			                </div>
			                
			                <div class="card-content" style="padding:12px 15px;">
			                    <p class="truncate">
			                        <input 
			                        <?php 
			                        	if (!logged()) {
			                        		echo "disabled";
			                        	} else {
			                        		$data=executeQuery("select * from utenti_seguono_utenti where FK_UTENTE=".$_SESSION["ID"]." and FK_UTENTE_SEGUITO=".$user["ID"]);
				                            if ($data) {
				                                if ($data->num_rows > 0) {
				                                    echo "checked ";
				                                }
				                            }
				                            if ($user["ID"]==$_SESSION["ID"]) echo "disabled";
			                        	}		                            
			                        ?> 
			                        type="checkbox" id="<?php echo $user["ID"]; ?>" />
			                        <label for="<?php echo $user["ID"]; ?>" style="font-weight:400; color:#424242"><a href="/user/<?php echo $user["ID"]; ?>" style="color:#424242!important"><?php echo $user["NOME"]." ".$user["COGNOME"] ?></a></label>            
			                    </p>
			                </div>
			                
			                <div class="card-content truncate" style="padding:0; border-top:1px solid #ddd;">
			                	<a href="/user/<?php echo $user["ID"]; ?>?fx=following">
				                    <div class="center-align waves-effect col s4"style="padding:6px 0; border-right:1px solid #ddd"> 
				                        <div id="usersFollowingNumber<?php echo $user["ID"]; ?>" class="number" style="font-weight:600; color:#424242;">
				                            <?php
				                                $QUERY=executeQuery("select * FROM utenti_seguono_utenti where FK_UTENTE=".$user["ID"]);
				                                echo $QUERY->num_rows; 
				                            ?>
				                        </div>
				                        <div class="subtitle truncate" style="color:#757575">FOLLOWING</div>
				                    </div>
			                    </a>
			                    <a href="/user/<?php echo $user["ID"]; ?>?fx=projects">
				                    <div class="center-align waves-effect col s4 " style="padding:6px 0; height:100%;">
					                    <div id="usersProjectsNumber<?php echo $user["ID"]; ?>" class="number" style="font-weight:600; color:#424242;">
				                            <?php
				                                $QUERY=executeQuery("select * FROM progetti where FK_UTENTE=".$user["ID"]);
				                                echo $QUERY->num_rows; 
				                            ?>
				                        </div>
					                    <div class="subtitle truncate" style="color:#757575">PROJECTS</div>
				                    </div>
			                    </a>
			                    <a href="/user/<?php echo $user["ID"]; ?>?fx=followers">
				                    <div class="center-align waves-effect col s4" style=" padding:6px 0; border-left:1px solid #ddd">
				                        <div id="usersFollowersNumber<?php echo $user["ID"]; ?>" class="number" style="font-weight:600; color:#424242;">
				                            <?php
				                            $QUERY=executeQuery("select * FROM utenti_seguono_utenti where FK_UTENTE_SEGUITO=".$user["ID"]);
				                            echo $QUERY->num_rows; 
				                        ?>
				                        </div>
				                        <div class="subtitle truncate" style="color:#757575">FOLLOWERS</div>
				                    </div>
			                    </a>
			                </div>

			                <div class="card-reveal">
			                    <span class="card-title grey-text text-darken-4"><i class="material-icons right noselect">close</i><?php echo $user["NOME"]." ".$user["COGNOME"] ?></span>
			                    <p class="valign-wrapper"><i class="valign material-icons noselect" style="margin-right:20px;">email</i><?php echo $user["EMAIL"] ?></p>
			                    <p class="valign-wrapper"><i class="valign material-icons noselect" style="margin-right:20px;">place</i><?php echo getComune($user["FK_COMUNE"]) ?></p>
			                    <p class="valign-wrapper"><i class="valign material-icons noselect" style="margin-right:20px;">description</i><?php echo $user["DESCRIZIONE"] ?></p>
			                </div>
			            </div>
			        </div>
			        </li>
			        <?php endwhile; ?>            
			    </div>
			</ul>

			<?php


			exit();
			break;

		case 'followers':?>
			<ul id="followers">
			    <div class="row users container">
			        <?php 
			            $users=executeQuery('select * from utenti where utenti.ID IN (select FK_UTENTE from utenti_seguono_utenti where FK_UTENTE_SEGUITO='.$_POST["id"].')');
			            //$users=executeQuery('select * from utenti');
			            while ($user=$users->fetch_assoc()) :
			        ?>
			        <li style="opacity: 0;">
			        <div class="col s12 m6 l4">
			            <div class="z-depth-1 card hoverable" style="background-color:white">
			                <div class="background2 waves-effect waves-block waves-light activator" style="padding:12px 0;">
			                    <div id="avatar" class="z-depth-1 activator" style="background-image:url('<?php echo requestPathUser($user["NOME"], $user["COGNOME"], $user["EMAIL"])."/profile.jpg"?>')">

			                    </div>
			                </div>
			                <div class="card-content" style="padding:12px 15px;">
			                    <p class="truncate">
			                        <input 
			                        <?php 
			                        	if (!logged()) {
			                        		echo "disabled";
			                        	} else {
			                        		$data=executeQuery("select * from utenti_seguono_utenti where FK_UTENTE=".$_SESSION["ID"]." and FK_UTENTE_SEGUITO=".$user["ID"]);
				                            if ($data) {
				                                if ($data->num_rows > 0) {
				                                    echo "checked";
				                                }
				                            }
				                            if ($user["ID"]==$_SESSION["ID"]) echo "disabled";
			                        	}        
			                        ?> 
			                        type="checkbox" id="<?php echo $user["ID"]; ?>" />
			                        <label for="<?php echo $user["ID"]; ?>" style="font-weight:400; color:#424242"><a href="/user/<?php echo $user["ID"]; ?>" style="color:#424242!important"><?php echo $user["NOME"]." ".$user["COGNOME"] ?></a></label>            
			                    </p>
			                </div>
			                
			                <div class="card-content truncate" style="padding:0; border-top:1px solid #ddd;">
			                	<a href="/user/<?php echo $user["ID"]; ?>?fx=following">
				                    <div class="center-align waves-effect col s4"style="padding:6px 0; border-right:1px solid #ddd"> 
				                        <div id="usersFollowingNumber<?php echo $user["ID"]; ?>" class="number" style="font-weight:600; color:#424242;">
				                            <?php
				                                $QUERY=executeQuery("select * FROM utenti_seguono_utenti where FK_UTENTE=".$user["ID"]);
				                                echo $QUERY->num_rows; 
				                            ?>
				                        </div>
				                        <div class="subtitle truncate" style="color:#757575">FOLLOWING</div>
				                    </div>
			                    </a>
			                    <a href="/user/<?php echo $user["ID"]; ?>?fx=projects">
				                    <div class="center-align waves-effect col s4 " style="padding:6px 0; height:100%;">
					                    <div id="usersProjectsNumber<?php echo $user["ID"]; ?>" class="number" style="font-weight:600; color:#424242;">
				                            <?php
				                                $QUERY=executeQuery("select * FROM progetti where FK_UTENTE=".$user["ID"]);
				                                echo $QUERY->num_rows; 
				                            ?>
				                        </div>
					                    <div class="subtitle truncate" style="color:#757575">PROJECTS</div>
				                    </div>
			                    </a>
			                    <a href="/user/<?php echo $user["ID"]; ?>?fx=followers">
				                    <div class="center-align waves-effect col s4" style=" padding:6px 0; border-left:1px solid #ddd">
				                        <div id="usersFollowersNumber<?php echo $user["ID"]; ?>" class="number" style="font-weight:600; color:#424242;">
				                            <?php
				                            $QUERY=executeQuery("select * FROM utenti_seguono_utenti where FK_UTENTE_SEGUITO=".$user["ID"]);
				                            echo $QUERY->num_rows; 
				                        ?>
				                        </div>
				                        <div class="subtitle truncate" style="color:#757575">FOLLOWERS</div>
				                    </div>
			                    </a>
			                </div>
			                <div class="card-reveal">
			                    <span class="card-title grey-text text-darken-4"><i class="material-icons right noselect">close</i><?php echo $user["NOME"]." ".$user["COGNOME"] ?></span>
			                    <p class="valign-wrapper"><i class="valign material-icons noselect" style="margin-right:20px;">email</i><?php echo $user["EMAIL"] ?></p>
			                    <p class="valign-wrapper"><i class="valign material-icons noselect" style="margin-right:20px;">place</i><?php echo getComune($user["FK_COMUNE"]) ?></p>
			                    <p class="valign-wrapper"><i class="valign material-icons noselect" style="margin-right:20px;">description</i><?php echo $user["DESCRIZIONE"] ?></p>
			                </div>
			            </div>
			        </div>
			        </li>
			        <?php endwhile; ?>            
			    </div>
			</ul>

			<?php


			exit();
			break;

		case 'projects': ?>

			<ul id="projects">
		        <div class="row projects container">
		            <?php 
		                //$users=executeQuery('select * from utenti where utenti.ID IN (select FK_UTENTE_SEGUITO from utenti_seguono_utenti where FK_UTENTE='.$_SESSION["ID"].')');
		                $projects=executeQuery('select progetti.ID, progetti.NOME AS NOME_PROGETTO, progetti.DESCRIZIONE, progetti.FK_UTENTE, utenti.ID AS ID_UTENTE, utenti.NOME AS NOME_UTENTE, utenti.COGNOME, utenti.EMAIL, categorie_primarie.NOME AS CATEGORIA_PRIMARIA, categorie_secondarie.NOME AS CATEGORIA_SECONDARIA, COUNT(*) AS FILES FROM progetti, utenti, categorie_primarie, categorie_secondarie, parti_3d WHERE progetti.FK_CATEGORIA_SECONDARIA=categorie_secondarie.ID AND utenti.ID='.$_POST["id"].' AND categorie_secondarie.FK_CATEGORIA_PRIMARIA=categorie_primarie.ID AND progetti.FK_UTENTE=utenti.ID AND parti_3d.FK_PROGETTO=progetti.ID GROUP BY progetti.ID ORDER BY progetti.ID DESC');
		                while ($project=$projects->fetch_assoc()) : 
		            ?>
		            <li style="opacity: 0;">
		                <div class="col s12 m6 l4" onmouseover="$('#save<?php echo $project["ID"] ?>').show()" onmouseout="$('#save<?php echo $project["ID"] ?>').hide()">
		                    <div class="z-depth-1 card hoverable" style="background-color:white">
		                        <div class="card-image waves-effect waves-block waves-light activator" style="padding:12px 15px; background-image:url('<?php echo "/users/".$project["NOME_UTENTE"]."-".$project["COGNOME"]."-".$project["EMAIL"]."/".$project["ID"]; ?>/projectWallpaper.jpg')">
		                        	<a data-projectid="<?php echo $project["ID"] ?>" onClick="save(this.id, this.name)" name="<?php echo $project["NOME_PROGETTO"] ?>" id="save<?php echo $project["ID"] ?>" class="white-text right waves-effect waves-light btn-flat" style="background-color:rgba(33,33,33,0.5); padding:0 15px; font-size:12px; display:none">Save
				                        <i class="material-icons left" style="font-size:17px">add</i>
				                    </a>
		                        </div>
		                        <div class="card-content truncate" style="padding:12px 15px;">
		                            <p class="truncate" style="font-size:20px; margin-bottom:6px;"><a href="/project/<?php echo $project["ID"] ?>" style="color:#212121!important"><?php echo $project["NOME_PROGETTO"] ?></a></p>
		                            <p class="truncate" style="font-size:13px">by <a href="/user/<?php echo $project["ID_UTENTE"]; ?>"><?php echo $project["NOME_UTENTE"]." ".$project["COGNOME"] ?></a></p>
		                        </div>
		                        <div class="card-content truncate" style="padding:12px 15px; border-top:1px solid #ddd">
		                        	<p style="font-size:13px; color:#999; margin-bottom:12px" class="truncate">Submitted in <a href="#"><?php echo $project["CATEGORIA_SECONDARIA"]?></a></p>
				                    <div class="col s4 truncate" style="padding:0; cursor:pointer">
				                    	<?php 
				                    		$LIKES = executeQuery("select * from utenti_like_progetti where FK_PROGETTO=".$project["ID"]);
				                    		$USER_LIKE = executeQuery("select * from utenti_like_progetti where FK_PROGETTO=".$project["ID"]." and FK_UTENTE=".$project["ID_UTENTE"]);
				                    	?>
				                    	<p class="center-align left valign-wrapper" style="color:#777;">
				                    		<i class="material-icons noselect valign" style="margin-right:5px; font-size:15px;
				                    		<?php 
				                    			if ($USER_LIKE->num_rows > 0) echo "color:#ff6e40";
				                    		?>
				                    		">
				                    		favorite
				                    		</i>
				                    		<span class="valign" style="font-size:13px">
				                    			<?php  echo $LIKES->num_rows; ?>
				                    		</span>
				                    	</p>
				                    </div>
				                    <div class="col s4 truncate" style="padding:0; cursor:pointer">
				                    	<?php $link= 'http://localhost/project/'.$project["ID"]."#disqus_thread" ?>
				                    	<p class="center-align valign-wrapper fit-content no_style" style="color:#777; margin:auto" onClick="location.href='<?php echo $link ?>'">
				                    		<i class="material-icons noselect valign" style="margin-right:5px; font-size:15px">comment</i>
				                    		<a class="" href="<?php echo $link; ?>" style="font-size:13px">0</a>
				                    	</p>
				                    </div>
				                    <div class="col s4 truncate" style="padding:0; cursor:pointer">
				                    	<p class="center-align right valign-wrapper" style="color:#777;">
				                    		<i class="material-icons noselect valign" style="margin-right:5px; font-size:15px">move_to_inbox
				                    		</i>
				                    		<span id="inCollection<?php echo $project["ID"]; ?>" class="valign" style="font-size:13px">
				                    			<?php $COLLECTION = executeQuery("select * from collezioni_composte_da_progetti where FK_PROGETTO=".$project["ID"]); echo $COLLECTION->num_rows; ?>
				                    		</span>
				                    	</p>
				                    </div>
				                </div>
		                        <div class="card-reveal">
		                            <span class="card-title"><i class="material-icons right noselect">close</i><?php echo $project["NOME_PROGETTO"]?></span>
		                            <p class="valign-wrapper"><i class="valign material-icons noselect" style="margin-right:20px;">description</i><?php echo $project["DESCRIZIONE"]?></p>
		                        </div>
		                    </div>
		                </div>
		            </li>
		            <?php endwhile; ?>
		        </div>
		    </ul>


		<?php 
			
			exit();
			break;

		case 'myCollections': ?>

			<ul id="myCollections">
		        <div class="row myCollections container">
		            <?php 
		                //$users=executeQuery('select * from utenti where utenti.ID IN (select FK_UTENTE_SEGUITO from utenti_seguono_utenti where FK_UTENTE='.$_SESSION["ID"].')');
		            	$userData=requestDataUser($_POST["id"]);
		                $collections=executeQuery('select * from collezioni where FK_UTENTE= '.$_POST["id"]);
		                while ($collection=$collections->fetch_assoc()) : 
		            ?>
		            <li style="opacity: 0;">
		                <div class="col s12 m6 l4" onmouseover="$('#follow<?php echo $collection["ID"] ?>').show()" onmouseout="$('#follow<?php echo $collection["ID"] ?>').hide()">
		                	<div class="z-depth-1 card hoverable" style="background-color:white">
		                		<div class="waves-effect waves-block waves-light activator" style="padding:0; background-color:#f0f0f0">
		                			<?php 
		                				$collectionID=$collection["ID"];
		                				$projects = executeQuery("select progetti.ID as ID_PROGETTO, progetti.FK_UTENTE as ID_UTENTE, utenti.NOME as NOME_UTENTE, utenti.COGNOME as COGNOME_UTENTE, utenti.EMAIL as EMAIL_UTENTE FROM progetti, collezioni_composte_da_progetti, utenti WHERE collezioni_composte_da_progetti.FK_PROGETTO=progetti.ID and progetti.FK_UTENTE=utenti.ID and collezioni_composte_da_progetti.FK_COLLEZIONE=$collectionID order by collezioni_composte_da_progetti.DATA_AGGIUNTA DESC");
		                				if ($projects->num_rows > 0):

		                					$j=0;
		                					while(($project=$projects->fetch_assoc()) || ($j<4)) :
		                					$j++;
		                					$url= "/users/".$project["NOME_UTENTE"]."-".$project["COGNOME_UTENTE"]."-".$project["EMAIL_UTENTE"]."/".$project["ID_PROGETTO"]."/projectWallpaper.jpg";
		                					$control= "users/".$project["NOME_UTENTE"]."-".$project["COGNOME_UTENTE"]."-".$project["EMAIL_UTENTE"]."/".$project["ID_PROGETTO"]."/projectWallpaper.jpg";
		                					if (!file_exists($control)) $url="";
		                					if ($j==1) :
		                			?>
			                			<div class="activator card-image col s12" style="height:150px; padding: 12px 15px; background-image:url('<?php echo $url; ?>')">
			                				<a id="follow<?php echo $collectionID; ?>" class="white-text right waves-effect waves-light btn-flat" style="background-color:rgba(33,33,33,0.5); padding:0 15px; font-size:12px; display:none">Follow
						                        <i class="material-icons left" style="font-size:17px">add</i>
						                    </a>
			                			</div>
			                        <?php 
			                        	else :
			                        ?>
			                    		<div class="activator card-image col s4" style="height:100px; background-image:url('<?php echo $url; ?>')"></div>
			                        <?php
			                        	endif;
			                        	endwhile; 
			                        	else : 
			                        		$url="";
			                        ?>
			                    		<div class="activator card-image col s12" style="height:250px; padding: 12px 15px; background-image:url('<?php echo $url; ?>')">
			                    			<a id="follow<?php echo $collectionID; ?>" class="white-text right waves-effect waves-light btn-flat" style="background-color:rgba(33,33,33,0.5); padding:0 15px; font-size:12px; display:none">Follow
						                        <i class="material-icons left" style="font-size:17px">add</i>
						                    </a>
			                    		</div>
			                        <?php
			                        	endif;
			                        ?>
		                        </div>
		                        <div class="card-content truncate" style="padding:12px 15px;">
		                            <p class="truncate" style="font-size:20px; margin-bottom:6px;"><a href="/collection/<?php echo $collection["ID"] ?>" style="color:#212121!important"><?php echo $collection["TITOLO"] ?></a></p>
		                            <p class="truncate" style="font-size:13px">by <a href="/user/<?php echo $userData["ID"]; ?>"><?php echo $userData["NOME"]." ".$userData["COGNOME"] ?></a></p>
		                        </div>
		                        <div class="card-content truncate" style="padding:12px 15px; border-top:1px solid #ddd">
				                    <div class="col s12 truncate" style="padding:0; font-size:10px; letter-spacing: 0.5px">
				                    	<?php 
				                    		if ($projects->num_rows==0) {
				                    			echo "THIS COLLECTION IS EMPTY ";
				                    		} else {
				                    			$PROJECTS = executeQuery("select * from collezioni_composte_da_progetti where FK_COLLEZIONE=".$collection["ID"]); 
			                    				if ($PROJECTS->num_rows>0) echo "<b>".$PROJECTS->num_rows."</b> POSTS ";
			                    				$FOLLOW = executeQuery("select * from utenti_seguono_collezioni where FK_COLLEZIONE=".$collection["ID"]); 
			                    				if ($FOLLOW->num_rows>0) echo "• <b>".$FOLLOW->num_rows."</b> FOLLOWERS";
				                    		}
		                    			?>
									</div>
				                </div>
				                <div class="card-reveal">
		                            <span class="card-title">
		                            	<i class="material-icons right noselect">close</i>
		                            	<?php echo $collection["TITOLO"] ?>
		                            </span>
		                            <p class="valign-wrapper">
		                            	<i class="valign material-icons noselect" style="margin-right:20px;">description</i>
		                            	<?php echo $collection["DESCRIZIONE"] ?>
		                            </p>
		                        </div>
		                	</div>
		                </div>
		            </li>
		            <?php endwhile; ?>
		        </div>
		    </ul>


		<?php 
			
			exit();
			break;

		case 'followedCollections': ?>

			<ul id="followedCollections">
		        <div class="row followedCollections container">
		            <?php 
		                //select * from utenti_seguono_collezioni, collezioni where utenti_seguono_collezioni.FK_COLLEZIONE=collezioni.ID and utenti_seguono_collezioni.FK_UTENTE=56
		                
		                $collections=executeQuery('select collezioni.ID, collezioni.TITOLO, collezioni.DATA_CREAZIONE, collezioni.FK_UTENTE, collezioni.DESCRIZIONE from utenti_seguono_collezioni, collezioni where utenti_seguono_collezioni.FK_COLLEZIONE=collezioni.ID and utenti_seguono_collezioni.FK_UTENTE='.$_POST["id"]);
		                
		                while ($collection=$collections->fetch_assoc()) :
		                $userData=requestDataUser($collection["FK_UTENTE"]); 
		            ?>
		            <li style="opacity: 0;">
		                <div class="col s12 m6 l4" onmouseover="$('#follow<?php echo $collection["ID"] ?>').show()" onmouseout="$('#follow<?php echo $collection["ID"] ?>').hide()">
		                	<div class="z-depth-1 card hoverable" style="background-color:white">
		                		<div class="waves-effect waves-block waves-light activator" style="padding:0; background-color:#f0f0f0">
		                			<?php 
		                				$collectionID=$collection["ID"];
		                				$projects = executeQuery("select progetti.ID as ID_PROGETTO, progetti.FK_UTENTE as ID_UTENTE, utenti.NOME as NOME_UTENTE, utenti.COGNOME as COGNOME_UTENTE, utenti.EMAIL as EMAIL_UTENTE FROM progetti, collezioni_composte_da_progetti, utenti WHERE collezioni_composte_da_progetti.FK_PROGETTO=progetti.ID and progetti.FK_UTENTE=utenti.ID and collezioni_composte_da_progetti.FK_COLLEZIONE=$collectionID order by collezioni_composte_da_progetti.DATA_AGGIUNTA DESC");
		                				if ($projects->num_rows > 0):

		                					$j=0;
		                					while(($project=$projects->fetch_assoc()) || ($j<4)) :
		                					$j++;
		                					$url= "/users/".$project["NOME_UTENTE"]."-".$project["COGNOME_UTENTE"]."-".$project["EMAIL_UTENTE"]."/".$project["ID_PROGETTO"]."/projectWallpaper.jpg";
		                					$control= "users/".$project["NOME_UTENTE"]."-".$project["COGNOME_UTENTE"]."-".$project["EMAIL_UTENTE"]."/".$project["ID_PROGETTO"]."/projectWallpaper.jpg";
		                					if (!file_exists($control)) $url="";
		                					if ($j==1) :
		                			?>
			                			<div class="activator card-image col s12" style="height:150px; padding: 12px 15px; background-image:url('<?php echo $url; ?>')">
			                				<a id="follow<?php echo $collectionID; ?>" class="white-text right waves-effect waves-light btn-flat" style="background-color:rgba(33,33,33,0.5); padding:0 15px; font-size:12px; display:none">Follow
						                        <i class="material-icons left" style="font-size:17px">add</i>
						                    </a>
			                			</div>
			                        <?php 
			                        	else :
			                        ?>
			                    		<div class="activator card-image col s4" style="height:100px; background-image:url('<?php echo $url; ?>')"></div>
			                        <?php
			                        	endif;
			                        	endwhile; 
			                        	else : 
			                        		$url="";
			                        ?>
			                    		<div class="activator card-image col s12" style="height:250px; padding: 12px 15px; background-image:url('<?php echo $url; ?>')">
			                    			<a id="follow<?php echo $collectionID; ?>" class="white-text right waves-effect waves-light btn-flat" style="background-color:rgba(33,33,33,0.5); padding:0 15px; font-size:12px; display:none">Follow
						                        <i class="material-icons left" style="font-size:17px">add</i>
						                    </a>
			                    		</div>
			                        <?php
			                        	endif;
			                        ?>
		                        </div>
		                        <div class="card-content truncate" style="padding:12px 15px;">
		                            <p class="truncate" style="font-size:20px; margin-bottom:6px;"><a href="/collection/<?php echo $collection["ID"] ?>" style="color:#212121!important"><?php echo $collection["TITOLO"] ?></a></p>
		                            <p class="truncate" style="font-size:13px">by <a href="/user/<?php echo $userData["ID"]; ?>"><?php echo $userData["NOME"]." ".$userData["COGNOME"] ?></a></p>
		                        </div>
		                        <div class="card-content truncate" style="padding:12px 15px; border-top:1px solid #ddd">
				                    <div class="col s12 truncate" style="padding:0; font-size:10px; letter-spacing: 0.5px">
				                    	<?php 
				                    		if ($projects->num_rows==0) {
				                    			echo "THIS COLLECTION IS EMPTY ";
				                    		} else {
				                    			$PROJECTS = executeQuery("select * from collezioni_composte_da_progetti where FK_COLLEZIONE=".$collection["ID"]); 
			                    				if ($PROJECTS->num_rows>0) echo "<b>".$PROJECTS->num_rows."</b> POSTS ";
			                    				$FOLLOW = executeQuery("select * from utenti_seguono_collezioni where FK_COLLEZIONE=".$collection["ID"]); 
			                    				if ($FOLLOW->num_rows>0) echo "• <b>".$FOLLOW->num_rows."</b> FOLLOWERS";
				                    		}
		                    			?>
									</div>
				                </div>
				                <div class="card-reveal">
		                            <span class="card-title">
		                            	<i class="material-icons right noselect">close</i>
		                            	<?php echo $collection["TITOLO"] ?>
		                            </span>
		                            <p class="valign-wrapper">
		                            	<i class="valign material-icons noselect" style="margin-right:20px;">description</i>
		                            	<?php echo $collection["DESCRIZIONE"] ?>
		                            </p>
		                        </div>
		                	</div>
		                </div>
		            </li>
		            <?php endwhile; ?>
		        </div>
		    </ul>


		<?php 
			
			exit();
			break;

		case 'likes': ?>

			<ul id="likes">
		        <div class="row likes container">
		        	<h1>Likes Under Developement</h1>
		            <?php 
		                //$users=executeQuery('select * from utenti where utenti.ID IN (select FK_UTENTE_SEGUITO from utenti_seguono_utenti where FK_UTENTE='.$_SESSION["ID"].')');
		            	$id=$_POST["id"];
		                $likes=executeQuery("select * FROM utenti_like_progetti where FK_UTENTE=".$id);
		                while ($like=$likes->fetch_assoc()) : 
		            ?>
		            <li style="opacity: 0;">
		                <p>Under Developement</p>
		            </li>
		            <?php endwhile; ?>
		        </div>
		    </ul>


		<?php 
			
			exit();
			break;

		default:
			//header("location: /");
			exit();
			break;
	}
?>
