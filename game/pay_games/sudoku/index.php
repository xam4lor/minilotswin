 <?php
	include_once realpath(dirname(__FILE__) . "/../../../inc/html_inc/header/accueil_jouer_contact_direct.php");
?>


	<!-- 1ère image transition -->
	<div class="bgimg-1 w3-display-container w3-opacity-min" id="home">
		<div class="w3-display-middle" style="white-space:nowrap;">
			<h1><span class="w3-center w3-padding-large w3-black w3-xlarge w3-wide w3-animate-opacity">MINI-LOTS</span></h1>
		</div>
	</div>


<?php
	$cle_number = $session->getFreeKeyNumber();

	if(!($session->getSudokyPartyLeftNumber() > 0)) $display_sudoku = false;
	else if($session->getSudokuKeyNumber() <= 0) $display_sudoku = false;
	else $display_sudoku = true;

 
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
	else if($display_sudoku) {
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
			document.getElementById('game-text').innerHTML = "Chargement en cours, veuillez patienter ...";

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


	else {
		$sudoku_key_left = $session->getSudokuKeyNumber();

		if($sudoku_key_left == 0) {
			?>

			<div id="game">
			<!-- Container -> A propos du site -->
				<div class="w3-content w3-container w3-padding-64">
					<h3 class="w3-center" id="game-title">TICKET DE SUDOKU</h3>

					<p id="game-text">&nbsp;&nbsp;&nbsp;&nbsp;Il n'y a actuellement <b>plus de lots</b> pour le sudoku restants, revenez dans quelques jours.<br/>Pour suivre l'actualité de notre site, <b>suivez nous</b> sur notre compte <b>Twitter</b> :
						<a href="https://twitter.com/minilotswin" class="twitter-follow-button" data-show-count="false" data-size="large">Suivez-nous</a>
					</p>

					<button class="margin-button bords-ronds w3-button w3-black w3-section" onclick="document.location.href='/'"><i class="fa fa-paper-plane"></i> Retour à l'accueil</button>
				</div>
			</div>

			<?php
		}
		else {
			?>

			<div id="game">
			<!-- Container -> A propos du site -->
				<div class="w3-content w3-container w3-padding-64">
					<h3 class="w3-center" id="game-title">TICKET DE SUDOKU</h3>

					<p id="game-text">Cliquez sur le bouton suivant pour acheter 1 ticket de sudoku pour <b><?php echo $params->getSudokuTicketPrice(); ?>&euro;</b> et gagner <b>une clé Steam</b> ou <b>un bien matériel</b>.</p>

					<center>
						<form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
							<input type="hidden" name="cmd" value="_s-xclick">
							<input type="hidden" name="hosted_button_id" value="2P9FKLEBRZKWL">
							<input type="image" src="https://www.paypalobjects.com/fr_FR/FR/i/btn/btn_buynowCC_LG.gif" border="0" name="submit" alt="PayPal, le réflexe sécurité pour payer en ligne">
							<img alt="" border="0" src="https://www.paypalobjects.com/fr_FR/i/scr/pixel.gif" width="1" height="1">
							<input type="hidden" name="custom" value=<?php echo '"payement_id=1§§user_id=' . $session->getUserSession()['id'] . '"'; ?>>
						</form>
					</center>
				</div>
			</div>

			<?php
		}
	}
	?>


<?php
	include_once realpath(dirname(__FILE__) . '/../../../inc/html_inc/contact.php');
	include_once realpath(dirname(__FILE__) . '/../../../inc/html_inc/footer.php');
?>
