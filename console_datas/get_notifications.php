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
			. "\"notifications\" : [";

	$req = $bdd->prepare('SELECT * FROM notifications ORDER BY id DESC LIMIT ' . (intval($_POST['number_max']) + 30));
	$req->execute(array());
	$first = true;

	while ($donnees = $req->fetch()) {
		if($first)
			$first = false;
		else
			$datas .= ",";

		if($donnees['is_for_developper'])
			$donnees['is_for_developper'] = "true";
		else
			$donnees['is_for_developper'] = "false";


		$datas .=
				"{"
					. "\"id\":\"" . $donnees['id'] . "\","
					. "\"title\":\"" . $donnees['title'] . "\","
					. "\"body\":\"" . $donnees['body'] . "\","
					. "\"is_for_developper\":\"" . $donnees['is_for_developper'] . "\","
					. "\"date_publish\":\"" . $donnees['date_publish'] . "\""
				. "}";
	}

	$datas .=
			 "]"
		. "}";


	echo $datas;