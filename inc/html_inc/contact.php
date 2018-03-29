
		<!-- 3e image transition -->
		<div class="bgimg-3 w3-display-container w3-opacity-min">
			<div class="w3-display-middle">
				<span class="w3-xxlarge w3-text-white w3-wide"></span>
			</div>
		</div>




		<!-- Container -> Contact -->
		<div id="contact" class="w3-content w3-container w3-padding-32">
			<div class="w3-container w3-padding-32">
				<h3 class="w3-center">FAIRE UN DON</h3>
				<p class="w3-center" style="padding-bottom: 3%;"><em>N'hésitez pas à faire un don pour nous aider dans le développement de ce site. Le montant du don doit être <b>supérieur</b> à <b>27 centimes</b> (<b>0.27€</b>)</b> sans quoi les taxes sont supérieures au montant du don et nous ne recevons rien.</em></p>

				<form method="post" target="_top" class="w3-center" action="https://www.paypal.com/cgi-bin/webscr">
					<input name="cmd" value="_s-xclick" type="hidden">
					<input name="hosted_button_id" value="HZZWKHJ6H8NEQ" type="hidden">
					<input src="https://www.paypalobjects.com/fr_FR/FR/i/btn/btn_donateCC_LG.gif" name="submit" alt="PayPal, le réflexe sécurité pour payer en ligne" type="image" border="0">
					<img alt="" src="https://www.paypalobjects.com/fr_FR/i/scr/pixel.gif" width="1" height="1" border="0">
				</form>
			</div>


			<hr class="w3-padding-16" style="border-top: 1px solid #d4d1d1;">

			<div>
				<h3 class="w3-center">NOUS CONTACTER</h3>

				<?php
					if((isset($_POST['pseudo']) && isset($_POST['email']) && isset($_POST['text'])) || (isset($_POST['text']) && $session->isUserSession())) {
						if($session->isUserSession() || filter_var(htmlspecialchars($_POST['email']), FILTER_VALIDATE_EMAIL)) {
							if($session->isUserSession()) {
								$req = $bdd->prepare("INSERT INTO requetes_contact(pseudo, email, message, date_post) VALUES (:pseudo, :email, :message, NOW())");
								$req->execute(array('pseudo' => $session->getUserSession()['username'], 'email' => $session->getUserSession()['email'], 'message' => htmlspecialchars($_POST['text'])));
							}
							else {
								$req = $bdd->prepare("INSERT INTO requetes_contact(pseudo, email, message, date_post) VALUES (:pseudo, :email, :message, NOW())");
								$req->execute(array('pseudo' => ("Non connecté : " . htmlspecialchars($_POST['pseudo'])), 'email' => htmlspecialchars($_POST['email']), 'message' => htmlspecialchars($_POST['text'])));
							}

							// CONFIRMATION : email bien envoyé
							?>
						<p class="w3-center"><em>Le message a bien été envoyé. Nous vous recontacterons le plus vite possible.</em></p>
							<?php
						}
						else {
							// ERROR : Syntaxe email incorect
							?>
						<p class="w3-center"><em>La syntaxe de l'adresse email fournie (<?php echo htmlspecialchars($_POST['email']) ?>) est incorrecte.</em></p>
							<?php
						}
					}
					else {
						?>
					<p class="w3-center"><em>N'hésitez pas à nous contacter pour nous donner votre avis sur le site, des améliorations à faire<br />mais aussi pour nous signaler d'éventuels problèmes ou bugs à corriger.</em></p>
						<?php
					}
				?>

				

				<div class="w3-row w3-padding-32 w3-section" style="display: center;">
					<div class="w3-large w3-margin-bottom">
						<i class="fa fa-envelope fa-fw w3-hover-text-black w3-xlarge w3-margin-right"></i> Email du site : minilotswin@gmail.com<br />
					</div>

					<form method="post" action="#contact">
						<?php
							if(!$session->isUserSession()) {
						?>
							<div class="w3-row-padding" style="margin:0 -16px 8px -16px">
								<div class="w3-half">
									<input class="w3-input w3-border" type="text" placeholder="Nom et prénom" required name="pseudo" />
								</div>
								<div class="w3-half">
									<input class="w3-input w3-border" type="text" placeholder="Email" required name="email" />
								</div>
							</div>
						<?php
							}
						?>

						<input class="w3-input w3-border" type="text" placeholder="Votre message" required name="text" />

						<button class="bords-ronds w3-button w3-black w3-right w3-section" type="submit">
							<i class="fa fa-paper-plane"></i> Envoyer votre message
						</button>
					</form>
				</div>
			</div>
		</div>


