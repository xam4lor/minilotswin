<?php
	include_once '../inc/html_inc/header/accueil_contact.php';
	

	if($session->isUserSession()) {
		header("Location: /account/");
	}
?>

		<!-- 1ère image transition -->
		<div class="bgimg-1 w3-display-container w3-opacity-min" id="home">
			<div class="w3-display-middle" style="white-space:nowrap;">
				<h1><span class="w3-center w3-padding-large w3-black w3-xlarge w3-wide w3-animate-opacity">MINI-LOTS</span></h1>
			</div>
		</div>

	<?php
		$show_error = true;
		$error_message = "";

		if(isset($_POST['pseudo']) && isset($_POST['password'])) {
			$isConnected = $session->connectUser(htmlspecialchars($_POST['pseudo']), $encryption_key->cryptPassword(htmlspecialchars($_POST['password'])));

			if($isConnected == -1) {
				$error_message = "<p class='error-message'>Vous avez été banni du site conformément à nos <a href=\"/account/cgu.php#about\" class=\"w3-hover-text-green\">Conditions Générales d'Utilisation</a>.</p>";
			}
			else if($isConnected) {
				$show_error = false;
		?>
			<div>
			<!-- Container -> A propos du site -->
				<div class="w3-content w3-container w3-padding-64" id="about">
					<script type="text/javascript">
						window.location.replace("/account/#about");
					</script>

					<p>Vous vous êtes bien connecté. Vous allez être redirigé dans quelques secondes sinon cliquez sur le bouton suivant :</p>
					<button class="bords-ronds w3-button w3-black w3-right w3-section" onclick="document.location.href='/'"><i class="fa fa-paper-plane"></i> Cliquez ici</button>
				</div>
			</div>
		<?php
			}
			else if(!$isConnected) {
				$error_message = "<p class='error-message'>Le nom d'utilisateur ou le mot de passe est incorrect.</p>";
			}
			else {
				$error_message = "<p class='error-message'>Erreur de connexion, si le problème persiste, veuillez contacter un administrateur.</p>";
			}
		}
		

		if($show_error) {
	?>
		<div>
		<!-- Container -> A propos du site -->
			<div class="w3-content w3-container w3-padding-64" id="about">
				<h3 class="w3-center">CONNEXION A L'INTERFACE</h3>
				<?php echo $error_message ?>
				<a href="inscription.php"><i>Cliquez ici pour vous inscrire</i></a><br />

				<form method="post" action="#about">
					<div class="w3-row-padding" style="margin:0 -16px 8px -16px">
						<div class="w3-half">
							<input class="w3-input w3-border" type="text" placeholder="Pseudo ou adresse mail" required name="pseudo" />
						</div>
						<div class="w3-half">
							<input class="w3-input w3-border" type="password" placeholder="Mot de passe" required name="password" />
						</div>
					</div>

					<button class="bords-ronds w3-button w3-black w3-right w3-section" type="submit">
						<i class="fa fa-paper-plane"></i> Connexion
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