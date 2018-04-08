<?php
	include_once '../inc/html_inc/header/accueil_contact.php';

?>

	<!-- 1ère image transition -->
	<div class="bgimg-1 w3-display-container w3-opacity-min" id="home">
		<div class="w3-display-middle" style="white-space:nowrap;">
			<h1><span class="w3-center w3-padding-large w3-black w3-xlarge w3-wide w3-animate-opacity">MINI-LOTS</span></h1>
		</div>
	</div>

<?php


	if($session->isUserSession() || !isset($_GET['token'])) {
		?>
		<meta http-equiv="Refresh" content="0; URL=/account/#about">

		<div class="w3-content w3-container w3-padding-64">
			<h3 class="w3-center" id="game-title">REDIRECTION</h3>
			
			<p id="game-text">Vous ne pouvez pas accéder à cette page.</p>
			<button class="bords-ronds w3-button w3-black w3-right w3-section" onclick="document.location.href='/'"><i class="fa fa-paper-plane"></i> Retour à l'accueil</button>
		</div>
		<?php
		exit();
	}

	if($session->confirmAccountFromToken($_GET['token'])) {
		// success
		?>

		<div id="game">
			<div class="w3-content w3-container w3-padding-64">
				<h3 class="w3-center" id="game-title">COMPTE CONFIRME</h3>
				
				<p id="game-text">Votre compte a bien été confirmé, cliquez sur le bouton suivant pour vous rediriger vers la page de connexion afin de vous-y connecter.</p>
				<button class="bords-ronds w3-button w3-black w3-right w3-section" onclick="document.location.href='/account/connexion.php#about'"><i class="fa fa-paper-plane"></i> Connexion</button>
			</div>
		</div>

		<?php
	}
	else {
		// token not valid
		?>

		<div id="game">
			<div class="w3-content w3-container w3-padding-64">
				<h3 class="w3-center" id="game-title">TOKEN INVALIDE</h3>
				
				<p id="game-text">Le token n'est pas valide, veuillez vérifier l'URL contenu dans le mail. Cliquez sur le bouton suivant pour vous rediriger vers l'accueil.</p>
				<button class="bords-ronds w3-button w3-black w3-right w3-section" onclick="document.location.href='/'"><i class="fa fa-paper-plane"></i> Accueil</button>
			</div>
		</div>

		<?php
	}

	include_once realpath(dirname(__FILE__) . '/../inc/html_inc/contact.php');
	include_once realpath(dirname(__FILE__) . '/../inc/html_inc/footer.php');
?>
