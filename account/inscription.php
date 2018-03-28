<?php
	include_once '../inc/html_inc/header/accueil_contact.php';


	if($session->isUserSession()) {
		header("Location: /account/");
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


			if(htmlspecialchars($_POST['password']) != htmlspecialchars($_POST['password_conf'])) {
				$error_msg = "Les mots de passe ne correspondent pas.";
			}
			else if(!filter_var(htmlspecialchars($_POST['email']), FILTER_VALIDATE_EMAIL)) {
				$error_msg = "L'adresse e-mail est incorrecte.";
			}
			else {
				$pass = $encryption_key->cryptPassword($_POST['password']);

				$connected = $session->createAccount(htmlspecialchars($_POST['pseudo']), htmlspecialchars($_POST['email']), $pass);
				$error_msg = "Un compte avec ce nom d'utilisateur ou cette adresse mail existe déjà.";
			}

			if($connected) {
		?>
			<div>
			<!-- Container -> A propos du site -->
				<div class="w3-content w3-container w3-padding-64" id="about">
					<script type="text/javascript">
						window.location.replace("/account/#about");
					</script>

					<p>Vous vous êtes bien inscrit. Vous allez être redirigé dans quelques secondes sinon cliquez sur le bouton suivant :</p>
					<button class="bords-ronds w3-button w3-black w3-right w3-section" onclick="document.location.href='/'"><i class="fa fa-paper-plane"></i>Cliquez ici</button>
				</div>
			</div>
		<?php
				$dispForm = false;
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
							echo $error_msg . '<br />Veuillez rentrer une adresse mail valable, cette dernière servant à vous attribuer vos lots.';
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