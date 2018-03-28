<?php

class EncryptionKey {
	static $instance;

	static function getInstance() {
		if(!self::$instance) {
			self::$instance = new EncryptionKey();
		}
		return self::$instance;
	}

	

	public function getEncryptionKey() {
		$key = "uc-482$rj+yG-pYid~@]xji8z";

		return $key;
	}


	public function cryptPassword($password) {
		return sha1($password);
	}
}