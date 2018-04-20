<?php
	
// FICHIER DE DEMONSTRATION DE 'config.php'
class Config {
	private static $instance;

	static function getInstance() {
		if(!self::$instance) {
			self::$instance = new Config();
		}
		return self::$instance;
	}



	private $app;
	private $parameters;
	private $popup;
	private $account;
	private $enkey;

	public function __construct() {
		// ---------- APP CONFIG ----------
		if (in_array($_SERVER['REMOTE_ADDR'], array('127.0.0.1', '::1','localhost'))) { 		// --- localhost ---
			$this->app = array(
				'host' => 'mysql:host=_local-host_;dbname=_local-dbname_;charset=utf8', 		// host de connexion à la BDD et nom de la BDD
				'user' => '_local-user_', 														// nom d'utilisateur
				'password' => '_local-password_' 												// mot de passe
			);
		}
		else {																					// --- serveur ---
			$this->app = array(
				'host' => 'mysql:host=_server-host_;dbname=_server-dbname_;charset=utf8', 		// host de connexion à la BDD et nom de la BDD
				'user' => '_server-user_', 														// nom d'utilisateur
				'password' => '_server-password_' 												// mot de passe
			);
		}
		// --------------------------------

		


		// ---------- PARAMETERS CONFIG ----------
		$this->parameters = array(
			'win_percentage' => -1, 		// % de chance de gagner un lot en gagnant une partie gratuite
			'nb_parties_max' => -1,			// nombre parties / jour gratuites
			'sudoku_gess_nb' => -1,			// nombre cases à deviner au sudoku
			'sudoku_ticket_price' => -1,	// prix d'un ticket de sudoku (en euros)
			'morpion_ticket_price' => -1	// prix d'un ticket de morpion (en euros)
		);
		// ---------------------------------------




		// ---------- COOKIE TEXT ----------
		$this->popup = array(
			'cookie_text' => '_cookie-text_', // texte du cookie
		);
		// ---------------------------------




		// ---------- ACCOUNT PARAMETERS ----------
		$this->account = array(
			'account_valid_mode' => -1,		// 0 : pas de validation par email
			 								// 1 : validation des comptes par un lien via email
			 								// 2 : validation des comptes par entrée manuelle du token reçu par mail
		);
		// ----------------------------------------




		// ---------- ACCOUNT PARAMETERS ----------
		$this->enkey = array(
			'lots_key_encryption' => '_encryption-Key_',		// clé d'encryption des lots
		);
		// ----------------------------------------
	}





	public function getAppConfig() {
		return $this->app;
	}

	public function getParametersConfig() {
		return $this->parameters;
	}

	public function getPopupConfig() {
		return $this->popup;
	}

	public function getAccountConfig() {
		return $this->account;
	}

	public function getEncryptionKeyConfig() {
		return $this->enkey;
	}
}