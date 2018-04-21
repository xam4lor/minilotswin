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





		<!-- _________________________ OPTIONS ADMINISTRATRICES _________________________ -->

		<div>
		<!-- Container -> A propos du site -->
			<div class="w3-content w3-container w3-padding-64" id="about">
				<h3 class="w3-center">OPTIONS ADMININSTRATEURS DU SITE</h3>

				<?php
				if(isset($_GET['unset_session']) && $_GET['unset_session'] == 1) {
					session_unset();
					?>
				<p class="w3-center answer-text">Les variables de session ont bien été unset.</p>

				<button class="bords-ronds w3-button w3-black w3-right w3-section" onclick="document.location.href='/#about'"><i class="fa fa-paper-plane"></i> Retour à l'accueil</button>
					<?php
				}
				else {
				?>

				<p class="w3-center answer-text">Les liens des pages administratices sont accessibles via les boutons suivants.</p>

				<button class="margin-button bords-ronds w3-button w3-black w3-right w3-section" onclick="document.location.href='addLot.php#about'"><i class="fa fa-paper-plane"></i> Ajouter des lots</button>
				<button class="margin-button bords-ronds w3-button w3-black w3-right w3-section" onclick="document.location.href='bddDisplay.php#about'"><i class="fa fa-paper-plane"></i> Modifier les bases de données</button>
				<button class="margin-button bords-ronds w3-button w3-black w3-right w3-section" onclick="document.location.href='?unset_session=1#about'"><i class="fa fa-paper-plane"></i> Unset les variables de session</button>
				
				<?php
				}
				?>
			</div>
		</div>


		<!-- _________________________ REQUETES SUR LE SITE _________________________ -->

		<!-- 2e image transition -->
		<div class="bgimg-2 w3-display-container w3-opacity-min">
			<div class="w3-display-middle">
				<span class="w3-xxlarge w3-text-white w3-wide"></span>
			</div>
		</div>

		<?php
			$requete_nb = 0;

			$req3 = $bdd->prepare("SELECT id FROM requetes_contact");
			$req3->execute(array());

			while ($donnees3 = $req3->fetch()) {
				$requete_nb++;
			}


			if($requete_nb == 0) {
				?>
				<div>
				<!-- Container -> A propos du site -->
					<div class="w3-content w3-container w3-padding-64" id="messages">
						<h3 class="w3-center">REQUETES SUR LE SITE</h3>
						<center>
							<p class="answer-text">Il n'y a aucune requête sur le site pour le moment.</p>
						</center>

						<button class="bords-ronds w3-button w3-black w3-right w3-section" onclick="document.location.href='/#about'"><i class="fa fa-paper-plane"></i> Retour à l'accueil</button>
					</div>
				</div>
				<?php
			}
			else {
				$req4 = $bdd->prepare("SELECT * FROM requetes_contact ORDER BY id DESC LIMIT 30");
				$req4->execute(array());
				?>
						
				<div>
				<!-- Container -> A propos du site -->
					<div class="w3-content w3-container w3-padding-64" id="messages">
						<h3 class="w3-center">REQUETES SUR LE SITE</h3>
						<p class="answer-text w3-center">Ci-dessous, la liste des 30 dernieres requêtes sur le site. La dernière requête effectuée se situe en premier dans cette liste.<br />Pour voir toutes les requêtes, veuillez consulter la base de donnée.</p><br />

						<table class="w3-content w3-padding-64 custom-tbl">
							<thead> <!-- En-tête du tableau -->
								<tr>
									<th>
										<h4><b>Pseudo</b></h4>
									</th>
									<th>
										<h4><b>Email</b></h4>
									</th>
									<th>
										<h4><b>Message</b></h4>
									</th>
									<th>
										<h4><b>Date de l'envoi</b></h4>
									</th>
									<th>
										<h4><b>Supprimer</b></h4>
									</th>
								</tr>
							</thead>

							<tbody>
								<?php
									while ($donnees4 = $req4->fetch()) {
										?>
										<tr>
											<td>
												<?php echo $donnees4['pseudo'] ?>
											</td>
											<td>
												<?php echo $donnees4['email'] ?>
											</td>
											<td>
												<?php echo $donnees4['message'] ?>
											</td>
											<td>
												<?php echo $donnees4['date_post'] ?>
											</td>
											<td>
												<a href=<?php echo '"bddDisplay.php?bdd_name=requetes_contact&line_id=' . $donnees4['id'] . '&delete=1#about"'; ?>>
													<i class="fa fa-trash-o" aria-hidden="true"></i>
												</a>
											</td>
										</tr>
										<?php
									}
								?>
							</tbody>
						</table>
						<br /><br />
					</div>
				</div>

			<?php
			}
		?>
		<!-- ________________________________________________________________________ -->



<?php
	include_once '../inc/html_inc/contact.php';
	include_once '../inc/html_inc/footer.php';
?>