<?php
	include_once realpath(dirname(__FILE__) . "/../config.php");
	include_once realpath(dirname(__FILE__) . "/../App.php");
	include_once realpath(dirname(__FILE__) . "/../EncryptionKey.php");
	include_once realpath(dirname(__FILE__) . "/../Parameters.php");
	include_once realpath(dirname(__FILE__) . "/../Session.php");
	include_once realpath(dirname(__FILE__) . "/../ToolsFunction.php");
	include_once realpath(dirname(__FILE__) . "/../AdminAppNotification.php");


	// ----- INITIATION DES VARIABLES DES FONCTIONS -----
	$config = Config::getInstance();
	$bdd = App::getDatabase($config->getAppConfig());
	$admin_app_notif = AdminAppNotification::getInstance($bdd, $config->getAppConfig()['authorization_key']);
	$config->setupParameters($bdd);
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
			&& strcmp($main_url . '/console_datas/get_datas.php', $main_url . "$_SERVER[REQUEST_URI]") != 0
			&& strcmp($main_url . '/console_datas/post_datas.php', $main_url . "$_SERVER[REQUEST_URI]") != 0
			&& strcmp($main_url . '/paypal/listener-ipn.php', $main_url . "$_SERVER[REQUEST_URI]") != 0
			&& strcmp($main_url . '/console_datas/fcm_id_actualisation.php', $main_url . "$_SERVER[REQUEST_URI]") != 0
			&& $maintenance
			&& !$session->isUserSession()
		)
		|| (
			   strcmp($main_url . '/maintenance.php', $main_url . "$_SERVER[REQUEST_URI]") != 0
			&& strcmp($main_url . '/console_datas/get_datas.php', $main_url . "$_SERVER[REQUEST_URI]") != 0
			&& strcmp($main_url . '/console_datas/post_datas.php', $main_url . "$_SERVER[REQUEST_URI]") != 0
			&& strcmp($main_url . '/paypal/listener-ipn.php', $main_url . "$_SERVER[REQUEST_URI]") != 0
			&& strcmp($main_url . '/console_datas/fcm_id_actualisation.php', $main_url . "$_SERVER[REQUEST_URI]") != 0
			&& $maintenance
			&& $session->isUserSession()
			&& $session->getUserSession()['admin'] != 1
		)
	) {
		header('Location: ' . $main_url . 'maintenance.php');
		exit();
	}