<?php
	class LotsGestion {
		public function getRandomLot($bdd, $session, $key_type) {
			// SELECT CLE RANDOM
			$cle_number = 0;

			$req = $bdd->prepare('SELECT * FROM lots_list WHERE use_left_nb>0 AND key_type=' . $key_type . ' ORDER BY RAND()');
			$req->execute(array());

			while ($donnees = $req->fetch()) {
				$arrayDatas = explode('§§', $donnees['mail_user']);
				$already_get = false;

				for ($i = 0; $i < sizeof($arrayDatas); $i++) {
					if($arrayDatas[$i] == $session->getUserSession()['email']) { //si mail correspond
						$already_get = true;
					}
				}

				if(!$already_get) { //si clé pas déjà récupérée
					$cle_number++;
					$cle_return = $donnees;
				}
			}


			if($cle_number < 0) { //IL N'Y A PLUS DE CLES
				return false;
			}

			date_default_timezone_set('UTC'); //date jour
			$date = date('l jS \of F Y h:i:s A');

			$req2 = $bdd->prepare('UPDATE lots_list SET mail_user=:mail_user, date_reclamation=:date_reclamation, use_left_nb=:use_left_nb WHERE cle=:cle');
			$req2->execute(array('mail_user' => $cle_return['mail_user'] . '§§' . $session->getUserSession()['email'], 'date_reclamation' => $cle_return['date_reclamation'] . '§§' . $date, 'cle' => $cle_return['cle'], 'use_left_nb' => $cle_return['use_left_nb'] - 1));

			return $cle_return;
		}

		public function getRandomFreeLot($bdd, $session) {
			return $this->getRandomLot($bdd, $session, 0);
		}

		public function getRandomSudokuLot($bdd, $session) {
			return $this->getRandomLot($bdd, $session, 1);
		}

		public function getRandomMorpionPayLot($bdd, $session) {
			return $this->getRandomLot($bdd, $session, 2);
		}
	}