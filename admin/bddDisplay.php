<?php
	include_once '../inc/html_inc/header/accueil_jouer_contact_recul.php';
	

	if(!$session->isUserSession() || $session->getUserSession()['admin'] != 1) {
		?>
		<meta http-equiv="Refresh" content="0; URL=/#about">

		<div class="w3-content w3-container w3-padding-64">
			<h3 class="w3-center" id="game-title">REDIRECTION</h3>
			
			<p id="game-text">Vous ne pouvez pas accéder à cette page.</p>
			<button class="bords-ronds w3-button w3-black w3-right w3-section" onclick="document.location.href='/#about'"><i class="fa fa-paper-plane"></i> Retour à l'accueil</button>
		</div>
		<?php
		exit();
	}
?>


	<!-- 1ère image transition -->
	<div class="bgimg-1 w3-display-container w3-opacity-min" id="home">
		<div class="w3-display-middle" style="white-space:nowrap;">
			<h1><span class="w3-center w3-padding-large w3-black w3-xlarge w3-wide w3-animate-opacity">MINI-LOTS</span></h1>
		</div>
	</div>



	<div id="about">
	<!-- Container -> A propos du site -->
		<div class="w3-center w3-container w3-padding-64">
			<?php
				$dispSelect = true;
				$gameTextMsg = "Sélectionnez la base de donnée à afficher, modifier ou supprimer.";


				// ----------------------------------- AJOUT D'UNE LIGNE -> BDD ------------------------------------
				if(isset($_POST['bdd_name']) && isset($_POST['add_new_line']) && intval($_POST['add_new_line']) == 2) {
					try {
						$bddDatas = array();

						foreach ($_POST as $key => $value) {
							$postCorrectVal = explode("§§§", $key);

							if($postCorrectVal[0] == "bddParam") {
								$bddDatas[$postCorrectVal[1]] = $value;
							}
						}


						// REQUETE SQL CUSTOM PREPARE
						$first = true;
						$reqSQL = 'INSERT INTO ' . htmlspecialchars($_POST['bdd_name']) . ' (';

						foreach ($bddDatas as $key => $value) {
							if($key != "id") {
								if(!$first) {
									$reqSQL .= ', ' . $key;
								}
								else {
									$first = false;
									$reqSQL .= $key;
								}
							}
						}
						$reqSQL .= ') VALUES (';

						$first = true;
						foreach ($bddDatas as $key => $value) {
							if($key != "id") {
								if(!$first) {
									$reqSQL .= ', :' . $key;
								}
								else {
									$first = false;
									$reqSQL .= ':' . $key;
								}
							}
						}

						$reqSQL .= ')';
						$req2 = $bdd->prepare($reqSQL);

						// REQUETE SQL CUSTOM EXECUTE
						$arrayExe = array();
						foreach ($bddDatas as $key => $value) {
							if($key != "id") {
								$arrayExe[$key] = $value;
							}
						}
						$req2->execute($arrayExe);
					?>

					<h3 class="w3-center" id="game-title">CONFIRMATION DE L'AJOUT</h3>
					<p id="answer-text">La ligne a bien été ajoutée à la base de donnée <?php echo htmlspecialchars($_GET['bdd_name']) ?>.</p><br />
					<button class="margin-button bords-ronds w3-button w3-black w3-section" onclick="document.location.href=<?php echo '\'bddDisplay.php?bdd_name=' . htmlspecialchars($_POST['bdd_name']) . '#about\'' ?>"><i class="fa fa-paper-plane"></i> Retour au choix de la ligne à éditer</button>

						<?php
						$dispSelect = false;
					}
					catch (Exception $e) {
						$dispSelect = true;
						$gameTextMsg = "Erreur inconnue :<br />" . $e;
					}
				}
				// -------------------------------------------------------------------------------------------------






				// ---------------------------------- AJOUT D'UNE LIGNE -> INPUT -----------------------------------
				else if(isset($_GET['bdd_name']) && isset($_GET['add_new_line']) && $_GET['add_new_line'] == 1) {
					$bddExist = false;
					$bddDatas = array();

					try {
						$req = $bdd->prepare('SELECT * FROM ' . htmlspecialchars($_GET['bdd_name']));
						$req->execute(array());

						while($donnees = $req->fetch()) {
							$bddExist = true;
							array_push($bddDatas, $donnees);
						}

						if($bddExist) {
							?>
						<h3 class="w3-center" id="game-title">AJOUTEZ LES DONNEES</h3>
						<p id="answer-text">Modifiez les champs suivants de la base de donnée <?php echo htmlspecialchars($_GET['bdd_name']) ?>.<br />Puis appuyez sur le bouton de confirmation.  N.B. : Les id ne sont pas modifiables.</p><br />

						<form method="post" action="#about" style="display: inline-grid;">
							<div class="input-align">
								<?php
									foreach ($bddDatas[0] as $key => $value) {
										if(!is_numeric($key)) {
											if($key != "id") {
												?>
													<label class="label-align"> <?php echo $key ?> :
														<input class="bords-ronds w3-center" type="text" name=<?php echo '"bddParam§§§' . $key . '"' ?> />
													</label>
												<?php
											}
										}
									}
								?>
								<input class="bords-ronds w3-center" type="text" name="bdd_name" value=<?php echo '"' . htmlspecialchars($_GET['bdd_name']) . '"' ?> style="display: none;" />
								<input class="bords-ronds w3-center" type="text" name="add_new_line" value="2" style="display: none;" />
							</div>
							<br />
							<button class="margin-button bords-ronds w3-button w3-black w3-right w3-section" type="submit" style="width: -moz-fit-content;">
								<i class="fa fa-paper-plane"></i> Confirmer
							</button>
						</form>
						<br />
						<button class="margin-button bords-ronds w3-button w3-black w3-section" onclick="document.location.href=<?php echo '\'bddDisplay.php?bdd_name=' . htmlspecialchars($_GET['bdd_name']) . '#about\'' ?>"><i class="fa fa-paper-plane"></i> Retour au choix de la ligne à éditer</button>
							<?php
							$dispSelect = false;
						}
						else {
							$dispSelect = true;
							$gameTextMsg = "Le nom de la base de donnée n'est pas correct ou cette dernière est vide.";
						}
					}
					catch (Exception $e) {
						$dispSelect = true;
						$gameTextMsg = "Le nom de la base de donnée n'est pas correct ou cette dernière est vide.";
					}
				}
				// -------------------------------------------------------------------------------------------------






				// ------------------------------- SUPRESSION DE LA LIGNE DE LA BDD --------------------------------
				else if(isset($_GET['bdd_name']) && isset($_GET['line_id']) && isset($_GET['delete']) && htmlspecialchars($_GET['delete']) == 1) {
					try {
						$req3 = $bdd->prepare('DELETE FROM ' . htmlspecialchars($_GET['bdd_name']) . ' WHERE id=:id');
						$req3->execute(array('id' => htmlspecialchars($_GET['line_id'])));

						?>

					<h3 class="w3-center" id="game-title">CONFIRMATION DE LA SUPPRESSION</h3>
					<p id="answer-text">La ligne à l'id <?php echo htmlspecialchars($_GET['line_id']) ?> de la base de donnée <?php echo htmlspecialchars($_GET['bdd_name']) ?> a bien été supprimée.<br /></p><br />
					<button class="margin-button bords-ronds w3-button w3-black w3-section" onclick="document.location.href=<?php echo '\'bddDisplay.php?bdd_name=' . htmlspecialchars($_GET['bdd_name']) . '#about\'' ?>"><i class="fa fa-paper-plane"></i> Retour au choix de la ligne à éditer</button>

						<?php
						$dispSelect = false;
					}
					catch (Exception $e) {
						$dispSelect = true;
						$gameTextMsg = "Erreur inconnue : " . $e;
					}
				}
				// -------------------------------------------------------------------------------------------------






				// --------------------------- MODIFICATION DE LA LIGNE DE LA BDD -> BDD ---------------------------
				else if(isset($_POST['bdd_name']) && isset($_POST['line_id'])) {
					try {
						$bddDatas = array();

						foreach ($_POST as $key => $value) {
							$postCorrectVal = explode("§§§", $key);

							if($postCorrectVal[0] == "bddParam") {
								$bddDatas[$postCorrectVal[1]] = $value;
							}
						}


						//REQUETE SQL CUSTOM PREPARE
						$first = true;
						$reqSQL = 'UPDATE ' . htmlspecialchars($_POST['bdd_name']) . ' SET ';
						foreach ($bddDatas as $key => $value) {
							if($key != "id") {
								if(!$first) {
									$reqSQL .= ', ' . $key . '=:' . $key;
								}
								else {
									$first = false;
									$reqSQL .= $key . '=:' . $key;
								}
							}
						}
						$reqSQL .= ' WHERE id=:id';
						$req2 = $bdd->prepare($reqSQL);

						//REQUETE SQL CUSTOM EXECUTE
						$arrayExe = array();
						foreach ($bddDatas as $key => $value) {
							if($key != "id") {
								$arrayExe[$key] = $value;
							}
						}
						$arrayExe['id'] = htmlspecialchars($_POST['line_id']);
						$req2->execute($arrayExe);
						?>

					<h3 class="w3-center" id="game-title">CONFIRMATION DE LA MODIFICATION</h3>
					<p id="answer-text">La ligne à l'id <?php echo htmlspecialchars($_GET['line_id']) ?> de la base de donnée <?php echo htmlspecialchars($_GET['bdd_name']) ?> a bien été modifiée.<br />Si la valeur n'est pas modifiée, le type de la nouvelle valeur entrée est incorrecte.</p><br />
					<button class="margin-button bords-ronds w3-button w3-black w3-section" onclick="document.location.href=<?php echo '\'bddDisplay.php?bdd_name=' . htmlspecialchars($_POST['bdd_name']) . '#about\'' ?>"><i class="fa fa-paper-plane"></i> Retour au choix de la ligne à éditer</button>

						<?php
						$dispSelect = false;
					}
					catch (Exception $e) {
						$dispSelect = true;
						$gameTextMsg = "Erreur inconnue : " . $e;
					}
				}
				// -------------------------------------------------------------------------------------------------






				// -------------------------- MODIFICATION DE LA LIGNE DE LA BDD -> INPUT --------------------------
				else if(isset($_GET['bdd_name']) && isset($_GET['line_id'])) {
					$bddExist = false;
					$bddDatas = array();

					try {
						$req = $bdd->prepare('SELECT * FROM ' . htmlspecialchars($_GET['bdd_name']) . ' WHERE id=:id');
						$req->execute(array('id' => htmlspecialchars($_GET['line_id'])));

						while($donnees = $req->fetch()) {
							$bddExist = true;
							array_push($bddDatas, $donnees);
						}

						if($bddExist) {
							?>
						<h3 class="w3-center" id="game-title">MODIFIEZ LES DONNEES</h3>
						<p id="answer-text">Modifiez les champs de la ligne à l'id <?php echo htmlspecialchars($_GET['line_id']) ?> de la base de donnée <?php echo htmlspecialchars($_GET['bdd_name']) ?> triée par ordre d'ajout décroissant.<br />Puis appuyez sur le bouton de confirmation.  N.B. : Les id ne sont pas modifiables.</p><br />

						<form method="post" action="#about" style="display: inline-grid;">
							<div class="input-align">
								<?php
								foreach ($bddDatas[0] as $key => $value) {
									if(!is_numeric($key)) {
										if($key != "id") {
											?>
												<label class="label-align"> <?php echo $key ?> :
													<input class="bords-ronds w3-center" type="text" name=<?php echo '"bddParam§§§' . $key . '"' ?> value=<?php echo '"' . $value . ' "' ?> />
												</label>
											<?php
										}
										else {
											?>
												<label class="label-align"> <?php echo $key ?> :
													<input class="bords-ronds w3-center" type="text" name=<?php echo '"bddParam§§§' . $key . '"' ?> value=<?php echo '"' . $value . ' "' ?> style="background-color: #c6c6c6;" readonly />
												</label>
											<?php
										}
									}
								}
								?>
								<input class="bords-ronds w3-center" type="text" name="bdd_name" value=<?php echo '"' . htmlspecialchars($_GET['bdd_name']) . '"' ?> style="display: none;" />
								<input class="bords-ronds w3-center" type="text" name="line_id" value=<?php echo '"' . htmlspecialchars($_GET['line_id']) . '"' ?> style="display: none;" />
							</div>
							<br />
							<button class="margin-button bords-ronds w3-button w3-black w3-right w3-section" type="submit" style="width: -moz-fit-content;">
								<i class="fa fa-paper-plane"></i> Confirmer
							</button>
						</form>
						<br />
						<button class="margin-button bords-ronds w3-button w3-black w3-section" onclick="document.location.href=<?php echo '\'bddDisplay.php?bdd_name=' . htmlspecialchars($_GET['bdd_name']) . '#about\'' ?>"><i class="fa fa-paper-plane"></i> Retour au choix de la ligne à éditer</button>
							<?php
							$dispSelect = false;
						}
						else {
							$dispSelect = true;
							$gameTextMsg = "Le nom de la base de donnée ou l'id de la ligne a modifier n'est pas correct ou cette dernière est vide.";
						}
					}
					catch (Exception $e) {
						$dispSelect = true;
						$gameTextMsg = "Le nom de la base de donnée ou l'id de la ligne a modifier n'est pas correct ou cette dernière est vide.";
					}
				}
				// -------------------------------------------------------------------------------------------------






				// ----------------------------------- AFFICHAGE DE TOUTE LA BDD -----------------------------------
				else if(isset($_GET['bdd_name'])) {
					$bddExist = false;
					$bddDatas = array();

					try {
						$req = $bdd->prepare('SELECT * FROM ' . htmlspecialchars($_GET['bdd_name']) . ' ORDER BY id DESC');
						$req->execute(array());

						while($donnees = $req->fetch()) {
							$bddExist = true;
							array_push($bddDatas, $donnees);
						}

						if($bddExist) {
							?>
						<h3 class="w3-center" id="game-title">BASE DE DONNEE : <?php echo htmlspecialchars($_GET['bdd_name']) ?></h3>
						<p id="answer-text">Ci-dessous, la base de donnée appelée <?php echo htmlspecialchars($_GET['bdd_name']) ?> triée par ordre d'ajout décroissant.<br />Cliquez sur le <i>crayon</i> à droite des lignes pour les <i>modifier</i>, sur la <i>poubelle</i> pour les <i>supprimer</i> et sur la <i>croix</i> pour <i>ajouter une ligne</i>.</p><br />

						<div class="table-class-admin">
							<table class="w3-content w3-padding-64 custom-tbl">
								<thead class="w3-animate-top w3-card-2"> <!-- En-tête du tableau -->
									<tr>
								<?php
									foreach ($bddDatas[0] as $key => $value) {
										if(!is_numeric($key)) {
											?>
												<th>
													<h4><b><?php echo $key ?></b></h4>
												</th>		
											<?php
										}
									}
								?>
										<th>
											<h4>
												<b>Outils</b>
												<a href=<?php echo '"?bdd_name=' . htmlspecialchars($_GET['bdd_name']) . '&add_new_line=1#about"'; ?>>
													<i class="fa fa-plus-square-o" aria-hidden="true" style="padding-left: 5%;"></i>
												</a>
											</h4>
										</th>
									</tr>
								</thead>
								<tbody>
									<?php
										foreach ($bddDatas as $firstTblKey => $secondTbl) {
											?>
										<tr>
											<?php
												foreach ($secondTbl as $key => $value) {
													if(!is_numeric($key)) {
														?>
													<td>
														<?php echo $value ?>
													</td>
														<?php
													}
												}
												?>
											<td>
												<a href=<?php echo '"?bdd_name=' . htmlspecialchars($_GET['bdd_name']) . '&line_id=' . $secondTbl['id'] . '#about"'; ?> style="padding-right: 10%;">
													<i class="fa fa-pencil" aria-hidden="true"></i>
												</a>
												<a href=<?php echo '"?bdd_name=' . htmlspecialchars($_GET['bdd_name']) . '&line_id=' . $secondTbl['id'] . '&delete=1#about"'; ?>>
													<i class="fa fa-trash-o" aria-hidden="true"></i>
												</a>
											</td>
										</tr>
											<?php
										}
									?>
									</tr>
								</tbody>
							</table>
						</div>

						<button class="margin-button bords-ronds w3-button w3-black w3-section" onclick="document.location.href='bddDisplay.php#about'"><i class="fa fa-paper-plane"></i> Retour au choix de la base de donnée</button>
							<?php
							$dispSelect = false;
						}
						else {
							$dispSelect = true;
							$gameTextMsg = "Le nom de la base de donnée n'est pas correct ou cette dernière est vide.";
						}
					}
					catch (Exception $e) {
						$dispSelect = true;
						$gameTextMsg = "Le nom de la base de donnée n'est pas correct ou cette dernière est vide.";
					}
				}
				// -------------------------------------------------------------------------------------------------






				// -------------------------------------- SELECTION DE LA BDD --------------------------------------
				if($dispSelect) {
					?>

					<script>
						function selectBdd(str) {
							if (str.length == 0) {
								return;
							}
							else {
								document.location.href = "?bdd_name=" + str + "#about";
							}
						}
					</script>

					<h3 class="w3-center" id="game-title">SELECTIONNEZ LA BASE DE DONNEE</h3>
					<p id="game-text"><?php echo $gameTextMsg ?></p>

					<select class="bords-ronds w3-button w3-black w3-section" onchange="selectBdd(this.value)">
						<option value="">Selectionnez la base de donnée</option>
						<option value="account">Comptes (account)</option>
						<option value="lots_list">Liste des lots (lots_list)</option>
						<option value="requetes_contact">Requêtes du site (requetes_contact)</option>
						<option value="connectes">Utilisateurs connectés (connectes)</option>
						<option value="payments">Liste des paiements (payments)</option>
					</select>

					<br />
					<button class="margin-button bords-ronds w3-button w3-black w3-section" onclick="document.location.href='/admin/#about'"><i class="fa fa-paper-plane"></i> Retour à l'accueil administrateur</button>

					<?php
				}
				// -------------------------------------------------------------------------------------------------
			?>
		</div>
	</div>



<?php
	include_once '../inc/html_inc/contact.php';
	include_once '../inc/html_inc/footer.php';
?>
