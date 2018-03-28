<?php
	include_once '../inc/html_inc/header/accueil_jouer_contact_recul.php';
	

	if(!$session->isUserSession()) {
		header('Location: http://www.minilotswin.890m.com');
	}



	$mdp_text = "";
	$display_page = true;
?>


		<!-- _________________________ LISTE DES LOTS _________________________ -->

		<!-- 1ère image transition -->
		<div class="bgimg-1 w3-display-container w3-opacity-min" id="home">
			<div class="w3-display-middle" style="white-space:nowrap;">
				<h1><span class="w3-center w3-padding-large w3-black w3-xlarge w3-wide w3-animate-opacity">MINI-LOTS</span></h1>
			</div>
		</div>


		<?php
			$requete = $bdd->prepare("SELECT * FROM lots_list");
			$requete->execute(array());
			$lots_nb = 0;

			while ($donnees = $requete->fetch()) {
				$arrayDatas = explode('§§', $donnees['mail_user']);

				for ($i = 0; $i < sizeof($arrayDatas); $i++) {
					if($arrayDatas[$i] == $session->getUserSession()['email']) { //si mail correspond
						$lots_nb++;
					}
				}
			}

			if($lots_nb == 0) {
				?>

				<div>
				<!-- Container -> A propos du site -->
					<div class="w3-content w3-container w3-padding-64" id="about">
						<h3 class="w3-center">MES LOTS</h3>
						<center>
							<p class="answer-text">Vous n'avez gagné aucun lot.</p>
						</center>

						<button class="bords-ronds w3-button w3-black w3-right w3-section" onclick="document.location.href='/#about'"><i class="fa fa-paper-plane"></i>Cliquez ici pour retourner à l'accueil</button>
					</div>
				</div>

				<?php
			}
			else {
				?>
				
				<div>
				<!-- Container -> A propos du site -->
					<div class="w3-content w3-container w3-padding-64" id="about">
						<h3 class="w3-center">MES LOTS</h3>
						<p class="answer-text w3-center">Ci-dessous figure la liste des 30 derniers lots que vous avez gagné.<br />Le dernier lot gagné figure en premier sur la liste suivante.</p><br />

						<table class="w3-content w3-padding-64 custom-tbl">
							<thead> <!-- En-tête du tableau -->
								<tr>
									<th>
										<h4><b>Type</b></h4>
									</th>
									<th>
										<h4><b>Plateforme</b></h4>
									</th>
									<th colspan="2">
										<h4><b>Clé / Compte</b></h4>
									</th>
								</tr>
							</thead>

							<tbody>
								<?php
									$req2 = $bdd->prepare("SELECT * FROM lots_list ORDER BY id DESC");
									$req2->execute(array());
									$lots_nb = 0;
									$cles_number_display = 0; // nombre de clés affichés (au maximum 30)

									while ($donnees = $req2->fetch()) {
										$arrayDatas = explode('§§', $donnees['mail_user']);

										for ($i = 0; $i < sizeof($arrayDatas); $i++) {
											if($arrayDatas[$i] == $session->getUserSession()['email'] && $cles_number_display <= 30) { //si mail correspond
												$cles_number_display++;

												if(strcmp($donnees['type'], "cle")) { //CAS CLE
													?>
												<tr>
													<td>Clé</td>
													<td><?php echo $donnees['plateforme'] ?></td>
													<td colspan="2"><b>Clé :</b> <?php echo $donnees['cle'] ?></td>
												</tr>
													<?php
												}
												else { //CAS COMPTE
													$split_cle = explode('§§', $donnees['cle']);

													?>
												<tr>
													<td>Compte</td>
													<td><?php echo $donnees['plateforme'] ?></td>
													<td><b>Username :</b> <?php echo $split_cle[0] ?></td>
													<td><b>Password :</b> <?php echo $split_cle[1] ?></td>
												</tr>
													<?php
												}
											}
										}
									}

								?>
							</tbody>
						</table><br /><br />

						<button class="bords-ronds w3-button w3-black w3-right w3-section" onclick="document.location.href='/#about'"> Retour à l'accueil</button>
					</div>
				</div>

				<?php
			}
		?>

		<!-- __________________________________________________________________ -->


		<!-- _______________________ MODIFIER LE COMPTE _______________________ -->

		<!-- 2e image transition -->
		<div class="bgimg-2 w3-display-container w3-opacity-min">
			<div class="w3-display-middle">
				<span class="w3-xxlarge w3-text-white w3-wide"></span>
			</div>
		</div>

		<div id="params-pass">
		<!-- Container -> A propos du site -->
			<div class="w3-content w3-container w3-padding-64">
				<h3 class="w3-center">PARAMETRES DU COMPTE</h3>

				<?php
					if(isset($_POST['last_pass']) && isset($_POST['new_pass']) && isset($_POST['new_pass_conf'])) {
						if(htmlspecialchars($_POST['new_pass']) != htmlspecialchars($_POST['new_pass_conf'])) {
							$mdp_text = "Les mots de passe ne correspondent pas.";
						}
						else {
							$mdp_text = $session->changePass(
								$encryption_key->cryptPassword(htmlspecialchars($_POST['last_pass'])),
								$encryption_key->cryptPassword(htmlspecialchars($_POST['new_pass']))
							);
						}
					}
				?>

				<div class="params-pass">
					<p class="answer-text">Votre pseudo : <b><?php echo $session->getUserSession()['username'] ?></b></p>
					<p class="answer-text">Votre adresse-mail : <b><?php echo $session->getUserSession()['email'] ?></b></p>

					<p class="w3-large title_2">Modifiez votre mot de passe :</p>
					<?php
						if($mdp_text != "") {
						?>
							<p class="answer-text"><?php echo $mdp_text ?></p><br />
						<?php
						}
					?>

					<form method="post" action="#params-pass">
						<div class="w3-row-padding" style="margin:0 -8px 8px -8px">
							<input class="w3-input w3-border" type="password" placeholder="Ancien mot de passe" required name="last_pass" />
						</div>

						<div class="w3-row-padding" style="margin:0 -16px 8px -16px">
							<div class="w3-half">
								<input class="w3-input w3-border" type="password" placeholder="Nouveau mot de passe" required name="new_pass" />
							</div>
							<div class="w3-half">
								<input class="w3-input w3-border" type="password" placeholder="Confirmez le nouveau mot de passe" required name="new_pass_conf" />
							</div>
						</div>

						<button class="margin-button bords-ronds w3-button w3-black w3-right w3-section" type="submit">
							<i class="fa fa-paper-plane"></i> Confirmer
						</button>
					</form>
				</div>
			</div>
		</div>

		<!-- __________________________________________________________________ -->



<?php
	include_once '../inc/html_inc/contact.php';
	include_once '../inc/html_inc/footer.php';
?>