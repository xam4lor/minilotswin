<?php
	include_once '../inc/html_inc/header/accueil_contact.php';


	if($session->isUserSession()) {
		?>
		<meta http-equiv="Refresh" content="0; URL=/account/">

		<div class="w3-content w3-container w3-padding-64">
			<h3 class="w3-center" id="game-title">REDIRECTION</h3>
			
			<p id="game-text">Vous ne pouvez pas accéder à cette page.</p>
			<button class="bords-ronds w3-button w3-black w3-right w3-section" onclick="document.location.href='/'"><i class="fa fa-paper-plane"></i> Retour à l'accueil</button>
		</div>
		<?php
		exit();
	}

	$dispForm = true;
?>

		<!-- 1ère image transition -->
		<div class="bgimg-1 w3-display-container w3-opacity-min" id="home">
			<div class="w3-display-middle" style="white-space:nowrap;">
				<h1><span class="w3-center w3-padding-large w3-black w3-xlarge w3-wide w3-animate-opacity">MINI-LOTS</span></h1>
			</div>
		</div>


	<?php
		if(isset($_POST['pseudo']) && isset($_POST['email']) && isset($_POST['password']) && isset($_POST['password_conf'])) {
			$error_msg = "";
			$connected = false;
			$account_valid_mode = $config->getAccountConfig()['account_valid_mode'];


			if(htmlspecialchars($_POST['password']) != htmlspecialchars($_POST['password_conf'])) {
				$error_msg = "Les mots de passe ne correspondent pas.";
			}
			else if(!filter_var(htmlspecialchars($_POST['email']), FILTER_VALIDATE_EMAIL)) {
				$error_msg = "L'adresse e-mail est incorrecte.";
			}
			else {
				$pass = $encryption_key->cryptPassword($_POST['password']);

				$connected = $session->createAccount(htmlspecialchars($_POST['pseudo']), htmlspecialchars($_POST['email']), $pass, $_SERVER['REMOTE_ADDR'], $account_valid_mode);
				$error_msg = "Un compte avec ce nom d'utilisateur ou cette adresse mail existe déjà, ou vous avez déjà créé un compte avec cette IP.";
			}

			if($connected) {
				$dispForm = false;

				if($account_valid_mode == 0) {
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
					?>
						<div id="about">
							<div class="w3-content w3-container w3-padding-64" id="about">
								<h3 class="w3-center">MAIL DE CONFIRMATION</h3>
								<p>Vous vous êtes bien inscrit. Veuillez maintenant confirmer votre compte en cliquant sur le lien contenu dans le mail envoyé à l'adresse '<?php echo $_POST['email'] ?>'.</p>

								<br /><br /><button class="bords-ronds w3-button w3-black w3-right w3-section" onclick="document.location.href='/'"><i class="fa fa-paper-plane"></i>Cliquez ici pour retourner à l'accueil</button>
							</div>
						</div>
					<?php
				}
				else if($account_valid_mode == 2) {
					?>
						<div id="about">
							<div class="w3-content w3-container w3-padding-64" id="about">
								<h3 class="w3-center">MAIL DE CONFIRMATION</h3>

								<p>Si vous n'êtes pas redirigé dans quelques secondes, veuillez cliquer sur le bouton suivant :
									<button class="bords-ronds w3-button w3-black w3-right w3-section" onclick="document.location.href='confirm.php'"><i class="fa fa-paper-plane"></i> Cliquez ici</button>
								</p>

								<script type="text/javascript">
									window.location.replace("confirm.php");
								</script>

								<br /><br /><button class="bords-ronds w3-button w3-black w3-right w3-section" onclick="document.location.href='/'"><i class="fa fa-paper-plane"></i>Cliquez ici pour retourner à l'accueil</button>
							</div>
						</div>
					<?php
				}
				else {
					$dispForm = true;
				}
			}
		}


		if($dispForm) {
	?>
		<div>
		<!-- Container -> A propos du site -->
			<div class="w3-content w3-container w3-padding-64" id="about">
				<h3 class="w3-center">INSCRIPTION A L'INTERFACE</h3>
				<p class="error-message">
					<?php
						if(isset($error_msg) && $error_msg != "") {
							echo $error_msg . '<br />Veuillez donc rentrer une adresse mail valable et unique, cette dernière servant à vous attribuer vos lots.';
						}
						else {
							echo "Veuillez rentrer une adresse mail valable, cette dernière servant à vous attribuer vos lots.";
						}
					?>
				</p>

				<form method="post" action="#about">
					<div class="w3-row-padding" style="margin:0 -16px 8px -16px">
						<div class="w3-half">
							<input class="w3-input w3-border" type="text" placeholder="Pseudo" required name="pseudo" />
						</div>
						<div class="w3-half">
							<input class="w3-input w3-border" type="mail" placeholder="Adresse mail" required name="email" />
						</div>
					</div>

					<div class="w3-row-padding" style="margin:0 -16px 8px -16px">
						<div class="w3-half">
							<input class="w3-input w3-border" type="password" placeholder="Mot de passe" required name="password" />
						</div>
						<div class="w3-half">
							<input class="w3-input w3-border" type="password" placeholder="Confirmez le mot de passe" required name="password_conf" />
						</div>
					</div>

					<button class="bords-ronds w3-button w3-black w3-right w3-section" type="submit">
						<i class="fa fa-paper-plane"></i> INSCRIPTION
					</button>
				</form>
			</div>
		</div>
	<?php
		}
	?>



<?php
	include_once '../inc/html_inc/contact.php';
	include_once '../inc/html_inc/footer.php';
?>