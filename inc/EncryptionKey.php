<?php

class EncryptionKey {
	static $instance;

	static function getInstance($config) {
		if(!self::$instance) {
			self::$instance = new EncryptionKey($config);
		}
		return self::$instance;
	}

	
	private $config;

	public function __construct($config) {
		$this->config = $config;
	}



	public function getEncryptionKey() {
		return $this->config['lots_key_encryption'];
	}


	public function cryptPassword($password) {
		return sha1($password);
	}
}