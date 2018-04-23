<?php
	include_once '../inc/html_inc/main_php.php';

	if(!isset($_POST['password']) || !isset($_POST['username'])) {
		?>
			Vous n'êtes pas autorisé à voir cette page.
		<?php
		exit();
	}


	$config = $config->getAppConfig();

	if(
		strcmp(htmlspecialchars($_POST['username']), $config['user']) != 0
		|| strcmp(htmlspecialchars($_POST['password']), $config['password']) != 0
	)
	{
		?>
			Les identifiants de connexion sont incorrects.
		<?php
		exit();
	}












	// IDENTIFICATION VALIDEE
	// Nombre de comptes créés
	$comptes_crees = 0;

	$req = $bdd->prepare('SELECT id FROM account');
	$req->execute(array());

	while ($donnees = $req->fetch()) {
		$comptes_crees++;
	}
	// ------------------------




	// Nombre de personnes connectées
	$nombre_connectes = 0;

	$req = $bdd->prepare('SELECT id FROM connectes');
	$req->execute(array());

	while ($donnees = $req->fetch()) {
		$nombre_connectes++;
	}
	// ------------------------




	// Clés gratuites restances + totales
	$cles_gratuites_restantes = 0;
	$cles_gratuites_total = 0;

	$cles_sudoku_restantes = 0;
	$cles_sudoku_total = 0;

	$cles_morpion_restantes = 0;
	$cles_morpion_total = 0;



	$req = $bdd->prepare('SELECT * FROM lots_list');
	$req->execute(array());

	while ($donnees = $req->fetch()) {
		if($donnees['key_type'] == 0) {
			if($donnees['use_left_nb'] > 0)
				$cles_gratuites_restantes++;
			
			$cles_gratuites_total++;
		}


		else if($donnees['key_type'] == 1) {
			if($donnees['use_left_nb'] > 0)
				$cles_sudoku_restantes++;
			
			$cles_sudoku_total++;
		}


		else if($donnees['key_type'] == 2) {
			if($donnees['use_left_nb'] > 0)
				$cles_morpion_restantes++;
			
			$cles_morpion_total++;
		}
	}
	// ------------------------




	// Nombre de paiements reçus
	$nombre_tickets_achetes = 0;
	$nombre_dons_recus = 0;

	$req = $bdd->prepare('SELECT id FROM payments');
	$req->execute(array());

	while ($donnees = $req->fetch()) {
		if($donnees['lot_added'] == 1)
			$nombre_tickets_achetes++;

		else
			$nombre_dons_recus++;
	}
	// ------------------------




	// Nombre de requêtes de contact
	$nombre_requetes = 0;

	$req = $bdd->prepare('SELECT id FROM requetes_contact');
	$req->execute(array());

	while ($donnees = $req->fetch()) {
		$nombre_requetes++;
	}
	// ------------------------










	$datas = "{"
			  . "'account' : {"
			  	. "'description' : " . "'Contient la liste des comptes du site'" . ","

			  	. "'comptes_crees' : {"
			  		. "'value' : '" . $comptes_crees . "',"
			  		. "'description' : " . "'Nombre total de comptes crees sur le site'"
			  	. "}"
			. "},"



			. "'connectes' : {"
				. "'description' : " . "'Liste des personnes actuellement connectees'" . ","

				. "'nombre_connectes' : {"
			  		. "'value' : '" . $nombre_connectes . "',"
			  		. "'description' : " . "'Nombre de personnes actuellement connectes sur le site'"
			  	. "}"
			. "},"



			. "'lots_list' : {"
				. "'description' : " . "'Contient la liste des lots du site'" . ","

				. "'cles_gratuites_restantes' : {"
			  		. "'value' : '" . $cles_gratuites_restantes . "',"
			  		. "'description' : " . "'Nombre de cles gratuites restantes'"
			  	. "},"
				. "'cles_gratuites_total' : {"
			  		. "'value' : '" . $cles_gratuites_total . "',"
			  		. "'description' : " . "'Nombre de cles gratuites totales sur le site'"
			  	. "},"

				. "'cles_sudoku_restantes' : {"
			  		. "'value' : '" . $cles_sudoku_restantes . "',"
			  		. "'description' : " . "'Nombre de cles de sudoku restantes'"
			  	. "},"
				. "'cles_sudoku_total' : {"
			  		. "'value' : '" . $cles_sudoku_total . "',"
			  		. "'description' : " . "'Nombre de cles de sudoku totales'"
			  	. "},"

				. "'cles_morpion_restantes' : {"
			  		. "'value' : '" . $cles_morpion_restantes . "',"
			  		. "'description' : " . "'Nombre de cles de morpion restantes'"
			  	. "},"
				. "'cles_morpion_total' : {"
			  		. "'value' : '" . $cles_morpion_total . "',"
			  		. "'description' : " . "'Nombre de cles de morpion totales'"
			  	. "}"
			. "},"



			. "'payments' : {"
				. "'description' : " . "'Contient la liste des paiements et des dons du site'" . ","

				. "'nombre_tickets_achetes' : {"
			  		. "'value' : '" . $nombre_tickets_achetes . "',"
			  		. "'description' : " . "'Nombre total de tickets achetes'"
			  	. "},"
				. "'nombre_dons_recus' : {"
			  		. "'value' : '" . $nombre_dons_recus . "',"
			  		. "'description' : " . "'Nombre total de dons'"
			  	. "}"
			. "},"



			. "'requetes_contact' : {"
				. "'description' : " . "'Contient la liste des requetes de contact du site'" . ","

				. "'nombre_requetes' : {"
			  		. "'value' : '" . $nombre_requetes . "',"
			  		. "'description' : " . "'Nombre de requetes de contact'"
			  	. "}"
			. "}"
		. "}";

	echo $datas;