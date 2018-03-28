<?php

class ToolsFunction {
	static $instance;

	static function getInstance($bdd) {
		if(!self::$instance) {
			self::$instance = new ToolsFunction($bdd);
		}
		return self::$instance;
	}



	private $bdd;

	public function __construct($bdd) {
		$this->bdd = $bdd;
	}


	public function updateVisiteursConnectes() {
		$req = $this->bdd->prepare('SELECT COUNT(*) AS nbre_entrees FROM connectes WHERE ip=:ip');
		$req->execute(array('ip' => $_SERVER['REMOTE_ADDR']));

		$donnees = $req->fetch();

		//ip non inscrite --> inscription avec timestamp
		if($donnees['nbre_entrees'] == 0) {
			$req2 = $this->bdd->prepare('INSERT INTO connectes(ip, timestamp) VALUES (:ip,:timestamp)');
			$req2->execute(array('ip' => $_SERVER['REMOTE_ADDR'], 'timestamp' => time()));
		}
		else { //ip incrite --> timestamp modifié
			$req2 = $this->bdd->prepare('UPDATE connectes SET timestamp=:timestamp WHERE ip=:ip');
			$req2->execute(array('timestamp' => time(), 'ip' => $_SERVER['REMOTE_ADDR']));
		}

		 // 60 * 5 = nombre de secondes écoulées en 5 minutes
		$timestamp_5min = time() - (5 * 60);
		
		//suppression ip si timestamp > 5 min
		$req3 = $this->bdd->prepare('DELETE FROM connectes WHERE timestamp < :timestamp_5min');
		$req3->execute(array('timestamp_5min' => $timestamp_5min));
	}
}