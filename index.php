<?php
	include_once 'inc/html_inc/header/accueil_apropos_jouer_contact.php';

	$fields = array(
		'data' => array(
			'title' => 'TITRE de test2', 
			'body' => 'Ceci est une petite notification2',
			'long_body' => 'Ceci2 est un vrai test parcequ\'au final les vrais texts eh ben c\'est plutot bien et ceci est un vrai test parcequ\'au final les vrais texts eh ben c\'est plutot bien et ceci est un vrai test parcequ\'au final les vrais texts eh ben c\'est plutot bien'
		)
	);
	$admin_app_notif->sendNotification($fields);


	//---------------- NOMBRE LOTS DISTRIBUES + NON DISTRIBUES ----------------
	$lots_already_distrib = 0;
	$cle_number = 0;

	$req = $bdd->prepare('SELECT * FROM lots_list WHERE use_left_nb > 0 AND key_type = 0'); //nb clés free disponibles
	$req->execute(array());

	while ($donnees = $req->fetch()) {
		$arrayDatas = explode('§§', $donnees['mail_user']);
		$already_get = false;

		for ($i = 0; $i < sizeof($arrayDatas); $i++) {
			if($session->isUserSession() && $arrayDatas[$i] == $session->getUserSession()['email']) { //si mail correspond
				$already_get = true;
			}
		}

		if(!$already_get) { //si clé pas déjà récupérée
			$cle_number++; //nb clés dispo
		}
	}

	$total_cle_nb = 0;

	$req = $bdd->prepare('SELECT id FROM lots_list WHERE key_type = 0'); //nb clés free total
	$req->execute(array());

	while ($donnees = $req->fetch()) {
		$total_cle_nb++;
	}

	$lots_already_distrib = $total_cle_nb - $cle_number; //nb lots déjà récupérés = nb lots au total - nb lots dispo



	$cle_number_html = "";
	$lots_already_distrib_html = "";

	if($cle_number == 0) {
		$cle_number = "Aucun";
		$cle_number_html = "lot gratuit n'est actuellement disponible";
	}
	else if($cle_number <= 1) {
		$cle_number_html = "lot gratuit est actuellement disponible";
	}
	else {
		$cle_number_html = "lots gratuits sont actuellement disponibles";
	}


	if($lots_already_distrib == 0) {
		$lots_already_distrib = "Aucun";
		$lots_already_distrib_html = "lot gratuit n'a déjà été distribué";
	}
	else if($lots_already_distrib <= 1) {
		$lots_already_distrib_html = "lot gratuit a déjà été distribué";
	}
	else {
		$lots_already_distrib_html = "lots gratuits ont déjà été distribués";
	}
	//----------------------------------------------------------------------------


	//------------------------ NOMBRE VISITEURS CONNECTES ------------------------
	$visiteurs_connectes = 0;
	$visiteurs_connectes_html = "";

	$req3 = $bdd->prepare('SELECT ip FROM connectes');
	$req3->execute(array());

	while ($donnees3 = $req3->fetch()) {
		$visiteurs_connectes++;
	}

	if($visiteurs_connectes == 0) {
		$visiteurs_connectes = "Aucun";
		$visiteurs_connectes_html = "visiteur n'est actuellement connecté";
	}
	else if($visiteurs_connectes <= 1) {
		$visiteurs_connectes_html = "visiteur est actuellement connecté";
	}
	else {
		$visiteurs_connectes_html = "visiteurs sont actuellement connectés";
	}
	//---------------------------------------------------------------------------

	//------------------------------ COMPTES CREES ------------------------------
	$comptes_add = 0;
	$comptes_add_html = "";

	$req4 = $bdd->prepare('SELECT id FROM account');
	$req4->execute(array());

	while ($donnees4 = $req4->fetch()) {
		$comptes_add++;
	}

	if($comptes_add == 0) {
		$comptes_add = "Aucun";
		$comptes_add_html = "compte n'a déjà été créé";
	}
	else if($comptes_add <= 1) {
		$comptes_add_html = "compte a déjà été créé";
	}
	else {
		$comptes_add_html = "comptes ont déjà été créés";
	}
	//-------------------------------------------------------------------------

	$win_proba = $params->getWinPercentage(); // win proba
?>

		<!-- 1ère image transition -->
		<div class="bgimg-1 w3-display-container w3-opacity-min" id="home">
			<div class="w3-display-middle" style="white-space:nowrap;">
				<h1><span class="w3-center w3-padding-large w3-black w3-xlarge w3-wide w3-animate-opacity">MINI-LOTS</span></h1>
			</div>
		</div>



		<div>
		<!-- Container -> A propos du site -->
			<div class="w3-content w3-container w3-padding-64" id="about">
				<h3 class="w3-center">A PROPOS</h3>

				<p>
					Bienvenue sur Mini Lots !
					<br />Ce site vous permet de jouer à des mini-jeux simples afin de pouvoir gagner des lots gratuitement.
					<br />Vous pouvez jouer quinze fois gratuitement par jour et tenter de gagner des lots d'une valeur de 2€ minimum.
					<br /><br />Si vous êtes développeur, n'hésitez pas à participer au développement du site ou à nous proposer des modifications sur le Github du site : <a class="github-button" href="https://github.com/xam4lor/minilotswin">Voir sur GitHub</a>
					<br /><br />Pour connaître toutes les informations en temps réel, suivez-nous sur Twitter : <a href="https://twitter.com/minilotswin" class="twitter-follow-button" data-show-count="false" data-size="large">Suivez-nous</a>
					<br /><br />Pour toute information complémentaire, veuillez consulter nos <a href="/account/cgu.php#about" class="w3-hover-text-green">Conditions Générales d'Utilisation</a>.
					<br />
				</p>



				<!-- Stats barre -->
				<p class="w3-large w3-center w3-padding-16">Statistiques disponibles :</p>
				<p class="w3-wide"><i class="fa fa-camera"></i> Taux de chance de gagner un lot gratuit</p>
				<div class="bords-ronds w3-light-grey">
					<div class="bords-ronds w3-container w3-padding-small w3-dark-grey w3-center" style="width: <?php echo $win_proba ?>%"><?php echo $win_proba ?>%</div>
				</div>
			</div>


			<!-- Numéros promotionnels -->
			<div class="w3-row w3-center w3-dark-grey w3-padding-16">
				<div class="w3-quarter w3-section">
					<span class="w3-xlarge"><?php echo $visiteurs_connectes ?></span><br>
					<?php echo $visiteurs_connectes_html ?>
				</div>

				<div class="w3-quarter w3-section">
					<span class="w3-xlarge"><?php echo $lots_already_distrib ?></span><br>
					<?php echo $lots_already_distrib_html ?>
				</div>
				<div class="w3-quarter w3-section">
					<span class="w3-xlarge"><?php echo $cle_number ?></span><br>
					<?php echo $cle_number_html ?>
				</div>

				<div class="w3-quarter w3-section">
					<span class="w3-xlarge"><?php echo $comptes_add ?></span><br>
					<?php echo $comptes_add_html ?>
				</div>
			</div>
		</div>



		<!-- 2e image transition -->
		<div class="bgimg-2 w3-display-container w3-opacity-min">
			<div class="w3-display-middle">
				<span class="w3-xxlarge w3-text-white w3-wide"></span>
			</div>
		</div>



		<!-- Container -> Jouer -->
		<div class="w3-content w3-container w3-padding-64" id="play">
			<h3 class="w3-center">JOUER</h3>
			<p class="w3-center"><em>Tentez de gagner une clé steam, un compte spotify, un compte minecraft et beaucoup d'autres lots !<br />N'hésitez pas à parler de notre site autour de vous afin de nous permettre de vous proposer une qualité supérieure.</em></p>
			<p class="w3-center"></p><button class="bords-ronds w3-button w3-black w3-right w3-section" onclick="document.location.href='/game/index.php#game'"><i class="fa fa-paper-plane"></i>Cliquez ici pour jouer</button>
			<br />
		</div>
		
	
<?php
	include_once 'inc/html_inc/contact.php';
	include_once 'inc/html_inc/footer.php';
?>