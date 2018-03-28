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



	private $app_config;
	private $parameters_config;

	public function __construct() {
		// local
		$this->app_config = array(
			'host' => 'mysql:host=_host_;dbname=_dbname_;charset=utf8', // host de connexion à la BDD et nom de la BDD
			'user' => '_user_', 										// nom d'utilisateur
			'password' => '_password_' 									// mot de passe
		);
		// --------------------------------

		


		// ---------- PARAMETERS CONFIG ----------
		$this->parameters_config = array(
			'win_percentage' => 1, 			// % de chance de gagner un lot en gagnant une partie gratuite
			'nb_parties_max' => 1,			// nombre parties / jour gratuites
			'sudoku_gess_nb' => 1,			// nombre cases à deviner au sudoku
			'sudoku_ticket_price' => 1		// prix d'un ticket de sudoku (en euros)
		);
		// ---------------------------------------
	}





	public function getAppConfig() {
		return $this->app_config;
	}

	public function getParametersConfig() {
		return $this->parameters_config;
	}
}