<?php
	include_once '../inc/html_inc/main_php.php';

	if(!isset($_POST['password']) || !isset($_POST['username']) || !isset($_POST['number_max'])) {
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


	$datas = "{"
			. "'contact_request' : [";

	$req = $bdd->prepare('SELECT * FROM requetes_contact ORDER BY id DESC LIMIT ' . intval($_POST['number_max']));
	$req->execute();
	$first = true;

	while ($donnees = $req->fetch()) {
		if($first)
			$first = false;
		else
			$datas .= ",";


		$datas .=
				"{"
					. "'id':'" . $donnees['id'] . "',"
					. "'pseudo':'" . $donnees['pseudo'] . "',"
					. "'email':'" . $donnees['email'] . "',"
					. "'message':'" . $donnees['message'] . "',"
					. "'date_post':'" . $donnees['date_post'] . "'"
				. "}";
	}

	$datas .=
			 "]"
		. "}";


	echo $datas;