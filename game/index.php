<?php
	include_once '../inc/html_inc/header/accueil_jouer_contact_direct.php';

	$parties_max = $params->getNbPartiesMax();
	$is_free_party_left = $session->isFreePartyLeft();
?>


	<!-- 1ère image transition -->
	<div class="bgimg-1 w3-display-container w3-opacity-min" id="home">
		<div class="w3-display-middle" style="white-space:nowrap;">
			<h1><span class="w3-center w3-padding-large w3-black w3-xlarge w3-wide w3-animate-opacity">MINI-LOTS</span></h1>
		</div>
	</div>


<?php
	$free_cle_number = $session->getFreeKeyNumber();

	if(!$session->isUserSession()) {
		?>

	<div id="game">
	<!-- Container -> A propos du site -->
		<div class="w3-content w3-container w3-padding-64">
			<h3 class="w3-center" id="game-title">CONNEXION REQUISE</h3>
			
			<p id="game-text">Vous devez vous connecter pour jouer, cliquez sur le bouton suivant pour vous rediriger vers la page de connexion.</p>
			<button class="bords-ronds w3-button w3-black w3-right w3-section" onclick="document.location.href='/account/connexion.php#about'"><i class="fa fa-paper-plane"></i> Connexion</button>
		</div>
	</div>

		<?php
	}
	else {
		if($free_cle_number <= 0) {
			$button_free_text = " Plus de lots";
			$additional_free_text = "<br />Il n'y a actuellement <b>plus de lots gratuits</b> disponibles.<br />Tenez vous au courant sur <b>notre compte Twitter</b> pour savoir quand de nouveaux lots seront à nouveau disponibles.<br />";
		}
		else if(!$is_free_party_left) {
			$button_free_text = " Plus de parties disponibles";
			$additional_free_text = "<br />Vous n'avez le droit qu'à <b><?php echo $parties_max ?> parties gratuites</b> par jour, <b>revenez demain</b> !<br />";
		}
		else {
			$button_free_text = " Partie gratuite";
			$additional_free_text = "";
		}

		?>
		<div id="game">
		<!-- Container -> A propos du site -->
			<div class="w3-content w3-container w3-padding-64">
				<h3 class="w3-center">CHOISISSEZ VOTRE JEU</h3>

				<div class="w3-row-padding" style="margin:0 -16px 8px -16px">
					<div class="w3-half">
						<h4 class="w3-center" style="text-decoration: underline;">Parties gratuites : </h4>

						<p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Bénéficiez de vos <?php echo $parties_max ?> parties gratuites par jour permettant de gagner des <b>clés steam</b> ou encore des <b>comptes spotify</b>, <b>netflix</b>, ...
						<br />Cependant le taux de chance de gagner un lot si vous gagnez une partie est très faible : il est actuellement de <b><?php echo $params->getWinPercentage(); ?>%</b>.<?php echo $additional_free_text ?></p>

						<button class="bords-ronds w3-button w3-black w3-section w3-center" onclick="document.location.href='/game/free_games/#game'"><i class="fa fa-paper-plane"></i><?php echo $button_free_text ?></button>
					</div>

					<div class="w3-half">
						<h4 class="w3-center" style="text-decoration: underline;">Parties payantes : </h4>

						<p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Achetez des parties et soyez sûr à <b>100%</b> de gagner de <b>nombreux lots</b> comme des <b>clés steam</b> d'une valeur moyenne de 10&euro;, ou encore des biens matériels, et cela seulement pour des sommes aux alentours de 1&euro; !</p>

						<button class="bords-ronds w3-button w3-black w3-section w3-center" onclick="document.location.href='/game/pay_games/#game'"><i class="fa fa-paper-plane"></i> Partie payante</button>
					</div>
				</div>
			</div>
		</div>
		<?php
	}
?>


<?php
	include_once '../inc/html_inc/contact.php';
	include_once '../inc/html_inc/footer.php';
?>