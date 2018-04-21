<?php
	include_once realpath(dirname(__FILE__) . "/../inc/html_inc/header/accueil_jouer_contact_recul.php");
?>
		<!-- 1ère image transition -->
		<div class="bgimg-1 w3-display-container w3-opacity-min" id="home">
			<div class="w3-display-middle" style="white-space:nowrap;">
				<span class="w3-center w3-padding-large w3-black w3-xlarge w3-wide w3-animate-opacity">Erreur 403 <i>(Forbidden)</i> : Vous n'avez pas le droit d'accéder à la ressource.</span>
				<button class="bords-ronds w3-button w3-black w3-right w3-section" onclick="document.location.href='/#about'"><i class="fa fa-paper-plane"></i> Retour à l'accueil</button>
			</div>
		</div>

<?php
	include_once realpath(dirname(__FILE__) . '/../inc/html_inc/contact.php');
	include_once realpath(dirname(__FILE__) . '/../inc/html_inc/footer.php');
?>
