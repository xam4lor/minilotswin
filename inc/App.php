<?php

class App {
	private static $instance;

	static function getDatabase($config) {
		if(!self::$instance) {
			self::$instance = new App();
		}
		return self::$instance->constructDatabase($config);
	}




	public function constructDatabase($config) {
		$bdd = null;

		try {
			$bdd = new PDO($config['host'], $config['user'], $config['password'], array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
		}
		catch(Exception $e) {
			die('Erreur:' . $e->getMessage());
		}

		return $bdd;
	}
}