<?php
	include_once '../inc/html_inc/header/accueil_contact.php';

	$account_valid_mode = $config->getAccountConfig()['account_valid_mode'];
?>

	<!-- 1ère image transition -->
	<div class="bgimg-1 w3-display-container w3-opacity-min" id="home">
		<div class="w3-display-middle" style="white-space:nowrap;">
			<h1><span class="w3-center w3-padding-large w3-black w3-xlarge w3-wide w3-animate-opacity">MINI-LOTS</span></h1>
		</div>
	</div>


<?php
	if($session->isUserSession() || (!isset($_GET['token']) && $account_valid_mode == 1) || ($account_valid_mode != 1 && $account_valid_mode != 2)) {
		?>
			<meta http-equiv="Refresh" content="0; URL=/account/#about">

			<div class="w3-content w3-container w3-padding-64">
				<h3 class="w3-center">REDIRECTION</h3>
				
				<p>Vous ne pouvez pas accéder à cette page.</p>
				<button class="bords-ronds w3-button w3-black w3-right w3-section" onclick="document.location.href='/'"><i class="fa fa-paper-plane"></i> Retour à l'accueil</button>
			</div>
		<?php
		exit();
	}




		if($account_valid_mode == 1 || ($account_valid_mode == 2 && isset($_POST['email']) && isset($_POST['code']))) {
			if(($account_valid_mode == 1 && $session->confirmAccountFromToken($_GET['token'])) || ($account_valid_mode == 2 && $session->confirmAccountFromToken($_POST['code']))) {
				// success
				?>
					<div id="about">
						<div class="w3-content w3-container w3-padding-64">
							<h3 class="w3-center">COMPTE CONFIRME</h3>
							
							<p>Votre compte a bien été confirmé, cliquez sur le bouton suivant pour vous rediriger vers la page de connexion afin de vous-y connecter.</p>
							<button class="bords-ronds w3-button w3-black w3-right w3-section" onclick="document.location.href='/account/connexion.php#about'"><i class="fa fa-paper-plane"></i> Connexion</button>
						</div>
					</div>
				<?php
			}
			else if($account_valid_mode == 1) {
				// token not valid
				?>
					<div id="about">
						<div class="w3-content w3-container w3-padding-64">
							<h3 class="w3-center">CODE INVALIDE</h3>
							
							<p>Le code de confirmation n'est pas valide, veuillez vérifier l'URL contenu dans le mail. Cliquez sur le bouton suivant pour vous rediriger vers l'accueil.</p>
							<button class="bords-ronds w3-button w3-black w3-right w3-section" onclick="document.location.href='/'"><i class="fa fa-paper-plane"></i> Accueil</button>
						</div>
					</div>
				<?php
			}
			else if($account_valid_mode == 2) {
				// token not valid
				?>
					<div id="about">
						<div class="w3-content w3-container w3-padding-64" id="about-2">
							<h3 class="w3-center">CODE INVALIDE</h3>
							
							<p>Le code de confirmation n'est pas valide, veuillez vérifier le code contenu dans le mail. Cliquez sur le bouton suivant pour rééssayer.</p>
							<button class="bords-ronds w3-button w3-black w3-right w3-section" onclick="document.location.href='/account/confirm.php'"><i class="fa fa-paper-plane"></i> Rééssayer</button>
						</div>
					</div>
				<?php
			}
		}
		else if($account_valid_mode == 2) {
			?>
				<div id="about">
					<div class="w3-content w3-container w3-padding-64">
						<h3 class="w3-center">CONFIRMATION DU COMPTE</h3>
							
						<p>Veuillez entrer le code de validation que vous avez reçu par email :</p>
						<form method="post" action="#about">
							<div class="w3-row-padding" style="margin:0 -16px 8px -16px">
								<div class="w3-half">
									<input class="w3-input w3-border" type="mail" placeholder="Adresse mail" required name="email" />
								</div>
								<div class="w3-half">
									<input class="w3-input w3-border" type="password" placeholder="Code de validation" required name="code" />
								</div>
							</div>

							<button class="bords-ronds w3-button w3-black w3-right w3-section" type="submit">
								<i class="fa fa-paper-plane"></i> CONFIRMATION
							</button>
						</form>
					</div>
				</div>
			<?php
		}

	include_once realpath(dirname(__FILE__) . '/../inc/html_inc/contact.php');
	include_once realpath(dirname(__FILE__) . '/../inc/html_inc/footer.php');
?>
