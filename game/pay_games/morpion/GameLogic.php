<?php
	include_once 'gameDatas/Morpion.php';
	include_once realpath(dirname(__FILE__) . "/../../../inc/html_inc/main_php.php");


	$game_number = 1;
	$game = null;

	if($session->isUserSession()) {
		if((isset($_GET["datas"]) && htmlspecialchars($_GET["datas"]) == "null")) { // -> premier lancement
			if(isset($_SESSION['game_save_pay_2']) && $_SESSION['game_save_pay_2'] != "") {
				$game = $_SESSION['game_save_pay_2'];

				echo $game->newRound($session, $params, $encryption_key, "recupere_data");
			}
			else {
				$game = new Morpion($params);

				$_SESSION['game_save_pay_2'] = $game;

				echo $game->firstLaunch();

				unset($_SESSION['datas_game_2']);
				$_SESSION['datas_game_2'] = $game;
			}
		}
			
		else if(isset($_GET["datas"]) && isset($_SESSION['datas_game_2'])) {
			$game = $_SESSION['datas_game_2'];

			$_SESSION['game_save_pay_2'] = $game;

			echo $game->newRound($session, $params, $encryption_key, htmlspecialchars($_GET["datas"]));

			$_SESSION['datas_game_2'] = $game;
		}
	}
	else {
		echo "<h3 class='w3-center'>CONNEXION REQUISE</h3><p class='error-message'>Vous devez vous connecter pour tenter de gagner des lots.</p>";
	}