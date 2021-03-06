<?php

class AdminAppNotification {
	private static $instance;

	static function getInstance($bdd, $authorization_key) {
		if(!self::$instance) {
			self::$instance = new AdminAppNotification($bdd, $authorization_key);
		}
		return self::$instance;
	}



	private $bdd;
	private $tokens;
	private $authorization_key;


	public function __construct($bdd, $authorization_key) {
		$this->bdd = $bdd;
		$this->authorization_key = $authorization_key;
		$this->tokens = $this->getTokens();
	}


	public function buildAndSendNotification($title, $body, $long_body = null, $is_for_developper = false) {
		$custom_array = array(
			'title' => $title,
			'body' => $body,
			'is_for_developper' => $is_for_developper
		);

		if($long_body === null)
			$custom_array['long_body'] = $body;
		else
			$custom_array['long_body'] = $long_body;

		$fields = array(
			'data' => $custom_array
		);

		return $this->sendNotification($fields);
	}

	public function sendNotification($fields) {
		$fields['registration_ids'] = $this->tokens;
		$this->registerInDataBase($fields);
		return $this->send_notification($fields);
	}

	public function actualisationTokens() {
		return $this->tokens = $this->getTokens();
	}



	private function getTokens() {
		$tokens = array();

		$req = $this->bdd->prepare('SELECT * FROM admin_app_key');
		$req->execute(array());

		while ($donnees = $req->fetch()) {
			array_push($tokens, $donnees['token']);
		}

		return $tokens;
	}

	private function registerInDataBase($fields) {
		$req = $this->bdd->prepare("INSERT INTO notifications(title, body, is_for_developper, date_publish) VALUES (:title, :body, :is_for_developper, NOW())");
		$req->execute(array('title' => $fields['data']['title'], 'body' => $fields['data']['long_body'], 'is_for_developper' => intval($fields['data']['is_for_developper'])));
	}

	private function send_notification($fields) {
		$url = "https://fcm.googleapis.com/fcm/send";

		$headers = array(
			'Authorization:key = ' . $this->authorization_key,
			'Content-Type: application/json'
		);


		$ch = curl_init();

		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));

		$result = curl_exec($ch);

		if($result === FALSE) {
			die('curl failed: '. curl_error($ch));
		}

		curl_close($ch);

		return $result;
	}
}
