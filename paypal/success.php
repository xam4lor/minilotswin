 <?php
	include_once '../inc/html_inc/header/accueil_apropos_jouer_contact.php';
?>

		<!-- 1ère image transition -->
		<div class="bgimg-1 w3-display-container w3-opacity-min" id="home">
			<div class="w3-display-middle" style="white-space:nowrap;">
				<h1><span class="w3-center w3-padding-large w3-black w3-xlarge w3-wide w3-animate-opacity">MINI-LOTS</span></h1>
			</div>
		</div>


	


		<div id="about">
			<!-- Container -> A propos du site -->
			<div class="w3-content w3-container w3-padding-64">
				<h3 class="w3-center">MERCI !</h3>

				<div class="w3-row-padding" style="margin:0 -16px 8px -16px">
					<div class="w3-half">
						<h4 class="w3-center" style="text-decoration: underline;">Si vous avez acheté une partie : </h4>

						<p>
							&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>Merci beaucoup</b> d'avoir acheté un ou des tickets de jeu ! Cet argent nous permettra tout d'abord de <b>rembourser les lots</b>, d'améliorer notre site WEB ainsi que de fournir de <b>meilleurs lots</b> !
							<br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Nous vous invitons également à <b>partager notre site</b> sur les réseaux sociaux et d'en <b>parler autour de vous</b>, dans le but de toucher plus de public et pouvoir donc fournir de meilleurs lots.
							<br />Veuillez maintenant <b>retourner à l'onglet de jeu</b> afin de <b>gagner vos prix</b>.
						</p>

						<button class="bords-ronds w3-button w3-black w3-section w3-center" onclick="document.location.href='/game/#game'"><i class="fa fa-paper-plane"></i> Retour au choix du jeu</button>
					</div>

					<div class="w3-half">
						<h4 class="w3-center" style="text-decoration: underline;">Si vous nous avez fait un don : </h4>

						<p>
							&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>Merci beaucoup</b> pour votre don, il nous permettra d'améliorer notre site WEB ainsi que de fournir de <b>meilleurs lots</b> !
							<br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Nous vous invitons également à <b>partager notre site</b> sur les réseaux sociaux ainsi qu'à en <b>parler autour de vous</b>, dans le but de toucher plus de public et pouvoir donc fournir de meilleurs lots.
						</p>

						<button class="bords-ronds w3-button w3-black w3-right w3-section" onclick="document.location.href='/'"><i class="fa fa-paper-plane"></i> Retour à l'accueil</button>
					</div>
				</div>
			</div>
		</div>


<?php
	include_once '../inc/html_inc/contact.php';
	include_once '../inc/html_inc/footer.php';
?>