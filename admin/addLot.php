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

	/*

	TYPE DE CLE BDD (key_type) :
	   -   0  --->  clés gratuites
	   -   1  --->  clés de sudoku
	   -   2  --->  clés de morpion payantes
	*/

?>
		


		<meta name="robots" content="noindex, nofollow"> <!-- pas de référence à cette page sur les moteurs de recherche -->



		<!-- 1ère image transition -->
		<div class="bgimg-1 w3-display-container w3-opacity-min" id="home">
			<div class="w3-display-middle" style="white-space:nowrap;">
				<h1><span class="w3-center w3-padding-large w3-black w3-xlarge w3-wide w3-animate-opacity">MINI-LOTS</span></h1> 
			</div>
		</div>


		<script>
			function showRSS(str) {
				if (str.length == 0) {
					document.getElementById("rssOutput").innerHTML = "";
					return;
				}
				else {
					document.location.href = "?select=" + str + "#about";
				}
			}
		</script>



		<div>
		<!-- Container -> A propos du site -->
			<div class="w3-content w3-container w3-padding-64" id="about">
				<h3 class="w3-center">AJOUTER UNE CLE</h3>
				<div class="answer-text">
					<?php

						// APRES REMPLISSAGE DES INFOS + SELECTION DU TYPE DE CLE --------------------------
						if(isset($_POST['select_conf'])) {
							$select = htmlspecialchars($_POST['select_conf']);
							$sucess = false;

							if(
								$select == "cle"
								&& isset($_POST['plateforme'])
								&& isset($_POST['cle'])
								&& isset($_POST['useNb'])
								&& isset($_POST['keyType'])

							) { // LES CLES STEAM, ORIGIN, ... --------------------------
								$cle_to_insert = htmlspecialchars($_POST['cle']);


								if($_POST['useNb'] <= -1) { //CLE INFINIE -> définie sur maxint
									$useNb = 2147483647;
								}
								else {
									$useNb = intval($_POST['useNb']);
								}


								$req = $bdd->prepare(
									'INSERT INTO lots_list(
										type,
										plateforme,
										cle,
										key_add_by,
										key_add_date,
										use_left_nb,
										use_initial_nb,
										key_type
									)
									VALUES (
										:type,
										:plateforme,
										:cle,
										:key_add_by,
										NOW(),
										:use_left_nb,
										:use_initial_nb,
										:key_type
									)'
								);
								$req->execute(
									array(
										'type' => $select,
										'plateforme' => htmlspecialchars($_POST['plateforme']),
										'cle' => $cle_to_insert,
										'key_add_by' => $session->getUserSession()['username'],
										'use_left_nb' => $useNb,
										'use_initial_nb' => $useNb,
										'key_type' => htmlspecialchars($_POST['keyType'])
									)
								);
								

								$sucess = true;
							}




							else if(
								$select == "account"
								&& isset($_POST['plateforme'])
								&& isset($_POST['username'])
								&& isset($_POST['password'])
								&& isset($_POST['keyType'])

							) { // LES COMPTES SPOTIFY, ... -----------------
								$cle_to_insert = htmlspecialchars($_POST['username']) . '§§' . htmlspecialchars($_POST['password']);


								$req = $bdd->prepare(
									'INSERT INTO lots_list(
										type,
										plateforme,
										cle,
										key_add_by,
										key_add_date,
										use_left_nb,
										use_initial_nb,
										key_type
									)
									VALUES (
										:type,
										:plateforme,
										:cle,
										:key_add_by,
										NOW(),
										:use_left_nb,
										:use_initial_nb,
										:key_type
									)'
								);
								$req->execute(
									array(
										'type' => $select,
										'plateforme' => htmlspecialchars($_POST['plateforme']),
										'cle' => $cle_to_insert,
										'key_add_by' => $session->getUserSession()['username'],
										'use_left_nb' => 1,
										'use_initial_nb' => 1,
										'key_type' => htmlspecialchars($_POST['keyType'])
									)
								);

								$sucess = true;
							}





							else if(
								$select == "materiel"
								&& isset($_POST['marque'])
								&& isset($_POST['description'])
								&& isset($_POST['keyType'])

							) { // LES LOTS MATERIELS : TShirts, ... -----------------
								$req = $bdd->prepare(
									'INSERT INTO lots_list(
										type,
										plateforme,
										cle,
										key_add_by,
										key_add_date,
										use_left_nb,
										use_initial_nb,
										key_type
									)
									VALUES (
										:type,
										:plateforme,
										:cle,
										:key_add_by,
										NOW(),
										:use_left_nb,
										:use_initial_nb,
										:key_type
									)'
								);
								$req->execute(
									array(
										'type' => $select,
										'plateforme' => htmlspecialchars($_POST['marque']),
										'cle' => htmlspecialchars($_POST['description']),
										'key_add_by' => $session->getUserSession()['username'],
										'use_left_nb' => 1,
										'use_initial_nb' => 1,
										'key_type' => htmlspecialchars($_POST['keyType'])
									)
								);

								$sucess = true;
							}



							else {
								?>
								<p>Vous n'avez pas rempli tous les champs.</p>
								<?php
							}




							if($sucess) { //MESSAGE CONFIRMATION : TOUT S'EST BIEN PASSE
								?>
								<p>Vous avez bien ajouté ce lot.</p>
								<?php
							}
						}





						//APRES LA SELECTION DU TYPE DE CLE -----------------------------------------------
						else if(isset($_GET['select'])) {
							$select = htmlspecialchars($_GET['select']);


							if($select == "cle") { //LES CLES STEAM, ORIGIN, ... --------------------------
								?>
								<p class="w3-center">
									<em>Vous avez sélectionné une clé comme type de lot.
									<br />Pour entrer une clé utilisable <b>indéfiniment</b>, entrez la valeur <b>'-1'</b> dans le nombre maximum d'utilisations de cette clé.
									<br />Pour la valeurs du champ <b>'type de clé'</b> : pour une clé <b>gratuite</b>, entrez <b>0</b>, pour une clé <b>de Sudoku</b>, entrez <b>1</b>, pour une clé <b>de Morpion payante</b>, entrez <b>2</b>.</em>
								</p>

								<form method="post" action="#about">
									<div class="w3-row-padding" style="margin:0 -16px 8px -16px">
										<div class="w3-half">
											<input class="w3-input w3-border" type="text" placeholder="Plateforme (steam, origin, ...)" required name="plateforme" />
										</div>
										<div class="w3-half">
											<input class="w3-input w3-border" type="text" placeholder="Nombre d'utilisations maximum (-1 pour infini)" required name="useNb" />
										</div>
									</div>

									<div class="w3-row-padding" style="margin:0 -16px 8px -16px">
										<div class="w3-half">
											<input class="w3-input w3-border" type="text" placeholder="Clé" required name="cle" />
										</div>
										<div class="w3-half">
											<input class="w3-input w3-border" type="text" placeholder="Id du type de clé (cf paragraphe ci-dessus)" required name="keyType" />
										</div>
									</div>

									<button class="bords-ronds w3-button w3-black w3-right w3-section" type="submit"><i class="fa fa-paper-plane"></i> AJOUTER LA CLE</button>

									<input style="display: none;" type="text" name="select_conf" value=<?php echo '"' . $select . '"'; ?> />
								</form>
								<?php
							}



							else if($select == "account") { //LES COMPTES SPOTIFY, ... --------------------
								?>
								<p class="w3-center">
									<em>Vous avez sélectionné un compte comme type de lot.
									<br />Pour la valeurs du champ <b>'type de clé'</b> : pour une clé <b>gratuite</b>, entrez <b>0</b>, pour une clé <b>de Sudoku payante</b>, entrez <b>1</b>, pour une clé <b>de Morpion payante</b>, entrez <b>2</b>.</em>
								</p>

								<form method="post" action="#about">
									<div class="w3-row-padding" style="margin:0 -16px 8px -16px">
										<input class="w3-input w3-border" type="text" placeholder="Type de compte (minecraft, spotify, ...)" required name="plateforme" />
									</div>

									<div class="w3-row-padding" style="margin:0 -16px 8px -16px">
										<div class="w3-half">
											<input class="w3-input w3-border" type="text" placeholder="Nom d'utilisateur du compte (username)" required name="username" />
										</div>
										<div class="w3-half">
											<input class="w3-input w3-border" type="text" placeholder="Mot de passe du compte (password)" required name="password" />
										</div>
									</div>

									<div class="w3-row-padding" style="margin:0 -16px 8px -16px">
										<input class="w3-input w3-border" type="text" placeholder="Id du type de clé (cf paragraphe ci-dessus)" required name="keyType" />
									</div>

									<button class="bords-ronds w3-button w3-black w3-right w3-section" type="submit"><i class="fa fa-paper-plane"></i> AJOUTER LA CLE</button>
									
									<input style="display: none;" type="text" name="select_conf" value=<?php echo '"' . $select . '"'; ?> />
								</form>
								<?php
							}



							else if($select == "materiel") { //LES LOTS MATERIELS : TShirts, ... --------------------
								?>
								<p class="w3-center">
									<em>Vous avez sélectionné un lot matériel comme type de lot.
									<br />Pour la valeurs du champ <b>'type du lot'</b> : pour un lot <b>gratuit</b>, entrez <b>0</b>, pour un lot <b>de Sudoku payant</b>, entrez <b>1</b>, pour un lot <b>de Morpion payant</b>, entrez <b>2</b>.</em>
								</p>

								<form method="post" action="#about">
									<div class="w3-row-padding" style="margin:0 -16px 8px -16px">
										<input class="w3-input w3-border" type="text" placeholder="Marque de l'objet" required name="marque" />
									</div>

									<div class="w3-row-padding" style="margin:0 -16px 8px -16px">
										<input class="w3-input w3-border" type="text" placeholder="Description du produit" required name="description" />
									</div>

									<div class="w3-row-padding" style="margin:0 -16px 8px -16px">
										<input class="w3-input w3-border" type="text" placeholder="Id du type du lot (cf paragraphe ci-dessus)" required name="keyType" />
									</div>

									<button class="bords-ronds w3-button w3-black w3-right w3-section" type="submit"><i class="fa fa-paper-plane"></i> AJOUTER LE LOT</button>
									
									<input style="display: none;" type="text" name="select_conf" value=<?php echo '"' . $select . '"'; ?> />
								</form>
								<?php
							}



							else {
								?>
								<p>Erreur dans le type de lot choisi.</p>
								<?php
							}
						}




						else { //SELECTION DU TYPE DE CLE -------------------------------------
							?>
							<p class="w3-center"><em>Vous êtes bien un administrateur du site, veuillez sélectionner le type de lot que vous voulez ajouter.</em></p>

							<center>
								<form>
									<select class="bords-ronds w3-button w3-black w3-section" onchange="showRSS(this.value)">
										<option value="">Type de lots</option>
										<option value="cle">Clé</option>
										<option value="account">Compte</option>
										<option value="materiel">Lot matériel</option>
									</select>
								</form>
							</center>
							<?php
						}
					?>
				</div>
			</div>
		</div>




<?php
	include_once '../inc/html_inc/contact.php';
	include_once '../inc/html_inc/footer.php';
?>