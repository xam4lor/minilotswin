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


	public function __construct($bdd, $params) {
		@session_start();

		$this->bdd = $bdd;
		$this->params = $params;
	}


	// parametres de session de l'utilisateur
	public function setUserSession($user) {
		$_SESSION['user'] = $user;
	}

	public function getUserSession() {
		return isset($_SESSION['user']) ? $_SESSION['user'] : null;
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
			if(!intval($donnees['account_validated'])) return -2;
			
			$this->setUserSession($donnees);
			return 1;
		}

		return 0;
	}




	public function createAccount($username, $email, $password, $ip, $account_mode) {
		$req = $this->bdd->prepare("SELECT * FROM account WHERE username=:username OR email=:email OR ip=:ip");
		$req->execute(array('username' => $username, 'email' => $email, 'ip' => $ip));

		while ($donnees = $req->fetch()) {
			return false;
		}




		if($account_mode == 0) {
			$req2 = $this->bdd->prepare("INSERT INTO account(username, password, email, inscription_date, admin, parties_free_left, parties_free_timestamp, parties_sudoku_left, last_partie_date, banned, account_validated, token, ip) VALUES (:username, :password, :email, NOW(), 0, 0, 0, 0, NULL, 0, 1, NULL, :ip)");
			$req2->execute(array('username' => $username, 'password' => $password, 'email' => $email, 'ip' => $ip));
		}

		else if($account_mode == 1 || $account_mode == 2) {
			$req2 = $this->bdd->prepare("INSERT INTO account(username, password, email, inscription_date, admin, parties_free_left, parties_free_timestamp, parties_sudoku_left, last_partie_date, banned, account_validated, token, ip) VALUES (:username, :password, :email, NOW(), 0, 0, 0, 0, NULL, 0, 0, :token, :ip)");
			$req2->execute(array('username' => $username, 'password' => $password, 'email' => $email, 'token' => $this->generateTokenForMail($email, $account_mode), 'ip' => $ip));
		}

		return true;
	}


	public function generateTokenForMail($to, $account_mode) {
		$token = $this->generateRandomString(60);

		$subject = 'Validation de votre compte sur MiniLotsWin';
		$message = '';

		if($account_mode == 1) {
			$message = '
				<html>
					<head>
						<title>Validation de votre compte sur MiniLotsWin</title>
					</head>

					<body>
						<p>Cliquez sur le lien suivant pour valider votre compte MiniLotsWin : <a href="https://minilotswin.000webhostapp.com/account/confirm.php?token=' . $token . '#about">cliquez</a></p>
					</body>
				</html>
			';
		}
		else if($account_mode == 2) {
			$message = '
				<html>
					<head>
						<title>Validation de votre compte sur MiniLotsWin</title>
					</head>

					<body>
						<p>Entrez ce token sur le site pour valider votre compte : "' . $token . '".</p>
					</body>
				</html>
			';
		}

		$headers  = 'MIME-Version: 1.0' . "\r\n";
		$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
		$headers .= 'From: MiniLotsWin <minilotswin@gmail.com>' . "\r\n";
		$headers .= 'Reply-To: MiniLotsWin <minilotswin@gmail.com>' . "\r\n";
		$headers .= 'X-Mailer: PHP/' . phpversion();

		mail($to, $subject, $message, $headers);

		return $token;
	}


	public function confirmAccountFromToken($token) {
		$req = $this->bdd->prepare("SELECT * FROM account WHERE token=:token AND account_validated=0");
		$req->execute(array('token' => $token));
		$found = false;

		while ($donnees = $req->fetch()) {
			$found = true;
			$id = $donnees['id'];
		}

		if($found) {
			$req = $this->bdd->prepare("UPDATE account SET account_validated=1 WHERE id=:id"); // update account_validated
			$req->execute(array('id' => $id));

			return true;
		}

		return false;
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


	public function generateRandomString($length) {
		$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$charactersLength = strlen($characters);
		$randomString = '';

		for ($i = 0; $i < $length; $i++) {
			$randomString .= $characters[rand(0, $charactersLength - 1)];
		}

		return $randomString;
	}
}