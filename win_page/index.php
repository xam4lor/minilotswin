<?php
	include_once '../inc/html_inc/header/accueil_retirerlots_contact.php';
	include_once 'lots/LotsGestion.php';

	$lots_gestion = new LotsGestion();
?>

	<div class="bgimg-1 w3-display-container w3-opacity-min" id="home">
		<div class="w3-display-middle" style="white-space:nowrap;">
			<span class="w3-center w3-padding-large w3-black w3-xlarge w3-wide w3-animate-opacity">MINI-LOTS</span>
		</div>
	</div>

	<?php
		if(!$session->isUserSession()) {
	?>
		
		<div id="lots">
		<!-- Container -> A propos du site -->
			<div class="w3-content w3-container w3-padding-64">
				<h3 class="w3-center">CONNEXION REQUISE</h3>
				<p class="reponse-text">Vous devez être connecté pour récupérer vos lots, pour cela cliquez sur le bouton suivant :</p>

				<button class="bords-ronds w3-button w3-black w3-right w3-section" onclick="document.location.href='/account/connexion.php#about'"><i class="fa fa-paper-plane"></i> Connexion</button>
			</div>
		</div>


	<?php
		}
		else {
	?>

		<div id="lots">
			<div class="w3-content w3-container w3-padding-64">
				<h3 class="w3-center">RETIRER VOS LOTS</h3>

				<?php
					if(substr($_SESSION['has_win'], 0, -1) != ($_SERVER['REMOTE_ADDR'] . $encryption_key->getEncryptionKey() . '&lot_key_type=')) {
						//UTILISATEUR NON AUTORISE A ACCEDER AUX LOTS
						unset($_SESSION['has_win']);

						?>
						<div class="error-not-allowed">
							<script type="text/javascript">
								window.location.replace("/");
							</script>

							<p>Vous n'avez pas la permission d'accéder à cette page. Vous allez être redirigé dans quelques secondes sinon cliquez sur le bouton suivant :</p>
							<button class="bords-ronds w3-button w3-black w3-right w3-section" onclick="document.location.href='/#about'"><i class="fa fa-paper-plane"></i> Retour à l'accueil</button>
						</div>

						<?php
					}



					else {
						//UTILISATEUR AUTORISE A ACCEDER AUX LOTS
						if(substr($_SESSION['has_win'], -1) == '0') {
							$canGetLot = $lots_gestion->getRandomFreeLot($bdd, $session, $admin_app_notif);
						}
						else if(substr($_SESSION['has_win'], -1) == '1') {
							$canGetLot = $lots_gestion->getRandomSudokuLot($bdd, $session, $admin_app_notif);

							if($canGetLot) $session->addOneSudokuPartyPlayed();
						}
						else if(substr($_SESSION['has_win'], -1) == '2') {
							$canGetLot = $lots_gestion->getRandomMorpionPayLot($bdd, $session, $admin_app_notif);

							if($canGetLot) $session->addOneMorpionPartyPlayed();
						}


						if($canGetLot) {
							unset($_SESSION['has_win']); // reset du droit d'accès à un lot

							$button = "<button class='bords-ronds w3-button w3-black w3-right w3-section' onclick='document.location.href=\"/account/#about\"'><i class='fa fa-paper-plane'></i> Voir son lot</button>";
							$message_text = "Votre lot a bien été distribué. Cliquez sur le bouton suivant pour le voir :";
						}
						else {
							$button = "<button class='bords-ronds w3-button w3-black w3-right w3-section' onclick='document.location.href=\"/#about\"'><i class='fa fa-paper-plane'></i> Revenir à l'accueil</button>";
							$message_text = "Il n'y a actuellement <b>plus de lots</b>, revenez sur cette page dans quelques jours. Si vous avez acheté une partie payante, <b>votre ticket sera conservé</b> et vous pourrez re-faire une partie lorsqu'il y aura de nouveau des lots. Cliquez sur le bouton suivant pour revenir à l'accueil :";
						}

						?>

						<div id="lots">
							<div class="w3-content w3-container">
								<p class="reponse-text"><?php echo $message_text ?></p>

								<?php echo $button ?>
							</div>
						</div>

						<?php
					}
				?>
			</div>
		</div>
	<?php
		}
	?>


<?php
	include_once '../inc/html_inc/contact.php';
	include_once '../inc/html_inc/footer.php';
?>