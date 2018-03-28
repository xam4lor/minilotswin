<?php
	class Morpion {
		private $gameDatas;

		private $userPion;
		private $botPion;

		private $winner;

		private $error;



		public  function __construct() {
			$this->gameDatas = array("V", "V", "V", "V", "V", "V", "V", "V", "V"); // V -> vide /// X -> joueur /// O -> robot
			
			$this->userPion = "X";
			$this->botPion = "O";

			$this->winner = "V";
		}




		public function firstLaunch() {
			$returnHTML = $this->getScript() . $this->getGameDatasDisplay();

			return $returnHTML;
		}

		public function newRound($session, $params, $encryption_key, $datas) {
			$win_status = false;
			$returnHTML = "";

			if($datas != "recupere_data") {
				$hasChoosen = $this->setChoice($datas, $this->userPion);
				$win_status = $this->checkWin();
				

				if($hasChoosen) {
					if(!$win_status) {
						$this->setChoice($this->getBotChoice(), $this->botPion);
						$win_status = $this->checkWin();
					}
				}
				else if(!$hasChoosen && isset($this->error) && $this->error != "") {
					$returnHTML .= $this->error;
					$this->error = "";
				}
			}



			if(!$win_status) {
				$_SESSION['game_save'] = $this;
				$returnHTML .= $this->getScript() . $this->getGameDatasDisplay();
			}
			else { //fin de partie
				$session->addOneFreePartyPlay();

				unset($_SESSION['game_save']);

				if($this->winner == $this->botPion) {
					$returnHTML .= $this->getBotWinScript();
				}
				else if($this->winner == "V") {
					$returnHTML .= $this->getNullWinScript();
				}
				else {
					if($params->hasWin()) {
						$returnHTML .= '
						<div class="game-script">
							<p id="end-script">Vous avez gagné cette partie et avez gagné un lot ! Si vous n\'êtes pas redirigé dans quelques secondes, veuillez contacter un administrateur puis cliquer sur le bouton suivant :<button class="bords-ronds w3-button w3-black w3-right w3-section" onclick="document.location.href=\'/win_page#lots\'"><i class="fa fa-paper-plane"></i> Cliquez ici</button>
							<p id="game-javascript">
								console.log("DEBUG : Player won / Lot gagné. Vous avez gagné cette partie et avez gagné un lot ! Si vous n\'êtes pas redirigé dans quelques secondes, veuillez contacter un administrateur puis redirigez vous manuellement vers \'http://www.minilotswin.890m.com/win_page/\'.");
								window.location.replace("/win_page#lots");
							</p>
						</div>';

						$_SESSION['has_win'] = $_SERVER['REMOTE_ADDR'] . $encryption_key->getEncryptionKey() . '&lot_key_type=0';
					}
					else {
						$returnHTML .= '
						<div class="game-script">
							<p id="end-script">Vous avez gagné cette partie mais vous n\'avez gagné aucun lot !</p><button class="bords-ronds w3-button w3-black w3-right w3-section" onclick="document.location.href=\'http://rapidtory.com/BnTZ\'"><i class="fa fa-paper-plane"></i> Re-tenter sa chance</button>
							<p id="game-javascript">
								console.log("DEBUG : Player won / Pas lot. Vous avez gagné cette partie mais vous n\'avez gagné aucun lot ! Raffraichissez la page pour re-tenter votre chance.");
							</p>
						</div>';
					}
				}
			}

			return $returnHTML;
		}





		private function checkWin() {
			$win = false;


			if($this->hasUserWin()) {
				$win = true;
				$this->winner = $this->userPion;
			}
			else if($this->hasBotWin()) {
				$win = true;
				$this->winner = $this->botPion;
			}
			else if($this->matchNul()) {
				$win = true;
			}


			return $win;
		}





		private function setChoice($datas, $pion) {
			$datas = intval($datas);

			if($datas >= 0 && $datas <= 8) {
				if($this->gameDatas[$datas] == "V") {
					$this->gameDatas[$datas] = $pion;
					return true;
				}
				else {
					//Error log -> case déjà cochée
					$this->error = "La case a déjà été cochée.";
					return false;
				}
			}
			else {
				//Error log -> réponse non comprise entre 0 et 8 inclus
				$this->error = "La réponse doit être comprise entre 0 et 8.";
				return false;
			}
		}


		private function getBotChoice() {
			while(true) {
				$choice = rand(0, 8);

				if($this->gameDatas[$choice] == "V") {
					break;
				}
			}

			return $choice;
		}






		private function getBotWinScript() {
			$script = '
				<div class="end-game-div">
					<p id="end-game">Le robot a gagné !</p><button class="bords-ronds w3-button w3-black w3-right w3-section" onclick="document.location.reload(false)"><i class="fa fa-paper-plane"></i> Re-tenter sa chance</button>
				</div>

				<div class="game-script">
					<p id="game-javascript">
						console.log("DEBUG : Bot won");
					</p>
				</div>
			';

			return $script;
		}


		private function getNullWinScript() {
			$script = '
				<div class="end-game-div">
					<p id="end-game">Match nul !</p>
					<button class="bords-ronds w3-button w3-black w3-right w3-section" onclick="document.location.reload(false)"><i class="fa fa-paper-plane"></i> Re-tenter sa chance</button>
				</div>
				<div class="game-script">
					<p id="game-javascript">
						console.log("DEBUG : Match nul");
					</p>
				</div>
			';

			return $script;
		}






		private function getScript() {
			$script = '
				<div class="game-script">
					<p id="game-javascript" style="display: none;">
						document.getElementById("game-title").innerHTML = "VOUS JOUEZ AU MORPION";
					</p>
				</div>
			';

			return $script;
		}


		private function getGameDatasDisplay() {
			$datasTbl = $this->clearPion($this->gameDatas, "V", "");

			$datas = '<div class="morpion-center">'
						. '<div class = "gameGrid">'
							. '<div class="grid0 grid" onclick="datas=0; process();">' . $datasTbl[0] . '</div>'
							. '<div class="grid1 grid" onclick="datas=1; process();">' . $datasTbl[1] . '</div>'
							. '<div class="grid2 grid" onclick="datas=2; process();">' . $datasTbl[2] . '</div>'
						. '</div>'
						. '<div class = "gameGrid">'
							. '<div class="grid3 grid" onclick="datas=3; process();">' . $datasTbl[3] . '</div>'
							. '<div class="grid4 grid" onclick="datas=4; process();">' . $datasTbl[4] . '</div>'
							. '<div class="grid5 grid" onclick="datas=5; process();">' . $datasTbl[5] . '</div>'
						. '</div>'
						. '<div class = "gameGrid">'
							. '<div class="grid6 grid" onclick="datas=6; process();">' . $datasTbl[6] . '</div>'
							. '<div class="grid7 grid" onclick="datas=7; process();">' . $datasTbl[7] . '</div>'
							. '<div class="grid8 grid" onclick="datas=8; process();">' . $datasTbl[8] . '</div>'
						. '</div>'
					. '</div>';

			return $datas;
		}




		private function clearPion($tbl, $pionToClear, $pionToReplace) {
			$tblProv = $tbl;

			for ($i = 0; $i < sizeof($tblProv); $i++) { 
				if($tblProv[$i] == $pionToClear) {
					$tblProv[$i] = $pionToReplace;
				}
			}

			return $tblProv;
		}





		private function matchNul() {
			$matchNul = true;

			for ($i = 0; $i < sizeof($this->gameDatas); $i++) { 
				if($this->gameDatas[$i] == "V") {
					$matchNul = false;
				}
			}

			return $matchNul;
		}


		private function hasUserWin() {
			$tempGameDatas = $this->clearPion($this->gameDatas, "O", "V");

			if (
				$tempGameDatas == array("X", "X", "X", "V", "V", "V", "V", "V", "V")
				|| $tempGameDatas == array("X", "X", "X", "X", "V", "V", "V", "V", "V")
				|| $tempGameDatas == array("X", "X", "X", "V", "X", "V", "V", "V", "V")
				|| $tempGameDatas == array("X", "X", "X", "V", "V", "X", "V", "V", "V")
				|| $tempGameDatas == array("X", "X", "X", "V", "V", "V", "X", "V", "V")
				|| $tempGameDatas == array("X", "X", "X", "V", "V", "V", "V", "X", "V")
				|| $tempGameDatas == array("X", "X", "X", "V", "V", "V", "V", "V", "X")

				|| $tempGameDatas == array("V", "V", "V", "X", "X", "X", "V", "V", "V")
				|| $tempGameDatas == array("X", "V", "V", "X", "X", "X", "V", "V", "V")
				|| $tempGameDatas == array("V", "X", "V", "X", "X", "X", "V", "V", "V")
				|| $tempGameDatas == array("V", "V", "X", "X", "X", "X", "V", "V", "V")
				|| $tempGameDatas == array("V", "V", "V", "X", "X", "X", "X", "V", "V")
				|| $tempGameDatas == array("V", "V", "V", "X", "X", "X", "V", "X", "V")
				|| $tempGameDatas == array("V", "V", "V", "X", "X", "X", "V", "V", "X")

				|| $tempGameDatas == array("V", "V", "V", "V", "V", "V", "X", "X", "X")
				|| $tempGameDatas == array("X", "V", "V", "V", "V", "V", "X", "X", "X")
				|| $tempGameDatas == array("V", "X", "V", "V", "V", "V", "X", "X", "X")
				|| $tempGameDatas == array("V", "V", "X", "V", "V", "V", "X", "X", "X")
				|| $tempGameDatas == array("V", "V", "V", "X", "V", "V", "X", "X", "X")
				|| $tempGameDatas == array("V", "V", "V", "V", "X", "V", "X", "X", "X")
				|| $tempGameDatas == array("V", "V", "V", "V", "V", "X", "X", "X", "X")

				|| $tempGameDatas == array("X", "V", "V", "X", "V", "V", "X", "V", "V")
				|| $tempGameDatas == array("X", "X", "V", "X", "V", "V", "X", "V", "V")
				|| $tempGameDatas == array("X", "V", "X", "X", "V", "V", "X", "V", "V")
				|| $tempGameDatas == array("X", "V", "V", "X", "X", "V", "X", "V", "V")
				|| $tempGameDatas == array("X", "V", "V", "X", "V", "X", "X", "V", "V")
				|| $tempGameDatas == array("X", "V", "V", "X", "V", "V", "X", "X", "V")
				|| $tempGameDatas == array("X", "V", "V", "X", "V", "V", "X", "V", "X")

				|| $tempGameDatas == array("V", "X", "V", "V", "X", "V", "V", "X", "V")
				|| $tempGameDatas == array("X", "X", "V", "V", "X", "V", "V", "X", "V")
				|| $tempGameDatas == array("V", "X", "X", "V", "X", "V", "V", "X", "V")
				|| $tempGameDatas == array("V", "X", "V", "X", "X", "V", "V", "X", "V")
				|| $tempGameDatas == array("V", "X", "V", "V", "X", "X", "V", "X", "V")
				|| $tempGameDatas == array("V", "X", "V", "V", "X", "V", "X", "X", "V")
				|| $tempGameDatas == array("V", "X", "V", "V", "X", "V", "V", "X", "X")

				|| $tempGameDatas == array("V", "V", "X", "V", "V", "X", "V", "V", "X")
				|| $tempGameDatas == array("X", "V", "X", "V", "V", "X", "V", "V", "X")
				|| $tempGameDatas == array("V", "X", "X", "V", "V", "X", "V", "V", "X")
				|| $tempGameDatas == array("V", "V", "X", "X", "V", "X", "V", "V", "X")
				|| $tempGameDatas == array("V", "V", "X", "V", "X", "X", "V", "V", "X")
				|| $tempGameDatas == array("V", "V", "X", "V", "V", "X", "X", "V", "X")
				|| $tempGameDatas == array("V", "V", "X", "V", "V", "X", "V", "X", "X")

				|| $tempGameDatas == array("X", "V", "V", "V", "X", "V", "V", "V", "X")
				|| $tempGameDatas == array("X", "X", "V", "V", "X", "V", "V", "V", "X")
				|| $tempGameDatas == array("X", "V", "X", "V", "X", "V", "V", "V", "X")
				|| $tempGameDatas == array("X", "V", "V", "X", "X", "V", "V", "V", "X")
				|| $tempGameDatas == array("X", "V", "V", "V", "X", "X", "V", "V", "X")
				|| $tempGameDatas == array("X", "V", "V", "V", "X", "V", "X", "V", "X")
				|| $tempGameDatas == array("X", "V", "V", "V", "X", "V", "V", "X", "X")

				|| $tempGameDatas == array("V", "V", "X", "V", "X", "V", "X", "V", "V")
				|| $tempGameDatas == array("X", "V", "X", "V", "X", "V", "X", "V", "V")
				|| $tempGameDatas == array("V", "X", "X", "V", "X", "V", "X", "V", "V")
				|| $tempGameDatas == array("V", "V", "X", "X", "X", "V", "X", "V", "V")
				|| $tempGameDatas == array("V", "V", "X", "V", "X", "X", "X", "V", "V")
				|| $tempGameDatas == array("V", "V", "X", "V", "X", "V", "X", "X", "V")
				|| $tempGameDatas == array("V", "V", "X", "V", "X", "V", "X", "V", "X")
			) {
				return true;
			}

			return false;
		}


		private function hasBotWin() {
			$tempGameDatas = $this->clearPion($this->gameDatas, "X", "V");

			if (
				$tempGameDatas == array("O", "O", "O", "V", "V", "V", "V", "V", "V")
				|| $tempGameDatas == array("O", "O", "O", "O", "V", "V", "V", "V", "V")
				|| $tempGameDatas == array("O", "O", "O", "V", "O", "V", "V", "V", "V")
				|| $tempGameDatas == array("O", "O", "O", "V", "V", "O", "V", "V", "V")
				|| $tempGameDatas == array("O", "O", "O", "V", "V", "V", "O", "V", "V")
				|| $tempGameDatas == array("O", "O", "O", "V", "V", "V", "V", "O", "V")
				|| $tempGameDatas == array("O", "O", "O", "V", "V", "V", "V", "V", "O")

				|| $tempGameDatas == array("V", "V", "V", "O", "O", "O", "V", "V", "V")
				|| $tempGameDatas == array("O", "V", "V", "O", "O", "O", "V", "V", "V")
				|| $tempGameDatas == array("V", "O", "V", "O", "O", "O", "V", "V", "V")
				|| $tempGameDatas == array("V", "V", "O", "O", "O", "O", "V", "V", "V")
				|| $tempGameDatas == array("V", "V", "V", "O", "O", "O", "O", "V", "V")
				|| $tempGameDatas == array("V", "V", "V", "O", "O", "O", "V", "O", "V")
				|| $tempGameDatas == array("V", "V", "V", "O", "O", "O", "V", "V", "O")

				|| $tempGameDatas == array("V", "V", "V", "V", "V", "V", "O", "O", "O")
				|| $tempGameDatas == array("O", "V", "V", "V", "V", "V", "O", "O", "O")
				|| $tempGameDatas == array("V", "O", "V", "V", "V", "V", "O", "O", "O")
				|| $tempGameDatas == array("V", "V", "O", "V", "V", "V", "O", "O", "O")
				|| $tempGameDatas == array("V", "V", "V", "O", "V", "V", "O", "O", "O")
				|| $tempGameDatas == array("V", "V", "V", "V", "O", "V", "O", "O", "O")
				|| $tempGameDatas == array("V", "V", "V", "V", "V", "O", "O", "O", "O")

				|| $tempGameDatas == array("O", "V", "V", "O", "V", "V", "O", "V", "V")
				|| $tempGameDatas == array("O", "O", "V", "O", "V", "V", "O", "V", "V")
				|| $tempGameDatas == array("O", "V", "O", "O", "V", "V", "O", "V", "V")
				|| $tempGameDatas == array("O", "V", "V", "O", "O", "V", "O", "V", "V")
				|| $tempGameDatas == array("O", "V", "V", "O", "V", "O", "O", "V", "V")
				|| $tempGameDatas == array("O", "V", "V", "O", "V", "V", "O", "O", "V")
				|| $tempGameDatas == array("O", "V", "V", "O", "V", "V", "O", "V", "O")

				|| $tempGameDatas == array("V", "O", "V", "V", "O", "V", "V", "O", "V")
				|| $tempGameDatas == array("O", "O", "V", "V", "O", "V", "V", "O", "V")
				|| $tempGameDatas == array("V", "O", "O", "V", "O", "V", "V", "O", "V")
				|| $tempGameDatas == array("V", "O", "V", "O", "O", "V", "V", "O", "V")
				|| $tempGameDatas == array("V", "O", "V", "V", "O", "O", "V", "O", "V")
				|| $tempGameDatas == array("V", "O", "V", "V", "O", "V", "O", "O", "V")
				|| $tempGameDatas == array("V", "O", "V", "V", "O", "V", "V", "O", "O")

				|| $tempGameDatas == array("V", "V", "O", "V", "V", "O", "V", "V", "O")
				|| $tempGameDatas == array("O", "V", "O", "V", "V", "O", "V", "V", "O")
				|| $tempGameDatas == array("V", "O", "O", "V", "V", "O", "V", "V", "O")
				|| $tempGameDatas == array("V", "V", "O", "O", "V", "O", "V", "V", "O")
				|| $tempGameDatas == array("V", "V", "O", "V", "O", "O", "V", "V", "O")
				|| $tempGameDatas == array("V", "V", "O", "V", "V", "O", "O", "V", "O")
				|| $tempGameDatas == array("V", "V", "O", "V", "V", "O", "V", "O", "O")

				|| $tempGameDatas == array("O", "V", "V", "V", "O", "V", "V", "V", "O")
				|| $tempGameDatas == array("O", "O", "V", "V", "O", "V", "V", "V", "O")
				|| $tempGameDatas == array("O", "V", "O", "V", "O", "V", "V", "V", "O")
				|| $tempGameDatas == array("O", "V", "V", "O", "O", "V", "V", "V", "O")
				|| $tempGameDatas == array("O", "V", "V", "V", "O", "O", "V", "V", "O")
				|| $tempGameDatas == array("O", "V", "V", "V", "O", "V", "O", "V", "O")
				|| $tempGameDatas == array("O", "V", "V", "V", "O", "V", "V", "O", "O")

				|| $tempGameDatas == array("V", "V", "O", "V", "O", "V", "O", "V", "V")
				|| $tempGameDatas == array("O", "V", "O", "V", "O", "V", "O", "V", "V")
				|| $tempGameDatas == array("V", "O", "O", "V", "O", "V", "O", "V", "V")
				|| $tempGameDatas == array("V", "V", "O", "O", "O", "V", "O", "V", "V")
				|| $tempGameDatas == array("V", "V", "O", "V", "O", "O", "O", "V", "V")
				|| $tempGameDatas == array("V", "V", "O", "V", "O", "V", "O", "O", "V")
				|| $tempGameDatas == array("V", "V", "O", "V", "O", "V", "O", "V", "O")
			) {
				return true;
			}

			return false;
		}
	}
