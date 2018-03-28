<?php
	include_once realpath(dirname(__FILE__) . "/../../inc/html_inc/header/accueil_jouer_contact_direct.php");
?>


	<!-- 1ère image transition -->
	<div class="bgimg-1 w3-display-container w3-opacity-min" id="home">
		<div class="w3-display-middle" style="white-space:nowrap;">
			<h1><span class="w3-center w3-padding-large w3-black w3-xlarge w3-wide w3-animate-opacity">MINI-LOTS</span></h1>
		</div>
	</div>


<?php
	$party_number = $session->getSudokyPartyLeftNumber();

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
	else if($party_number > 0) {
		if($party_number <= 1)	$text = "Vous avez <b>1 partie de Sudoku</b> disponible";
		else 					$text = "Vous avez <b>" . $party_number . " parties de Sudoku</b> disponibles";

		?>
	<div>
	<!-- Container -> A propos du site -->
		<div class="w3-content w3-container w3-padding-64" id="game">
			<h3 class="w3-center">JOUEZ AU SUDOKU</h3>

			<div class="w3-row-padding" style="margin:0 -16px 8px -16px">
				<h4 class="w3-center" style="text-decoration: underline;">Sudoku : </h4>

				<p>&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $text; ?>. Cliquez sur le bouton ci-dessous pour pouvoir jouer à ce jeu et gagner vos prix.</p>

				<button class="bords-ronds w3-button w3-black w3-section w3-center" onclick="document.location.href='sudoku/#game'"><i class="fa fa-paper-plane"></i> Jouer au Sudoku</button>
			</div>
		</div>
	</div>
		<?php
	}
	else {
?>

	<div>
	<!-- Container -> A propos du site -->
		<div class="w3-content w3-container w3-padding-64" id="game">
			<h3 class="w3-center">ACHETEZ DES TICKETS</h3>

			<div class="w3-row-padding" style="margin:0 -16px 8px -16px">
				<h4 class="w3-center" style="text-decoration: underline;">Sudoku : </h4>

				<p>&nbsp;&nbsp;&nbsp;&nbsp;Achetez une partie de Sudoku et soyez sûr à <b>100%</b> de gagner <b>100 clés steam</b> et <b>une clé CS:GO</b>.</p>

				<button class="bords-ronds w3-button w3-black w3-section w3-center" onclick="document.location.href='sudoku/#game'"><i class="fa fa-paper-plane"></i> Acheter une partie</button>
			</div>
		</div>
	</div>

<?php

	}

?>


<?php
	include_once realpath(dirname(__FILE__) . "/../../inc/html_inc/contact.php");
	include_once realpath(dirname(__FILE__) . "/../../inc/html_inc/footer.php");
?>