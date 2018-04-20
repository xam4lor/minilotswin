<?php
	include_once realpath(dirname(__FILE__) . "/../../inc/html_inc/header/accueil_jouer_contact_direct.php");
?>


	<div class="bgimg-1 w3-display-container w3-opacity-min" id="home">
		<div class="w3-display-middle" style="white-space:nowrap;">
			<h1><span class="w3-center w3-padding-large w3-black w3-xlarge w3-wide w3-animate-opacity">MINI-LOTS</span></h1>
		</div>
	</div>


<?php
	if(!$session->isUserSession()) {
		?>

	<div id="game">
		<div class="w3-content w3-container w3-padding-64">
			<h3 class="w3-center" id="game-title">CONNEXION REQUISE</h3>
			
			<p id="game-text">Vous devez vous connecter pour jouer, cliquez sur le bouton suivant pour vous rediriger vers la page de connexion.</p>
			<button class="bords-ronds w3-button w3-black w3-right w3-section" onclick="document.location.href='/account/connexion.php#about'"><i class="fa fa-paper-plane"></i> Connexion</button>
		</div>
	</div>
		<?php
	}
	else {
		?>
	<div>
		<div class="w3-content w3-container w3-padding-64" id="game">
			<h3 class="w3-center">JOUEZ A UNE PARTIE PAYANTE</h3>
				<div class="w3-row-padding" style="margin: 0 -16px 8px -16px">
					<?php
						// ----------------------- SUDOKU -----------------------
						$sudoku_party_nb = $session->getSudokyPartyLeftNumber();

						if($sudoku_party_nb > 0) {
							if($sudoku_party_nb <= 1)	$text = "Vous avez <b>1 partie de Sudoku</b> disponible";
							else 						$text = "Vous avez <b>" . $sudoku_party_nb . " parties de Sudoku</b> disponibles";
							?>
								<div class="w3-half">
									<h4 class="w3-center" style="text-decoration: underline;">Sudoku : </h4>

									<p>&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $text; ?>. Cliquez sur le bouton ci-dessous pour pouvoir jouer à ce jeu et gagner vos prix.</p>

									<button class="bords-ronds w3-button w3-black w3-section w3-center"><i class="fa fa-paper-plane"></i> En développement</button>
									<!-- <button class="bords-ronds w3-button w3-black w3-section w3-center" onclick="document.location.href='sudoku/#game'"><i class="fa fa-paper-plane"></i> Jouer au Sudoku</button> -->
								</div>
							<?php
						}
						else {
							?>
								<div class="w3-half">
									<h4 class="w3-center" style="text-decoration: underline;">Sudoku : </h4>

									<p>&nbsp;&nbsp;&nbsp;&nbsp;Achetez une partie de Sudoku et soyez sûr à <b>100%</b> de gagner <b>une clé Steam</b> ou <b>un bien matériel</b>.</p>

									<button class="bords-ronds w3-button w3-black w3-section w3-center"><i class="fa fa-paper-plane"></i> En développement</button>
									<!-- <button class="bords-ronds w3-button w3-black w3-section w3-center" onclick="document.location.href='sudoku/#game'"><i class="fa fa-paper-plane"></i> Acheter une partie</button> -->
								</div>
							<?php
						}
						// ------------------------------------------------------




						// ----------------------- MORPION -----------------------
						$morpion_party_nb = $session->getMorpionPartyLeftNumber();

						if($morpion_party_nb > 0) {
							if($morpion_party_nb <= 1)	$text = "Vous avez <b>1 partie de Morpion payante</b> disponible";
							else 						$text = "Vous avez <b>" . $morpion_party_nb . " parties de Morpion payantes</b> disponibles";
							?>
								<div class="w3-row-padding w3-half" style="margin:0 -16px 8px -16px">
									<h4 class="w3-center" style="text-decoration: underline;">Morpion : </h4>

									<p>&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $text; ?>. Cliquez sur le bouton ci-dessous pour pouvoir jouer à ce jeu et gagner vos prix.</p>

									<button class="bords-ronds w3-button w3-black w3-section w3-center" onclick="document.location.href='morpion/#game'"><i class="fa fa-paper-plane"></i> Jouer au Morpion</button>
								</div>
							<?php
						}
						else {
							?>
								<div class="w3-row-padding w3-half" style="margin:0 -16px 8px -16px">
									<h4 class="w3-center" style="text-decoration: underline;">Morpion : </h4>

									<p>&nbsp;&nbsp;&nbsp;&nbsp;Achetez une partie de Morpion payante et soyez sûr à <b>100%</b> de gagner <b>une clé Steam</b> ou <b>un bien matériel</b>.</p>

									<button class="bords-ronds w3-button w3-black w3-section w3-center" onclick="document.location.href='morpion/#game'"><i class="fa fa-paper-plane"></i> Acheter une partie</button>
								</div>
							<?php
						}
						// ------------------------------------------------------
					?>
				</div>
		</div>
	</div>
		<?php
	}

	include_once realpath(dirname(__FILE__) . "/../../inc/html_inc/contact.php");
	include_once realpath(dirname(__FILE__) . "/../../inc/html_inc/footer.php");
?>