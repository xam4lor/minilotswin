 <?php
	include_once realpath(dirname(__FILE__) . "/../../inc/html_inc/header/accueil_jouer_contact_direct.php");

	$parties_max = $params->getNbPartiesMax();
	$is_party_left = $session->isFreePartyLeft();
?>


	<!-- 1ère image transition -->
	<div class="bgimg-1 w3-display-container w3-opacity-min" id="home">
		<div class="w3-display-middle" style="white-space:nowrap;">
			<h1><span class="w3-center w3-padding-large w3-black w3-xlarge w3-wide w3-animate-opacity">MINI-LOTS</span></h1>
		</div>
	</div>


<?php
	$cle_number = $session->getFreeKeyNumber();

	if(!$session->isUserSession()) {
		?>

	<div id="game">
	<!-- Container -> A propos du site -->
		<div class="w3-content w3-container w3-padding-64">
			<h3 class="w3-center" id="game-title">CONNEXION REQUISE</h3>
			
			<p id="game-text">Vous devez vous connecter pour jouer, cliquez sur le bouton suivant pour vous rediriger vers la page de connexion.</p>
			<button class="bords-ronds w3-button w3-black w3-right w3-section" onclick="document.location.href='../../account/connexion.php#about'"><i class="fa fa-paper-plane"></i> Connexion</button>
		</div>
	</div>

		<?php
	}
	else if($cle_number <= 0) {
		?>

	<div id="game">
	<!-- Container -> A propos du site -->
		<div class="w3-content w3-container w3-padding-64">
			<h3 class="w3-center" id="game-title">PLUS DE LOTS</h3>
			
			<p id="game-text">Il n'y a actuellement <b>plus de lots gratuits disponibles</b>, nous en rajouterons dès que possible.</p>
			<button class="bords-ronds w3-button w3-black w3-right w3-section" onclick="document.location.href='../../../'"> Retour à l'accueil</button>
		</div>
	</div>	

		<?php
	}
	else if(!$is_party_left) {
		?>

	<div id="game">
	<!-- Container -> A propos du site -->
		<div class="w3-content w3-container w3-padding-64">
			<h3 class="w3-center" id="game-title">TROP DE PARTIES</h3>
			
			<p id="game-text">Vous n'avez le droit qu'à <b><?php echo $parties_max ?> parties gratuites</b> par jour, revenez demain !</p>
			<button class="bords-ronds w3-button w3-black w3-right w3-section" onclick="document.location.href='../../../'"> Retour à l'accueil</button>
		</div>
	</div>	

		<?php
	}
	else {
?>


	<div id="game">
	<!-- Container -> A propos du site -->
		<div class="w3-content w3-container w3-padding-64">
			<h3 class="w3-center" id="game-title"></h3>

			<p id="game-text"></p>
		</div>
	</div>




	<script type="text/javascript">
		var datas = "null";


		function process() {
			var xhr = new XMLHttpRequest();

			xhr.open('GET', 'GameLogic.php?' + 'datas=' + datas);

			xhr.onreadystatechange = function() {
				if (xhr.readyState == 4 && xhr.status == 200) {
					document.getElementById('game-text').innerHTML = xhr.responseText;

					eval(document.getElementById('game-javascript').innerHTML);
					
					document.getElementById('game-javascript').innerHTML = "";
				}

				else if (xhr.readyState == 4 && xhr.status != 200) {
					console.log(
						'Une erreur est survenue !' +
						'\nCode :' + xhr.status +
						'\nTexte : ' + xhr.statusText
					);
				}
			}

			xhr.send(null);
		}

		process(); //first launch -> others are auto

	</script>

<?php

	}

?>

<?php
	include_once realpath(dirname(__FILE__) . '/../../inc/html_inc/contact.php');
	include_once realpath(dirname(__FILE__) . '/../../inc/html_inc/footer.php');
?>
