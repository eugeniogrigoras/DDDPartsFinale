<?php
	require_once 'functions.php';
	$items_per_group = 10;
	$userID=$_SESSION["ID"];

	if($_POST)
	{
		//sanitize post value
		$group_number = filter_var($_POST["group_no"], FILTER_SANITIZE_NUMBER_INT, FILTER_FLAG_STRIP_HIGH);
		
		//throw HTTP error if group number is not valid
		if(!is_numeric($group_number)){
			header('HTTP/1.1 500 Invalid number!');
			exit();
		}
		
		//get current starting point of records
		$position = ($group_number * $items_per_group);
		
		
		//Limit our results within a specified range. ù
		$results=executeQuery("SELECT 'PROGETTO' as TYPE, progetti.ID as ID, 'NULL' as ID_COLLEZIONE, progetti.FK_UTENTE as UTENTE, progetti.DATA_CREAZIONE as DATA FROM progetti, utenti_seguono_utenti WHERE utenti_seguono_utenti.FK_UTENTE=$userID AND progetti.FK_UTENTE=utenti_seguono_utenti.FK_UTENTE_SEGUITO UNION SELECT 'COLLEZIONE' AS TYPE, collezioni.ID AS ID, 'NULL' as ID_COLLEZIONE, collezioni.FK_UTENTE as UTENTE, collezioni.DATA_CREAZIONE as DATA FROM collezioni, utenti_seguono_utenti WHERE utenti_seguono_utenti.FK_UTENTE=$userID AND collezioni.FK_UTENTE=utenti_seguono_utenti.FK_UTENTE_SEGUITO UNION SELECT 'LIKE' AS TYPE, utenti_like_progetti.FK_PROGETTO AS ID, 'NULL' as ID_COLLEZIONE, utenti_like_progetti.FK_UTENTE AS UTENTE, utenti_like_progetti.DATA AS DATA_CREAZIONE FROM utenti_like_progetti, utenti_seguono_utenti, utenti WHERE utenti_seguono_utenti.FK_UTENTE=$userID AND utenti_like_progetti.FK_UTENTE=utenti_seguono_utenti.FK_UTENTE_SEGUITO UNION SELECT 'PROGETTO_COLLEZIONE' AS TYPE, collezioni_composte_da_progetti.FK_PROGETTO AS ID, collezioni_composte_da_progetti.FK_COLLEZIONE AS ID_COLLEZIONE, utenti.ID AS UTENTE, collezioni_composte_da_progetti.DATA_AGGIUNTA AS DATA FROM collezioni_composte_da_progetti, utenti, collezioni WHERE collezioni_composte_da_progetti.FK_COLLEZIONE IN (SELECT utenti_seguono_collezioni.FK_COLLEZIONE FROM utenti_seguono_collezioni WHERE utenti_seguono_collezioni.FK_UTENTE=$userID) AND collezioni.FK_UTENTE=utenti.ID AND collezioni.ID=collezioni_composte_da_progetti.FK_COLLEZIONE UNION SELECT 'FOLLOW' AS TYPE, utenti_seguono_utenti.FK_UTENTE_SEGUITO AS ID, 'NULL' AS ID_COLLEZIONE, utenti_seguono_utenti.FK_UTENTE AS UTENTE, utenti_seguono_utenti.DATA AS DATA FROM utenti_seguono_utenti WHERE utenti_seguono_utenti.FK_UTENTE IN (SELECT utenti_seguono_utenti.FK_UTENTE_SEGUITO FROM utenti_seguono_utenti WHERE utenti_seguono_utenti.FK_UTENTE=$userID) UNION SELECT 'FOLLOW_COLLEZIONE' AS TYPE, utenti_seguono_collezioni.FK_COLLEZIONE AS ID, 'NULL' AS ID_COLLEZIONE, utenti_seguono_collezioni.FK_UTENTE AS UTENTE, utenti_seguono_collezioni.DATA AS DATA FROM utenti_seguono_utenti, utenti_seguono_collezioni WHERE utenti_seguono_utenti.FK_UTENTE=$userID AND utenti_seguono_collezioni.FK_UTENTE=utenti_seguono_utenti.FK_UTENTE_SEGUITO ORDER by DATA DESC LIMIT $position, $items_per_group");

		while($result=$results->fetch_assoc()) {
			switch ($result["TYPE"]) {
				case 'PROGETTO': 
					$IDprogetto=$result["ID"];
					$IDutente=$result["UTENTE"];
					$utente=requestDataUser($IDutente);
					$progetto=getProject($IDprogetto);
					$utenteProgetto=$utente;
					$timestamp = strtotime($result["DATA"]);
				?>
					<div class="col s12 l8 offset-l2 m10 offset-m1">
						<div class="hoverable">
						<div class="card-panel white" style="margin-bottom:0; border-radius:0; border-bottom:6px solid #f66b40">
							<div class="valign-wrapper">
				                <div id="avatar" class="valign hide-on-small-only" style="margin:0; margin-right:12px; padding:0; width:40px; height:40px; background-image:url('<?php echo requestPathUser($utente["NOME"], $utente["COGNOME"], $utente["EMAIL"])."/profile.jpg";?>')">   
				                </div>
				                <div class="valign">
				                	<p class="grey-text text-darken-2" style="margin:0">
										<a href="/user/<?=$IDutente?>">
											<?=$utente["NOME"].' '.$utente["COGNOME"]?>
										</a>
										uploaded
										<a href="/project/<?=$IDprogetto?>">
											<?=$progetto["NOME_PROGETTO"] ?>
										</a>
										<br>
										<span style="font-size:13px; color:#777"><?=time_elapsed_string($timestamp);?></span>
			          				</p>
				                </div>
				            </div>
				        </div>

				            <div onmouseover="$('[id=save<?php echo $progetto["ID"] ?>]').show()" onmouseout="$('[id=save<?php echo $progetto["ID"] ?>]').hide()">
		                    <div class="z-depth-1 card" style="background-color:white; margin-top:0; border-radius:0">
		                        <div class="card-image waves-effect waves-block waves-light activator" style="padding:12px 15px; background-image:url('<?php echo "/users/".$utenteProgetto["NOME"]."-".$utenteProgetto["COGNOME"]."-".$utenteProgetto["EMAIL"]."/".$progetto["ID"]; ?>/projectWallpaper.jpg')">
		                        	<a data-projectid="<?php echo $progetto["ID"] ?>" onClick="save(this.id, this.name)" name="<?php echo $progetto["NOME_PROGETTO"] ?>" id="save<?php echo $progetto["ID"] ?>" class="white-text right waves-effect waves-light btn-flat" style="background-color:rgba(33,33,33,0.5); padding:0 15px; font-size:12px; display:none">Save
				                        <i class="material-icons left" style="font-size:17px">add</i>
				                    </a>
		                        </div>
		                        <div class="card-content truncate" style="padding:12px 15px;">
		                            <p class="truncate" style="font-size:20px; margin-bottom:6px;"><a href="/project/<?php echo $progetto["ID"] ?>" style="color:#212121!important"><?php echo $progetto["NOME_PROGETTO"] ?></a></p>
		                            <p class="truncate" style="font-size:13px">by <a href="/user/<?php echo $progetto["FK_UTENTE"]; ?>"><?php echo $utenteProgetto["NOME"]." ".$utenteProgetto["COGNOME"] ?></a></p>
		                        </div>
		                        <div class="card-content truncate" style="padding:12px 15px; border-top:1px solid #ddd">
		                        	<p style="font-size:13px; color:#999; margin-bottom:12px" class="truncate">Submitted in <a href="#"><?php echo $progetto["CATEGORIA_SECONDARIA"]?></a></p>
				                    <div class="col s4 truncate" style="padding:0;">
				                    	<?php 
				                    		$LIKES = executeQuery("select * from utenti_like_progetti where FK_PROGETTO=".$progetto["ID"]);
				                    		if (logged()) $USER_LIKE = executeQuery("select * from utenti_like_progetti where FK_PROGETTO=".$progetto["ID"]." and FK_UTENTE=".$_SESSION["ID"]);
				                    	?>
				                    	<p class="center-align left valign-wrapper" style="color:#777; cursor:pointer" onClick="likeProject(<?php echo $progetto["ID"]; ?>)">
				                    		<i id="projectLikeIcon<?php echo $progetto["ID"]; ?>" class="material-icons noselect valign" style="margin-right:5px; font-size:15px;
				                    		<?php 
				                    			if ((logged()) && ($USER_LIKE->num_rows > 0)) echo "color:#ff6e40";
				                    		?>
				                    		">
				                    		favorite
				                    		</i>
				                    		<span class="valign" style="font-size:13px" id="projectLikes<?php echo $progetto["ID"]; ?>">
				                    			<?php  echo $LIKES->num_rows; ?>
				                    		</span>
				                    	</p>
				                    </div>
				                    <div class="col s4 truncate" style="padding:0;">
				                    	<?php $link= 'http://localhost/project/'.$progetto["ID"]."#disqus_thread" ?>
				                    	<p class="center-align valign-wrapper fit-content no_style" style="cursor:pointer; color:#777; margin:auto" onClick="location.href='<?php echo $link ?>'">
				                    		<i class="material-icons noselect valign" style="margin-right:5px; font-size:15px">comment</i>
				                    		<a class="" href="<?php echo $link; ?>" style="font-size:13px">0</a>
				                    	</p>
				                    </div>
				                    <div class="col s4 truncate" style="padding:0;">
				                    	<p class="center-align right valign-wrapper" style="color:#777;">
				                    		<i class="material-icons noselect valign" style="margin-right:5px; font-size:15px">move_to_inbox
				                    		</i>
				                    		<span id="inCollection<?php echo $progetto["ID"]; ?>" class="valign" style="font-size:13px">
				                    			<?php $COLLECTION = executeQuery("select * from collezioni_composte_da_progetti where FK_PROGETTO=".$progetto["ID"]); echo $COLLECTION->num_rows; ?>
				                    		</span>
				                    	</p>
				                    </div>
				                </div>
		                        <div class="card-reveal">
		                            <span class="card-title"><i class="material-icons right noselect">close</i><?php echo $progetto["NOME_PROGETTO"]?></span>
		                            <p class="valign-wrapper"><i class="valign material-icons noselect" style="margin-right:20px;">insert_drive_file</i>Files: <b style="margin-left:6px"><?php echo $progetto["FILES"]?></b></p>
		                            <p class="valign-wrapper"><i class="valign material-icons noselect" style="margin-right:20px;">file_download</i>Downloads: <b style="margin-left:6px"><?php 
										$QUERY=executeQuery("select NUMERO_DOWNLOAD from progetti where ID=".$progetto["ID"]); 
	                                    $numeroDownload=$QUERY->fetch_assoc();
	                                    echo $numeroDownload["NUMERO_DOWNLOAD"];
		                            ?></b></p>
		                        </div>
		                    </div>
		                </div>

		                </div>
					</div>
				<?php
				break;
				case 'COLLEZIONE': 
					$IDcollezione=$result["ID"];
					$IDutente=$result["UTENTE"];
					$utente=requestDataUser($IDutente);
					$collezione=getCollection($IDcollezione);
					$collection=$collezione;
					$userData=requestDataUser($collezione["FK_UTENTE"]);
					$timestamp = strtotime($result["DATA"]);
				?>
					<div class="col s12 l8 offset-l2 m10 offset-m1">
						<div class="hoverable">
						<div class="card-panel white" style="margin-bottom:0; border-radius:0; border-bottom:6px solid #f66b40">
							<div class="valign-wrapper">
				                <div id="avatar" class="valign hide-on-small-only" style="margin:0; margin-right:12px; padding:0; width:40px; height:40px; background-image:url('<?php echo requestPathUser($utente["NOME"], $utente["COGNOME"], $utente["EMAIL"])."/profile.jpg";?>')">   
				                </div>
				                <div class="valign">
									<p class="grey-text text-darken-2" style="margin:0">
										<a href="/user/<?=$IDutente?>">
											<?=$utente["NOME"].' '.$utente["COGNOME"]?>
										</a>
										created 
										<a href="/collection/<?=$IDcollezione?>">
											<?=$collezione["TITOLO"] ?>
										</a>
										<br>
										<span style="font-size:13px; color:#777"><?=time_elapsed_string($timestamp);?></span>
									</p>
								</div>
							</div>
						</div>


						<div class="" onmouseover="$('[id=follow<?php echo $collection["ID"] ?>]').show()" onmouseout="$('[id=follow<?php echo $collection["ID"] ?>]').hide()">
		                	<div class="z-depth-1 card hoverable" style="background-color:white; margin-top:0">
		                		<div class="waves-effect waves-block waves-light activator" style="padding:0; background-color:#f0f0f0">
		                			<?php 
		                				$collectionID=$collection["ID"];
		                				$projects = executeQuery("select progetti.ID as ID_PROGETTO, progetti.FK_UTENTE as ID_UTENTE, utenti.NOME as NOME_UTENTE, utenti.COGNOME as COGNOME_UTENTE, utenti.EMAIL as EMAIL_UTENTE FROM progetti, collezioni_composte_da_progetti, utenti WHERE collezioni_composte_da_progetti.FK_PROGETTO=progetti.ID and progetti.FK_UTENTE=utenti.ID and collezioni_composte_da_progetti.FK_COLLEZIONE=$collectionID order by collezioni_composte_da_progetti.DATA_AGGIUNTA DESC");
		                				$one = 0;
		                				if ($projects->num_rows > 0):
		                					$j=0;
		                					while ((($project=$projects->fetch_assoc()) && ($j<4)) || ($one<4)) :
		                					$one++;
		                					$j++;
		                					$url= "/users/".$project["NOME_UTENTE"]."-".$project["COGNOME_UTENTE"]."-".$project["EMAIL_UTENTE"]."/".$project["ID_PROGETTO"]."/projectWallpaper.jpg";
		                					$control= "users/".$project["NOME_UTENTE"]."-".$project["COGNOME_UTENTE"]."-".$project["EMAIL_UTENTE"]."/".$project["ID_PROGETTO"]."/projectWallpaper.jpg";
		                					if (!file_exists($control)) $url="";
		                					if ($j==1) :
		                			?>
			                			<div class="activator card-image col s12" style="height:150px; padding: 12px 15px; background-image:url('<?php echo $url; ?>')">
			                				<?php 
			                					if (logged()) :
			                					if (!myCollection($collectionID)) :
			                				?>
			                				<a onClick="followCollection(<?php echo $collectionID; ?>)" id="follow<?php echo $collectionID; ?>" class="white-text right waves-effect waves-light btn-flat" style="background-color:rgba(33,33,33,0.5); padding:0 15px; font-size:12px; display:none">
												<span id="followCollectionText<?php echo $collectionID; ?>">
												<?php
													if (logged()) {
														$userID=$_SESSION["ID"];
														$QUERY=executeQuery("select * from  utenti_seguono_collezioni where FK_COLLEZIONE=$collectionID and FK_UTENTE=$userID");  
	        											if ($QUERY->num_rows == 0) {
	        												echo "Follow";
	        											} else {
	        												echo "Unfollow";
	        											}
													} else {
														echo "Follow";
													}	
												?>
												</span>
						                        <i id="followCollectionIcon<?php echo $collectionID; ?>" class="material-icons left" style="font-size:17px">
						                        <?php
						                        	if (logged()) {
	        											if ($QUERY->num_rows == 0) {
	        												echo "add";
	        											} else {
	        												echo "clear";
	        											}
        											} else {
        												echo "add";
        											}
												?>
						                        </i>
						                    </a>
						                    <?php 
						                    	else: ?>
						                    <a onClick="editCollection(<?php echo $collectionID; ?>)" id="follow<?php echo $collectionID; ?>" class="white-text right waves-effect waves-light btn-flat" style="background-color:rgba(33,33,33,0.5); padding:0 15px; font-size:12px; display:none">
												<span>Edit</span>
						                        <i class="material-icons left" style="font-size:17px">edit</i>
						                    </a>
						                    <?php 
						                		endif;
						                    	else:
						                    ?>
						                		<a onClick="followCollection(<?php echo $collectionID; ?>)" id="follow<?php echo $collectionID; ?>" class="white-text right waves-effect waves-light btn-flat" style="background-color:rgba(33,33,33,0.5); padding:0 15px; font-size:12px; display:none">
													<span id="followCollectionText<?php echo $collectionID; ?>">Follow</span>
							                        <i id="followCollectionIcon<?php echo $collectionID; ?>" class="material-icons left" style="font-size:17px">add</i>
							                    </a>
							                <?php endif; ?>
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
			                    			<?php 
			                					if (logged()) :
			                					if (!myCollection($collectionID)) :
			                				?>
			                				<a onClick="followCollection(<?php echo $collectionID; ?>)" id="follow<?php echo $collectionID; ?>" class="white-text right waves-effect waves-light btn-flat" style="background-color:rgba(33,33,33,0.5); padding:0 15px; font-size:12px; display:none">
												<span id="followCollectionText<?php echo $collectionID; ?>">
												<?php
													if (logged()) {
														$userID=$_SESSION["ID"];
														$QUERY=executeQuery("select * from  utenti_seguono_collezioni where FK_COLLEZIONE=$collectionID and FK_UTENTE=$userID");  
	        											if ($QUERY->num_rows == 0) {
	        												echo "Follow";
	        											} else {
	        												echo "Unfollow";
	        											}
													} else {
														echo "Follow";
													}	
												?>
												</span>
						                        <i id="followCollectionIcon<?php echo $collectionID; ?>" class="material-icons left" style="font-size:17px">
						                        <?php
						                        	if (logged()) {
	        											if ($QUERY->num_rows == 0) {
	        												echo "add";
	        											} else {
	        												echo "clear";
	        											}
        											} else {
        												echo "add";
        											}
												?>
						                        </i>
						                    </a>
						                    <?php 
						                    	else: ?>
						                    <a onClick="editCollection(<?php echo $collectionID; ?>)" id="follow<?php echo $collectionID; ?>" class="white-text right waves-effect waves-light btn-flat" style="background-color:rgba(33,33,33,0.5); padding:0 15px; font-size:12px; display:none">
												<span>Edit</span>
						                        <i class="material-icons left" style="font-size:17px">edit</i>
						                    </a>
						                    <?php 
						                		endif; 
						                    	else:
						                    ?>
						                		<a onClick="followCollection(<?php echo $collectionID; ?>)" id="follow<?php echo $collectionID; ?>" class="white-text right waves-effect waves-light btn-flat" style="background-color:rgba(33,33,33,0.5); padding:0 15px; font-size:12px; display:none">
													<span id="followCollectionText<?php echo $collectionID; ?>">Follow</span>
							                        <i id="followCollectionIcon<?php echo $collectionID; ?>" class="material-icons left" style="font-size:17px">add</i>
							                    </a>
							                <?php endif; ?>
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
			                    				echo "<span id='collectionFollowersNumber$collectionID'>";
			                    				if ($FOLLOW->num_rows>0) echo "• <b>".$FOLLOW->num_rows."</b> FOLLOWERS";
			                    				echo "</span>";
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


						</div>
					</div>
				<?php
				break;
				case 'LIKE': 
					$IDprogetto=$result["ID"];
					$IDutente=$result["UTENTE"];
					$utente=requestDataUser($IDutente);
					$progetto=getProject($IDprogetto);
					$utenteProgetto=requestDataUser($progetto["FK_UTENTE"]);
					$timestamp = strtotime($result["DATA"]);
				?>
					<div class="col s12 l8 offset-l2 m10 offset-m1 ">
						<div class="hoverable">
						<div class="card-panel white" style="margin-bottom:0; border-radius:0; border-bottom:6px solid #f66b40">
							<div class="valign-wrapper">
				                <div id="avatar" class="valign hide-on-small-only" style="margin:0; margin-right:12px; padding:0; width:40px; height:40px; background-image:url('<?php echo requestPathUser($utente["NOME"], $utente["COGNOME"], $utente["EMAIL"])."/profile.jpg";?>')">   
				                </div>
				                <div class="valign">
									<p class="grey-text text-darken-2" style="margin:0">
										<a href="/user/<?=$IDutente?>">
											<?=$utente["NOME"].' '.$utente["COGNOME"]?>
										</a>
										liked
										<a href="/project/<?=$IDprogetto?>">
											<?=$progetto["NOME_PROGETTO"] ?>
										</a>
										<br>
										<span style="font-size:13px; color:#777"><?=time_elapsed_string($timestamp);?></span>
									</p>
								</div>
							</div>
						</div>

						<div onmouseover="$('[id=save<?php echo $progetto["ID"] ?>]').show()" onmouseout="$('[id=save<?php echo $progetto["ID"] ?>]').hide()">
		                    <div class="z-depth-1 card" style="background-color:white; margin-top:0; border-radius:0">
		                        <div class="card-image waves-effect waves-block waves-light activator" style="padding:12px 15px; background-image:url('<?php echo "/users/".$utenteProgetto["NOME"]."-".$utenteProgetto["COGNOME"]."-".$utenteProgetto["EMAIL"]."/".$progetto["ID"]; ?>/projectWallpaper.jpg')">
		                        	<a data-projectid="<?php echo $progetto["ID"] ?>" onClick="save(this.id, this.name)" name="<?php echo $progetto["NOME_PROGETTO"] ?>" id="save<?php echo $progetto["ID"] ?>" class="white-text right waves-effect waves-light btn-flat" style="background-color:rgba(33,33,33,0.5); padding:0 15px; font-size:12px; display:none">Save
				                        <i class="material-icons left" style="font-size:17px">add</i>
				                    </a>
		                        </div>
		                        <div class="card-content truncate" style="padding:12px 15px;">
		                            <p class="truncate" style="font-size:20px; margin-bottom:6px;"><a href="/project/<?php echo $progetto["ID"] ?>" style="color:#212121!important"><?php echo $progetto["NOME_PROGETTO"] ?></a></p>
		                            <p class="truncate" style="font-size:13px">by <a href="/user/<?php echo $progetto["FK_UTENTE"]; ?>"><?php echo $utenteProgetto["NOME"]." ".$utenteProgetto["COGNOME"] ?></a></p>
		                        </div>
		                        <div class="card-content truncate" style="padding:12px 15px; border-top:1px solid #ddd">
		                        	<p style="font-size:13px; color:#999; margin-bottom:12px" class="truncate">Submitted in <a href="#"><?php echo $progetto["CATEGORIA_SECONDARIA"]?></a></p>
				                    <div class="col s4 truncate" style="padding:0;">
				                    	<?php 
				                    		$LIKES = executeQuery("select * from utenti_like_progetti where FK_PROGETTO=".$progetto["ID"]);
				                    		if (logged()) $USER_LIKE = executeQuery("select * from utenti_like_progetti where FK_PROGETTO=".$progetto["ID"]." and FK_UTENTE=".$_SESSION["ID"]);
				                    	?>
				                    	<p class="center-align left valign-wrapper" style="color:#777; cursor:pointer" onClick="likeProject(<?php echo $progetto["ID"]; ?>)">
				                    		<i id="projectLikeIcon<?php echo $progetto["ID"]; ?>" class="material-icons noselect valign" style="margin-right:5px; font-size:15px;
				                    		<?php 
				                    			if ((logged()) && ($USER_LIKE->num_rows > 0)) echo "color:#ff6e40";
				                    		?>
				                    		">
				                    		favorite
				                    		</i>
				                    		<span class="valign" style="font-size:13px" id="projectLikes<?php echo $progetto["ID"]; ?>">
				                    			<?php  echo $LIKES->num_rows; ?>
				                    		</span>
				                    	</p>
				                    </div>
				                    <div class="col s4 truncate" style="padding:0;">
				                    	<?php $link= 'http://localhost/project/'.$progetto["ID"]."#disqus_thread" ?>
				                    	<p class="center-align valign-wrapper fit-content no_style" style="cursor:pointer; color:#777; margin:auto" onClick="location.href='<?php echo $link ?>'">
				                    		<i class="material-icons noselect valign" style="margin-right:5px; font-size:15px">comment</i>
				                    		<a class="" href="<?php echo $link; ?>" style="font-size:13px">0</a>
				                    	</p>
				                    </div>
				                    <div class="col s4 truncate" style="padding:0;">
				                    	<p class="center-align right valign-wrapper" style="color:#777;">
				                    		<i class="material-icons noselect valign" style="margin-right:5px; font-size:15px">move_to_inbox
				                    		</i>
				                    		<span id="inCollection<?php echo $progetto["ID"]; ?>" class="valign" style="font-size:13px">
				                    			<?php $COLLECTION = executeQuery("select * from collezioni_composte_da_progetti where FK_PROGETTO=".$progetto["ID"]); echo $COLLECTION->num_rows; ?>
				                    		</span>
				                    	</p>
				                    </div>
				                </div>
		                        <div class="card-reveal">
		                            <span class="card-title"><i class="material-icons right noselect">close</i><?php echo $progetto["NOME_PROGETTO"]?></span>
		                            <p class="valign-wrapper"><i class="valign material-icons noselect" style="margin-right:20px;">insert_drive_file</i>Files: <b style="margin-left:6px"><?php echo $progetto["FILES"]?></b></p>
		                            <p class="valign-wrapper"><i class="valign material-icons noselect" style="margin-right:20px;">file_download</i>Downloads: <b style="margin-left:6px"><?php 
										$QUERY=executeQuery("select NUMERO_DOWNLOAD from progetti where ID=".$progetto["ID"]); 
	                                    $numeroDownload=$QUERY->fetch_assoc();
	                                    echo $numeroDownload["NUMERO_DOWNLOAD"];
		                            ?></b></p>
		                        </div>
		                    </div>
		                </div>

		                </div>
					</div>
				<?php
				break;
				case 'PROGETTO_COLLEZIONE': 
					$IDprogetto=$result["ID"];
					$IDcollezione=$result["ID_COLLEZIONE"];
					$IDutente=$result["UTENTE"];
					$utente=requestDataUser($IDutente);
					$progetto=getProject($IDprogetto);
					$collezione=getCollection($IDcollezione);
					$collection=$collezione;
					$userData=requestDataUser($collezione["FK_UTENTE"]);
					$timestamp = strtotime($result["DATA"]);
				?>
					<div class="col s12 l8 offset-l2 m10 offset-m1">
						<div class="hoverable">
						<div class="card-panel white" style="margin-bottom:0; border-radius:0; border-bottom:6px solid #f66b40">
							<div class="valign-wrapper">
				                <div id="avatar" class="valign hide-on-small-only" style="margin:0; margin-right:12px; padding:0; width:40px; height:40px; background-image:url('<?php echo requestPathUser($utente["NOME"], $utente["COGNOME"], $utente["EMAIL"])."/profile.jpg";?>')">   
				                </div>
				                <div class="valign">
									<p class="grey-text text-darken-2" style="margin:0">
										<a href="/user/<?=$IDutente?>">
											<?=$utente["NOME"].' '.$utente["COGNOME"]?>
										</a>
										added
										<a href="/project/<?=$IDprogetto?>">
											<?=$progetto["NOME_PROGETTO"] ?>
										</a>
										to
										<a href="/collection/<?=$IDcollezione?>">
											<?=$collezione["TITOLO"] ?>
										</a>
										<br>
										<span style="font-size:13px; color:#777"><?=time_elapsed_string($timestamp);?></span>
									</p>
								</div>
							</div>
						</div>



						<div class="" onmouseover="$('[id=follow<?php echo $collection["ID"] ?>]').show()" onmouseout="$('[id=follow<?php echo $collection["ID"] ?>]').hide()">
		                	<div class="z-depth-1 card hoverable" style="background-color:white; margin-top:0">
		                		<div class="waves-effect waves-block waves-light activator" style="padding:0; background-color:#f0f0f0">
		                			<?php 
		                				$collectionID=$collection["ID"];
		                				$projects = executeQuery("select progetti.ID as ID_PROGETTO, progetti.FK_UTENTE as ID_UTENTE, utenti.NOME as NOME_UTENTE, utenti.COGNOME as COGNOME_UTENTE, utenti.EMAIL as EMAIL_UTENTE FROM progetti, collezioni_composte_da_progetti, utenti WHERE collezioni_composte_da_progetti.FK_PROGETTO=progetti.ID and progetti.FK_UTENTE=utenti.ID and collezioni_composte_da_progetti.FK_COLLEZIONE=$collectionID order by collezioni_composte_da_progetti.DATA_AGGIUNTA DESC");
		                				$one = 0;
		                				if ($projects->num_rows > 0):
		                					$j=0;
		                					while ((($project=$projects->fetch_assoc()) && ($j<4)) || ($one<4)) :
		                					$one++;
		                					$j++;
		                					$url= "/users/".$project["NOME_UTENTE"]."-".$project["COGNOME_UTENTE"]."-".$project["EMAIL_UTENTE"]."/".$project["ID_PROGETTO"]."/projectWallpaper.jpg";
		                					$control= "users/".$project["NOME_UTENTE"]."-".$project["COGNOME_UTENTE"]."-".$project["EMAIL_UTENTE"]."/".$project["ID_PROGETTO"]."/projectWallpaper.jpg";
		                					if (!file_exists($control)) $url="";
		                					if ($j==1) :
		                			?>
			                			<div class="activator card-image col s12" style="height:150px; padding: 12px 15px; background-image:url('<?php echo $url; ?>')">
			                				<?php 
			                					if (logged()) :
			                					if (!myCollection($collectionID)) :
			                				?>
			                				<a onClick="followCollection(<?php echo $collectionID; ?>)" id="follow<?php echo $collectionID; ?>" class="white-text right waves-effect waves-light btn-flat" style="background-color:rgba(33,33,33,0.5); padding:0 15px; font-size:12px; display:none">
												<span id="followCollectionText<?php echo $collectionID; ?>">
												<?php
													if (logged()) {
														$userID=$_SESSION["ID"];
														$QUERY=executeQuery("select * from  utenti_seguono_collezioni where FK_COLLEZIONE=$collectionID and FK_UTENTE=$userID");  
	        											if ($QUERY->num_rows == 0) {
	        												echo "Follow";
	        											} else {
	        												echo "Unfollow";
	        											}
													} else {
														echo "Follow";
													}	
												?>
												</span>
						                        <i id="followCollectionIcon<?php echo $collectionID; ?>" class="material-icons left" style="font-size:17px">
						                        <?php
						                        	if (logged()) {
	        											if ($QUERY->num_rows == 0) {
	        												echo "add";
	        											} else {
	        												echo "clear";
	        											}
        											} else {
        												echo "add";
        											}
												?>
						                        </i>
						                    </a>
						                    <?php 
						                    	else: ?>
						                    <a onClick="editCollection(<?php echo $collectionID; ?>)" id="follow<?php echo $collectionID; ?>" class="white-text right waves-effect waves-light btn-flat" style="background-color:rgba(33,33,33,0.5); padding:0 15px; font-size:12px; display:none">
												<span>Edit</span>
						                        <i class="material-icons left" style="font-size:17px">edit</i>
						                    </a>
						                    <?php 
						                		endif;
						                    	else:
						                    ?>
						                		<a onClick="followCollection(<?php echo $collectionID; ?>)" id="follow<?php echo $collectionID; ?>" class="white-text right waves-effect waves-light btn-flat" style="background-color:rgba(33,33,33,0.5); padding:0 15px; font-size:12px; display:none">
													<span id="followCollectionText<?php echo $collectionID; ?>">Follow</span>
							                        <i id="followCollectionIcon<?php echo $collectionID; ?>" class="material-icons left" style="font-size:17px">add</i>
							                    </a>
							                <?php endif; ?>
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
			                    			<?php 
			                					if (logged()) :
			                					if (!myCollection($collectionID)) :
			                				?>
			                				<a onClick="followCollection(<?php echo $collectionID; ?>)" id="follow<?php echo $collectionID; ?>" class="white-text right waves-effect waves-light btn-flat" style="background-color:rgba(33,33,33,0.5); padding:0 15px; font-size:12px; display:none">
												<span id="followCollectionText<?php echo $collectionID; ?>">
												<?php
													if (logged()) {
														$userID=$_SESSION["ID"];
														$QUERY=executeQuery("select * from  utenti_seguono_collezioni where FK_COLLEZIONE=$collectionID and FK_UTENTE=$userID");  
	        											if ($QUERY->num_rows == 0) {
	        												echo "Follow";
	        											} else {
	        												echo "Unfollow";
	        											}
													} else {
														echo "Follow";
													}	
												?>
												</span>
						                        <i id="followCollectionIcon<?php echo $collectionID; ?>" class="material-icons left" style="font-size:17px">
						                        <?php
						                        	if (logged()) {
	        											if ($QUERY->num_rows == 0) {
	        												echo "add";
	        											} else {
	        												echo "clear";
	        											}
        											} else {
        												echo "add";
        											}
												?>
						                        </i>
						                    </a>
						                    <?php 
						                    	else: ?>
						                    <a onClick="editCollection(<?php echo $collectionID; ?>)" id="follow<?php echo $collectionID; ?>" class="white-text right waves-effect waves-light btn-flat" style="background-color:rgba(33,33,33,0.5); padding:0 15px; font-size:12px; display:none">
												<span>Edit</span>
						                        <i class="material-icons left" style="font-size:17px">edit</i>
						                    </a>
						                    <?php 
						                		endif; 
						                    	else:
						                    ?>
						                		<a onClick="followCollection(<?php echo $collectionID; ?>)" id="follow<?php echo $collectionID; ?>" class="white-text right waves-effect waves-light btn-flat" style="background-color:rgba(33,33,33,0.5); padding:0 15px; font-size:12px; display:none">
													<span id="followCollectionText<?php echo $collectionID; ?>">Follow</span>
							                        <i id="followCollectionIcon<?php echo $collectionID; ?>" class="material-icons left" style="font-size:17px">add</i>
							                    </a>
							                <?php endif; ?>
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
			                    				echo "<span id='collectionFollowersNumber$collectionID'>";
			                    				if ($FOLLOW->num_rows>0) echo "• <b>".$FOLLOW->num_rows."</b> FOLLOWERS";
			                    				echo "</span>";
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



						</div>
					</div>
				<?php
				break;
				case 'FOLLOW': 
					$IDutenteSeguito=$result["ID"];
					$IDutente=$result["UTENTE"];
					$utente=requestDataUser($IDutente);
					$utenteSeguito=requestDataUser($IDutenteSeguito);
					$user=$utenteSeguito;
					$timestamp = strtotime($result["DATA"]);
				?>
					<div class="col s12 l8 offset-l2 m10 offset-m1">
						<div class="hoverable">
						<div class="card-panel white" style="margin-bottom:0; border-radius:0; border-bottom:6px solid #f66b40">
							<div class="valign-wrapper">
				                <div id="avatar" class="valign hide-on-small-only" style="margin:0; margin-right:12px; padding:0; width:40px; height:40px; background-image:url('<?php echo requestPathUser($utente["NOME"], $utente["COGNOME"], $utente["EMAIL"])."/profile.jpg";?>')">   
				                </div>
				                <div class="valign">
									<p class="grey-text text-darken-2" style="margin:0">
										<a href="/user/<?=$IDutente?>">
											<?=$utente["NOME"].' '.$utente["COGNOME"]?>
										</a>
										followed
										<a href="/user/<?=$IDutenteSeguito?>">
											<?=$utenteSeguito["NOME"].' '.$utenteSeguito["COGNOME"]?>
										</a>
										<br>
										<span style="font-size:13px; color:#777"><?=time_elapsed_string($timestamp);?></span>
									</p>
								</div>
							</div>
						</div>

						<div class="">
			            <div class="z-depth-1 card" style="background-color:white; margin-top:0">
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

						</div>
					</div>
				<?php
				break;
				case 'FOLLOW_COLLEZIONE': 
					$IDcollezione=$result["ID"];
					$IDutente=$result["UTENTE"];
					$utente=requestDataUser($IDutente);
					$collezione=getCollection($IDcollezione);
					$collection=$collezione;
					$userData=requestDataUser($collezione["FK_UTENTE"]);
					$timestamp = strtotime($result["DATA"]);
				?>
					<div class="col s12 l8 offset-l2 m10 offset-m1">
						<div class="hoverable">
						<div class="card-panel white" style="margin-bottom:0; border-radius:0; border-bottom:6px solid #f66b40">
							<div class="valign-wrapper">
				                <div id="avatar" class="valign hide-on-small-only" style="margin:0; margin-right:12px; padding:0; width:40px; height:40px; background-image:url('<?php echo requestPathUser($utente["NOME"], $utente["COGNOME"], $utente["EMAIL"])."/profile.jpg";?>')">   
				                </div>
				                <div class="valign">
									<p class="grey-text text-darken-2" style="margin:0">
										<a href="/user/<?=$IDutente?>">
											<?=$utente["NOME"].' '.$utente["COGNOME"]?>
										</a>
										followed
										<a href="/collection/<?=$IDcollezione?>">
											<?=$collezione["TITOLO"] ?>
										</a>
										<br>
										<span style="font-size:13px; color:#777"><?=time_elapsed_string($timestamp);?></span>
									</p>
								</div>
							</div>
						</div>


						<div class="" onmouseover="$('[id=follow<?php echo $collection["ID"] ?>]').show()" onmouseout="$('[id=follow<?php echo $collection["ID"] ?>]').hide()">
		                	<div class="z-depth-1 card hoverable" style="background-color:white; margin-top:0">
		                		<div class="waves-effect waves-block waves-light activator" style="padding:0; background-color:#f0f0f0">
		                			<?php 
		                				$collectionID=$collection["ID"];
		                				$projects = executeQuery("select progetti.ID as ID_PROGETTO, progetti.FK_UTENTE as ID_UTENTE, utenti.NOME as NOME_UTENTE, utenti.COGNOME as COGNOME_UTENTE, utenti.EMAIL as EMAIL_UTENTE FROM progetti, collezioni_composte_da_progetti, utenti WHERE collezioni_composte_da_progetti.FK_PROGETTO=progetti.ID and progetti.FK_UTENTE=utenti.ID and collezioni_composte_da_progetti.FK_COLLEZIONE=$collectionID order by collezioni_composte_da_progetti.DATA_AGGIUNTA DESC");
		                				$one = 0;
		                				if ($projects->num_rows > 0):
		                					$j=0;
		                					while ((($project=$projects->fetch_assoc()) && ($j<4)) || ($one<4)) :
		                					$one++;
		                					$j++;
		                					$url= "/users/".$project["NOME_UTENTE"]."-".$project["COGNOME_UTENTE"]."-".$project["EMAIL_UTENTE"]."/".$project["ID_PROGETTO"]."/projectWallpaper.jpg";
		                					$control= "users/".$project["NOME_UTENTE"]."-".$project["COGNOME_UTENTE"]."-".$project["EMAIL_UTENTE"]."/".$project["ID_PROGETTO"]."/projectWallpaper.jpg";
		                					if (!file_exists($control)) $url="";
		                					if ($j==1) :
		                			?>
			                			<div class="activator card-image col s12" style="height:150px; padding: 12px 15px; background-image:url('<?php echo $url; ?>')">
			                				<?php 
			                					if (logged()) :
			                					if (!myCollection($collectionID)) :
			                				?>
			                				<a onClick="followCollection(<?php echo $collectionID; ?>)" id="follow<?php echo $collectionID; ?>" class="white-text right waves-effect waves-light btn-flat" style="background-color:rgba(33,33,33,0.5); padding:0 15px; font-size:12px; display:none">
												<span id="followCollectionText<?php echo $collectionID; ?>">
												<?php
													if (logged()) {
														$userID=$_SESSION["ID"];
														$QUERY=executeQuery("select * from  utenti_seguono_collezioni where FK_COLLEZIONE=$collectionID and FK_UTENTE=$userID");  
	        											if ($QUERY->num_rows == 0) {
	        												echo "Follow";
	        											} else {
	        												echo "Unfollow";
	        											}
													} else {
														echo "Follow";
													}	
												?>
												</span>
						                        <i id="followCollectionIcon<?php echo $collectionID; ?>" class="material-icons left" style="font-size:17px">
						                        <?php
						                        	if (logged()) {
	        											if ($QUERY->num_rows == 0) {
	        												echo "add";
	        											} else {
	        												echo "clear";
	        											}
        											} else {
        												echo "add";
        											}
												?>
						                        </i>
						                    </a>
						                    <?php 
						                    	else: ?>
						                    <a onClick="editCollection(<?php echo $collectionID; ?>)" id="follow<?php echo $collectionID; ?>" class="white-text right waves-effect waves-light btn-flat" style="background-color:rgba(33,33,33,0.5); padding:0 15px; font-size:12px; display:none">
												<span>Edit</span>
						                        <i class="material-icons left" style="font-size:17px">edit</i>
						                    </a>
						                    <?php 
						                		endif;
						                    	else:
						                    ?>
						                		<a onClick="followCollection(<?php echo $collectionID; ?>)" id="follow<?php echo $collectionID; ?>" class="white-text right waves-effect waves-light btn-flat" style="background-color:rgba(33,33,33,0.5); padding:0 15px; font-size:12px; display:none">
													<span id="followCollectionText<?php echo $collectionID; ?>">Follow</span>
							                        <i id="followCollectionIcon<?php echo $collectionID; ?>" class="material-icons left" style="font-size:17px">add</i>
							                    </a>
							                <?php endif; ?>
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
			                    			<?php 
			                					if (logged()) :
			                					if (!myCollection($collectionID)) :
			                				?>
			                				<a onClick="followCollection(<?php echo $collectionID; ?>)" id="follow<?php echo $collectionID; ?>" class="white-text right waves-effect waves-light btn-flat" style="background-color:rgba(33,33,33,0.5); padding:0 15px; font-size:12px; display:none">
												<span id="followCollectionText<?php echo $collectionID; ?>">
												<?php
													if (logged()) {
														$userID=$_SESSION["ID"];
														$QUERY=executeQuery("select * from  utenti_seguono_collezioni where FK_COLLEZIONE=$collectionID and FK_UTENTE=$userID");  
	        											if ($QUERY->num_rows == 0) {
	        												echo "Follow";
	        											} else {
	        												echo "Unfollow";
	        											}
													} else {
														echo "Follow";
													}	
												?>
												</span>
						                        <i id="followCollectionIcon<?php echo $collectionID; ?>" class="material-icons left" style="font-size:17px">
						                        <?php
						                        	if (logged()) {
	        											if ($QUERY->num_rows == 0) {
	        												echo "add";
	        											} else {
	        												echo "clear";
	        											}
        											} else {
        												echo "add";
        											}
												?>
						                        </i>
						                    </a>
						                    <?php 
						                    	else: ?>
						                    <a onClick="editCollection(<?php echo $collectionID; ?>)" id="follow<?php echo $collectionID; ?>" class="white-text right waves-effect waves-light btn-flat" style="background-color:rgba(33,33,33,0.5); padding:0 15px; font-size:12px; display:none">
												<span>Edit</span>
						                        <i class="material-icons left" style="font-size:17px">edit</i>
						                    </a>
						                    <?php 
						                		endif; 
						                    	else:
						                    ?>
						                		<a onClick="followCollection(<?php echo $collectionID; ?>)" id="follow<?php echo $collectionID; ?>" class="white-text right waves-effect waves-light btn-flat" style="background-color:rgba(33,33,33,0.5); padding:0 15px; font-size:12px; display:none">
													<span id="followCollectionText<?php echo $collectionID; ?>">Follow</span>
							                        <i id="followCollectionIcon<?php echo $collectionID; ?>" class="material-icons left" style="font-size:17px">add</i>
							                    </a>
							                <?php endif; ?>
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
			                    				echo "<span id='collectionFollowersNumber$collectionID'>";
			                    				if ($FOLLOW->num_rows>0) echo "• <b>".$FOLLOW->num_rows."</b> FOLLOWERS";
			                    				echo "</span>";
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


						</div>
					</div>
				<?php
				break;
			}
		}
	}
?>