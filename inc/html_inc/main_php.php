<?php
	include_once realpath(dirname(__FILE__) . "/../config.php");
	include_once realpath(dirname(__FILE__) . "/../App.php");
	include_once realpath(dirname(__FILE__) . "/../EncryptionKey.php");
	include_once realpath(dirname(__FILE__) . "/../Parameters.php");
	include_once realpath(dirname(__FILE__) . "/../Session.php");
	include_once realpath(dirname(__FILE__) . "/../ToolsFunction.php");


	// ----- INITIATION DES VARIABLES DES FONCTIONS -----
	$config = Config::getInstance();
	$bdd = App::getDatabase($config->getAppConfig());
	$encryption_key = EncryptionKey::getInstance($config->getEncryptionKeyConfig());
	$params = Parameters::getInstance($config->getParametersConfig());
	$session = Session::getInstance($bdd, $params);
	$tools = ToolsFunction::getInstance($bdd);
	// --------------------------------------------------


	$tools->updateVisiteursConnectes(); //update nombre visiteurs connectés

	// variables globales :
	$main_url = (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]/";
	$maintenance = $params->isWebsiteInMaintenance();

	// maintenance
	if(
		(
			strcmp($main_url . '/maintenance.php', $main_url . "$_SERVER[REQUEST_URI]") != 0
			&& $maintenance
			&& !$session->isUserSession()
		)
		|| (
			strcmp($main_url . '/maintenance.php', $main_url . "$_SERVER[REQUEST_URI]") != 0
			&& $maintenance
			&& $session->isUserSession()
			&& $session->getUserSession()['admin'] != 1
		)
	) {
		header('Location: ' . $main_url . 'maintenance.php');
		exit();
	}