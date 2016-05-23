<?php
	require_once 'functions.php';

	switch ($_REQUEST["fx"]) {
		case 'following': ?>
			<ul id="following">
			    <div class="row users container">
			        <?php 
			            $users=executeQuery('select * from utenti where utenti.ID IN (select FK_UTENTE_SEGUITO from utenti_seguono_utenti where FK_UTENTE='.$_SESSION["ID"].')');
			            //$users=executeQuery('select * from utenti');
			            while ($user=$users->fetch_assoc()) :
			        ?>
			        <li style="opacity: 0;">
			        <div class="col s12 m6 l4">
			            <div class="z-depth-1 card" style="background-color:white">
			                <div class="background2 card-image waves-effect waves-block waves-light activator" style="padding:12px 0;">
			                    <div id="avatar" class="z-depth-1 activator">
			                        <img class="activator" src="<?php echo requestPathUser($user["NOME"], $user["COGNOME"], $user["EMAIL"])."/profile.jpg" ?>">
			                    </div>
			                </div>
			                <div class="card-content truncate" style="padding:12px 15px;">
			                    <p class="truncate">
			                        <input 
			                        <?php 
			                            $data=executeQuery("select * from utenti_seguono_utenti where FK_UTENTE=".$_SESSION["ID"]." and FK_UTENTE_SEGUITO=".$user["ID"]);
			                            if ($data) {
			                                if ($data->num_rows > 0) {
			                                    echo "checked";
			                                }
			                            }
			                        ?> 
			                        type="checkbox" id="<?php echo $user["ID"]; ?>" />
			                        <label for="<?php echo $user["ID"]; ?>" style="font-weight:600; color:#424242"><?php echo $user["NOME"]." ".$user["COGNOME"] ?></label>
			                    </p>
			                </div>
			                
			                <div class="card-action" style="padding:0">
			                    <div class="center-align waves-effect col s6"style="padding:6px 0;"> 
			                        <div id="userFollowingNumber<?php echo $user["ID"]; ?>" class="number" style="font-weight:600; color:#424242;">
			                            <?php
			                                $QUERY=executeQuery("select * FROM utenti_seguono_utenti where FK_UTENTE=".$user["ID"]);
			                                echo $QUERY->num_rows; 
			                            ?>
			                        </div>
			                        <div class="subtitle truncate" style="color:#757575">FOLLOWING</div>
			                    </div>
			                    <div class="center-align waves-effect" style="width:50%; padding:6px 0; border-left:1px solid #ddd">
			                        <div id="userFollowersNumber<?php echo $user["ID"]; ?>" class="number" style="font-weight:600; color:#424242;">
			                            <?php
			                            $QUERY=executeQuery("select * FROM utenti_seguono_utenti where FK_UTENTE_SEGUITO=".$user["ID"]);
			                            echo $QUERY->num_rows; 
			                        ?>
			                        </div>
			                        <div class="subtitle truncate" style="color:#757575">FOLLOWERS</div>
			                    </div>
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
			            $users=executeQuery('select * from utenti where utenti.ID IN (select FK_UTENTE from utenti_seguono_utenti where FK_UTENTE_SEGUITO='.$_SESSION["ID"].')');
			            //$users=executeQuery('select * from utenti');
			            while ($user=$users->fetch_assoc()) :
			        ?>
			        <li style="opacity: 0;">
			        <div class="col s12 m6 l4">
			            <div class="z-depth-1 card" style="background-color:white">
			                <div class="background2 card-image waves-effect waves-block waves-light activator" style="padding:12px 0;">
			                    <div id="avatar" class="z-depth-1 activator">
			                        <img class="activator" src="<?php echo requestPathUser($user["NOME"], $user["COGNOME"], $user["EMAIL"])."/profile.jpg" ?>">
			                    </div>
			                </div>
			                <div class="card-content" style="padding:12px 15px;">
			                    <p class="truncate">
			                        <input 
			                        <?php 
			                            $data=executeQuery("select * from utenti_seguono_utenti where FK_UTENTE=".$_SESSION["ID"]." and FK_UTENTE_SEGUITO=".$user["ID"]);
			                            if ($data) {
			                                if ($data->num_rows > 0) {
			                                    echo "checked";
			                                }
			                            }
			                        ?> 
			                        type="checkbox" id="<?php echo $user["ID"]; ?>" />
			                        <label for="<?php echo $user["ID"]; ?>" style="font-weight:600; color:#424242"><?php echo $user["NOME"]." ".$user["COGNOME"] ?></label>
			                    </p>
			                </div>
			                
			                <div class="card-action" style="padding:0">
			                    <div class="center-align waves-effect col s6"style="padding:6px 0;"> 
			                        <div id="userFollowingNumber<?php echo $user["ID"]; ?>" class="number" style="font-weight:600; color:#424242;">
			                            <?php
			                                $QUERY=executeQuery("select * FROM utenti_seguono_utenti where FK_UTENTE=".$user["ID"]);
			                                echo $QUERY->num_rows; 
			                            ?>
			                        </div>
			                        <div class="subtitle truncate" style="color:#757575">FOLLOWING</div>
			                    </div>
			                    <div class="center-align waves-effect" style="width:50%; padding:6px 0; border-left:1px solid #ddd">
			                        <div id="userFollowersNumber<?php echo $user["ID"]; ?>" class="number" style="font-weight:600; color:#424242;">
			                            <?php
			                            $QUERY=executeQuery("select * FROM utenti_seguono_utenti where FK_UTENTE_SEGUITO=".$user["ID"]);
			                            echo $QUERY->num_rows; 
			                        ?>
			                        </div>
			                        <div class="subtitle truncate" style="color:#757575">FOLLOWERS</div>
			                    </div>
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
		                $projects=executeQuery('select progetti.ID, progetti.NOME AS NOME_PROGETTO, progetti.DESCRIZIONE, progetti.FK_UTENTE, utenti.NOME AS NOME_UTENTE, utenti.COGNOME, utenti.EMAIL, categorie_primarie.NOME AS CATEGORIA_PRIMARIA, categorie_secondarie.NOME AS CATEGORIA_SECONDARIA, COUNT(*) AS FILES FROM progetti, utenti, categorie_primarie, categorie_secondarie, parti_3d WHERE progetti.FK_CATEGORIA_SECONDARIA=categorie_secondarie.ID AND categorie_secondarie.FK_CATEGORIA_PRIMARIA=categorie_primarie.ID AND progetti.FK_UTENTE=utenti.ID AND parti_3d.FK_PROGETTO=progetti.ID GROUP BY progetti.ID');
		                while ($project=$projects->fetch_assoc()) : 
		            ?>
		            <li style="opacity: 0;">
		                <div class="col s12 m6 l4">
		                    <div class="z-depth-1 card" style="background-color:white">
		                        <div class="card-image waves-effect waves-block waves-light activator" style="background-image:url('<?php echo "/users/".$project["NOME_UTENTE"]."-".$project["COGNOME"]."-".$project["EMAIL"]."/".$project["ID"]; ?>/projectWallpaper.jpg')"></div>
		                        <div class="card-content truncate" style="padding:12px 15px;">
		                            <p style="font-size:20px; margin-bottom:6px;"><?php echo $project["NOME_PROGETTO"] ?></p>
		                            <p class="truncate"><a href="#"><?php echo $project["NOME_UTENTE"]." ".$project["COGNOME"] ?></a></p>
		                        </div>
		                        <div class="card-action" style="padding:0">
				                    <div class="center-align waves-effect col s6"style="padding:6px 0;"> 
				                        <div class="number" style="font-weight:600; color:#424242;">
				                            24
				                        </div>
				                        <div class="subtitle truncate" style="color:#757575">LIKES</div>
				                    </div>
				                    <div class="center-align waves-effect" style="width:50%; padding:6px 0; border-left:1px solid #ddd">
				                        <div class="number" style="font-weight:600; color:#424242;">
				                            52
				                        </div>
				                        <div class="subtitle truncate" style="color:#757575">IN COLLECTION</div>
				                    </div>
				                </div>
		                        <div class="card-reveal">
		                            <span class="card-title"><i class="material-icons right noselect">close</i><?php echo $project["NOME_PROGETTO"]?></span>
		                            <p class="valign-wrapper"><i class="valign material-icons noselect" style="margin-right:20px;">folder</i><?php echo $project["FILES"] ?> Files</p>
		                            <p class="valign-wrapper"><i class="valign material-icons noselect" style="margin-right:20px;">layers</i><?php echo $project["CATEGORIA_SECONDARIA"] ?></p>
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

		default:
			//header("location: /");
			exit();
			break;
	}
?>
