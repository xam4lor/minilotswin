<?php
	include_once '../inc/html_inc/main_php.php';

	if(!isset($_POST['password']) || !isset($_POST['username']) || !isset($_POST['datas'])) {
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


	$mysql_array = array();
	$line_tbl = explode("$$$$", $_POST['datas']);

	foreach ($line_tbl as $line_key => $line_val) {
		$line_compo = explode(";;;;", $line_val);
		$mysql_array[$line_compo[0]] = $line_compo[1];
	}

	$req = $bdd->prepare('UPDATE config SET parameters=:parameters, popup=:popup, account=:account, enkey=:enkey');
	$req->execute($mysql_array);


	?>
		Les données ont bien été mises à jour.
	<?php

	// TODO SYSTEME MAINTENANCE