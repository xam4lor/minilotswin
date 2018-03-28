<?php


class Session {
	static $instance;

	static function getInstance($bdd, $params) {
		if(!self::$instance) {
			self::$instance = new Session($bdd, $params);
		}
		return self::$instance;
	}




	private $bdd;
	private $params;


	public function __construct($bdd, $params){
		session_start();

		$this->bdd = $bdd;
		$this->params = $params;
	}


	// parametres de session de l'utilisateur
	public function setUserSession($user) {
		$_SESSION['user'] = $user;
	}

	public function getUserSession() {
		return $_SESSION['user'];
	}

	public function isUserSession() {
		if(isset($_SESSION['user'])) {
			return true;
		}
		return false;
	}

	public function destroySession() {
		unset($_SESSION['user']);
	}
	// ---------




	// CLES GRATUITES
	public function addFreePartyToPlay($partyNb) { // NOMBRE PARTIES FREE DISPONIBLES ++
		$req = $this->bdd->prepare('SELECT parties_free_left FROM account WHERE email=:email');
		$req->execute(array('email' => $this->getUserSession()['email']));

		$donnees = $req->fetch();

		$req2 = $this->bdd->prepare('UPDATE account SET parties_free_left=:parties_free_left, parties_free_timestamp=:parties_free_timestamp WHERE email=:email');
		$req2->execute(array('parties_free_left' => $donnees['parties_free_left'] + $partyNb, 'parties_free_timestamp' => time(), 'email' => $this->getUserSession()['email']));
	}


	public function addOneFreePartyPlay() { // NOMBRE PARTIES FREE JOUEES ++
		$this->setLastPartyDate();

		$req = $this->bdd->prepare('SELECT parties_free_left FROM account WHERE email=:email');
		$req->execute(array('email' => $this->getUserSession()['email']));

		$donnees = $req->fetch();

		$req2 = $this->bdd->prepare('UPDATE account SET parties_free_left=:parties_free_left, parties_free_timestamp=:parties_free_timestamp WHERE email=:email');
		$req2->execute(array('parties_free_left' => $donnees['parties_free_left'] - 1, 'parties_free_timestamp' => time(), 'email' => $this->getUserSession()['email']));

		unset($_SESSION['save_game']); //unset sauvegarde partie
	}


	public function isFreePartyLeft() { // PARTIE FREE DISPONIBLE ?
		$parties_max = $this->params->getNbPartiesMax();

		$req = $this->bdd->prepare('SELECT * FROM account WHERE email=:email');
		$req->execute(array('email' => $this->getUserSession()['email']));

		$donnees = $req->fetch();

		if($donnees['parties_free_left'] <= 0) {
			$difference = time() - $donnees['parties_free_timestamp'];

			if($difference > (24 * 60 * 60)) { //si cela fait plus d'un jour qu'il a joué sa dernière partie (24 * 60 * 60 secondes)
				$req2 = $this->bdd->prepare('UPDATE account SET parties_free_left=:parties_free_left, parties_free_timestamp=:parties_free_timestamp WHERE email=:email');
				$req2->execute(array('parties_free_left' => $parties_max, 'parties_free_timestamp' => time(), 'email' => $this->getUserSession()['email']));
			}
			else {
				return false;
			}
		}

		return true;
	}

	public function setLastPartyDate() { // MODIFIE LA DATE DE LA DERNIERE PARTIE JOUEE
		date_default_timezone_set('UTC'); //date jour
		$date = date('l jS \of F Y h:i:s A');

		$req = $this->bdd->prepare('UPDATE account SET last_partie_date=NOW() WHERE email=:email');
		$req->execute(array('email' => $this->getUserSession()['email']));
	}





	public function getKeyNumberByType($type) { // RETOURNE NOMBRE CLES DE SUDOKU RESTANTES
		$cle_number = 0;

		$req = $this->bdd->prepare('SELECT * FROM lots_list WHERE use_left_nb>0 AND key_type=:key_type');
		$req->execute(array('key_type' => $type));

		while ($donnees = $req->fetch()) {
			$arrayDatas = explode('§§', $donnees['mail_user']);
			$already_get = false;

			for ($i = 0; $i < sizeof($arrayDatas); $i++) {
				if($arrayDatas[$i] == $this->getUserSession()['email']) { // si mail correspond
					$already_get = true;
				}
			}

			if(!$already_get) { // si clé pas déjà récupérée
				$cle_number++;
			}
		}

		return $cle_number;
	}

	public function getFreeKeyNumber() { // RETOURNE NOMBRE CLES FREE RESTANTES
		return $this->getKeyNumberByType(0);
	}

	public function getSudokuKeyNumber() { // RETOURNE NOMBRE CLES DE SUDOKU RESTANTES
		return $this->getKeyNumberByType(1);
	}




	public function getSudokyPartyLeftNumber() {
		$req = $this->bdd->prepare('SELECT parties_sudoku_left FROM account WHERE email=:email');
		$req->execute(array('email' => $this->getUserSession()['email']));

		while ($donnees = $req->fetch()) {
			return $donnees['parties_sudoku_left'];
		}
	}

	public function addOneSudokuPartyPlay() {
		$req = $this->bdd->prepare('UPDATE account SET parties_sudoku_left=:parties_sudoku_left WHERE email=:email');
		$req->execute(array('parties_sudoku_left' => $this->getSudokyPartyLeftNumber() - 1, 'email' => $this->getUserSession()['email']));
	}

	public function addOneSudokuPartyToPlay() {
		$req = $this->bdd->prepare('UPDATE account SET parties_sudoku_left=:parties_sudoku_left WHERE email=:email');
		$req->execute(array('parties_sudoku_left' => $this->getSudokyPartyLeftNumber() + 1, 'email' => $this->getUserSession()['email']));
	}
	// -------------



	// méthodes gestion compte
	public function connectUser($username, $password) {
		$req = $this->bdd->prepare("SELECT * FROM account WHERE (username = :username OR email = :username) AND password = :password");
		$req->execute(array('username' => $username, 'password' => $password));

		while ($donnees = $req->fetch()) {
			if(intval($donnees['banned'])) return -1;
			
			$this->setUserSession($donnees);
			return 1;
		}

		return 0;
	}




	public function createAccount($username, $email, $password) {
		$req = $this->bdd->prepare("SELECT * FROM account WHERE username=:username OR email=:email");
		$req->execute(array('username' => $username, 'email' => $email));

		while ($donnees = $req->fetch()) {
			return false;
		}

		$req2 = $this->bdd->prepare("INSERT INTO account(username, password, email, inscription_date, admin, parties_free_left, parties_free_timestamp, parties_sudoku_left) VALUES (:username, :password, :email, NOW(), 0, 0, 0, 0)");
		$req2->execute(array('username' => $username, 'password' => $password, 'email' => $email));

		$this->connectUser($username, $password);

		return true;
	}


	public function changePass($last_pass, $new_pass) {
		$isAccount = false;

		$req = $this->bdd->prepare("SELECT * FROM account WHERE password=:password AND email=:email");
		$req->execute(array('password' => $last_pass, 'email' => $this->getUserSession()['email']));

		while ($donnees = $req->fetch()) {
			$isAccount = true;
		}

		if(!$isAccount) { //si n'existe pas de compte avec le mdp $last_pass et l'email de la session actuelle
			return "L'ancien mot de passe n'est pas correct."; //ERREUR
		}


		$req = $this->bdd->prepare("UPDATE account SET password=:password WHERE email=:email"); //update mdp
		$req->execute(array('password' => $new_pass, 'email' => $this->getUserSession()['email']));

		return "Votre mot de passe a bien été modifié.";
	}
	// ---------------
}