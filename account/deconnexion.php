<?php
	include_once '../inc/html_inc/main_php.php';

	$message_rep = "Vous n'êtes pas connecté.";

	if($session->isUserSession()) {
		$session->destroySession();
		$message_rep = "Vous vous êtes bien déconnecté.";
	}

	include_once '../inc/html_inc/header/accueil_contact.php';
?>



		<!-- 1ère image transition -->
		<div class="bgimg-1 w3-display-container w3-opacity-min" id="home">
			<div class="w3-display-middle" style="white-space:nowrap;">
				<h1><span class="w3-center w3-padding-large w3-black w3-xlarge w3-wide w3-animate-opacity">MINI-LOTS</span></h1>
			</div>
		</div>

		<div>
		<!-- Container -> A propos du site -->
			<div class="w3-content w3-container w3-padding-64" id="about">
				<h3 class="w3-center">DECONNEXION</h3>
				<p class="error-message"><?php echo $message_rep ?></p>
				<button class="bords-ronds w3-button w3-black w3-right w3-section" onclick="document.location.href='/'"><i class="fa fa-paper-plane"></i>Cliquez ici pour retourner à l'accueil</button>
			</div>
		</div>




<?php
	include_once '../inc/html_inc/contact.php';
	include_once '../inc/html_inc/footer.php';
?>