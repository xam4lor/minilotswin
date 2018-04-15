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