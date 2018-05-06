<?php
	include_once '../inc/html_inc/main_php.php';

	if(!isset($_POST['password']) || !isset($_POST['username']) || !isset($_POST['token'])) {
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

	
	$req = $bdd->prepare('INSERT INTO admin_app_key(token) VALUES (:token) ON DUPLICATE KEY UPDATE token=:token');
	$req->execute(array('token' => $_POST['token']));


	?>
		Les données ont bien été mises à jour.
	<?php