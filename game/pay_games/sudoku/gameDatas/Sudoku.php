<?php
	class Sudoku {
		private $valuesNbToSub; //nombre de valeurs à retirer de la grille

		private $neutralVal;
		private $sudoku_answer;
		private $gameText;
		private $params;

		public function __construct($params) {
			$this->sudoku_answer = array(0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0);
			$this->sudoku_user = array(0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0);
			$this->neutralVal = 'V';
			$this->gameText = "Remplissez les cases correctement puis appuyez sur le bouton de confirmation pour valider votre choix. Si vous devez recharger la page, veuillez noter vos dernières réponses car ces dernières seront supprimées.";
			$this->params = $params;

			$this->valuesNbToSub = $this->params->getNbCasesVidesSudoku();
		}

		public function firstLaunch() {
			$this->sudoku_answer = $this->reOrder($this->solve($this->sudoku_answer));
			$this->sudoku_user = $this->sudoku_answer;

			$this->sudoku_user = $this->subValues($this->sudoku_user, $this->valuesNbToSub);

			return $this->print_sudoku($this->sudoku_user);
		}


		public function newRound($session, $params, $encryption_key, $datas) {
			if($datas == "recupere_data") {
				return $this->print_sudoku($this->sudoku_user, @$provArray);
			}

			$provArray = array();

			foreach (explode("§§", $datas) as $key => $value) {
				if(intval($value) != 0) {
					array_push($provArray, intval($value));
				}
				else {
					array_push($provArray, $this->neutralVal);
				}
			}

			if($this->sudoku_answer == $provArray) { // Fin de partie
				unset($_SESSION['game_save_pay']);

				$_SESSION['has_win'] = $_SERVER['REMOTE_ADDR'] . $encryption_key->getEncryptionKey() . '&lot_key_type=1';

				return '
					<h3 class=\'w3-center\' id=\'game-title\'>VOUS JOUEZ AU SUDOKU</h3>
					<div class="game-script">
						<p id="end-script">Vous avez gagné cette partie et avez gagné un lot ! Si vous n\'êtes pas redirigé dans quelques secondes, veuillez contacter un administrateur puis cliquer sur le bouton suivant :<button class="bords-ronds w3-button w3-black w3-right w3-section" onclick="document.location.href=\'/win_page#lots\'"><i class="fa fa-paper-plane"></i> Cliquez ici</button>
						<p id="game-javascript">
								console.log("DEBUG : Player won / Lot gagné. Vous avez gagné cette partie et avez gagné un lot ! Si vous n\'êtes pas redirigé dans quelques secondes, veuillez contacter un administrateur puis redirigez vous manuellement vers \'http://www.minilotswin.890m.com/win_page/\'.");
								window.location.replace("/win_page#lots");
						</p>
					</div>';
			}

			$_SESSION['game_save_pay'] = $this;
			$this->gameText = "Vos choix sont incorrects, veuillez essayer une autre combinaison. Si vous devez recharger la page, veuillez noter vos dernières réponses car ces dernières seront supprimées.";

			return $this->print_sudoku($this->sudoku_user, $provArray);
		}





		private function subValues($sudoku, $valNb) {
			$idToSub = array();

			while (sizeof($idToSub) < $valNb) {
				$randomId = random_int(0, sizeof($sudoku));

				if(!in_array($randomId, $idToSub)) {
					array_push($idToSub, $randomId);
				}
			}

			foreach ($idToSub as $key => $value) {
				$sudoku[$value] = $this->neutralVal;
			}

			return $sudoku;
		}

		private function reOrder($array) {
			$arrayLength = sizeof($array);
			$currentId = 0;
			$arrayToReturn = array();

			while($currentId < $arrayLength) {
				array_push($arrayToReturn, $array[$currentId]);
				$currentId++;
			}

			array_push($arrayToReturn, $this->neutralVal);

			return $arrayToReturn;
		}




		function return_row($cell){
			return floor($cell/9);
		}

		function return_col($cell){
			return $cell % 9;
		}

		function return_block($cell){
			return floor($this->return_row($cell)/3)*3+floor($this->return_col($cell)/3);
		}

		function is_possible_row($number, $row, $sudoku){
			$possible = true;
			for($x=0;$x<=8;$x++){		
				if($sudoku[$row*9+$x] == $number){
					$possible = false;
				}		
			}
			return $possible;
		}

		function is_possible_col($number, $col, $sudoku){
			$possible = true;
			for($x=0;$x<=8;$x++){
				if($sudoku[$col+9*$x] == $number){
					$possible = false;
				}
			}
			return $possible;
		}

		function is_possible_block($number, $block, $sudoku){
			$possible = true;
			for($x=0;$x<=8;$x++){
				if($sudoku[floor($block/3)*27+$x%3+9*floor($x/3)+3*($block%3)] == $number){
					$possible = false;
				}
			}
			return $possible;
		}

		function is_possible_number($cell, $number, $sudoku){
			$row = $this->return_row($cell);
			$col = $this->return_col($cell);
			$block = $this->return_block($cell);
			return $this->is_possible_row($number,$row,$sudoku) and $this->is_possible_col($number,$col,$sudoku) and $this->is_possible_block($number,$block,$sudoku);
		}


		function print_sudoku($sudoku, $values = null) {
			$html = "
					<h3 class='w3-center' id='game-title'>VOUS JOUEZ AU SUDOKU</h3>
					<p id='game-text w3-center'>" . $this->gameText . "</p>
					<table class='sudoku-table w3-center' bgcolor = \"#000000\" cellspacing = \"1\" cellpadding = \"2\">";

			if(is_null($values)) {
				$values = $sudoku;
			}

			for($x=0;$x<=8;$x++){
				$html .= "<tr bgcolor = \"white\" align = \"center\">";
				
				for($y=0;$y<=8;$y++){
					if(@$sudoku[$x * 9 + $y] != $this->neutralVal) {
						$html .= "<td class='sudoku-td' id='element-" . (string) ($x *9+$y) . "'>" . @$values[$x *9+$y] . "</td>";
					}
					else {
						if($values[$x * 9 + $y] == $this->neutralVal) {
							$values[$x * 9 + $y] = "";
						}
						$html .=
							"<td class='sudoku-td'>
								<textarea class='sudoku-textarea' id='element-" . (string) ($x *9+$y) . "'>"
									. $values[$x * 9 + $y]
								. "</textarea>
							</td>";
					}
				}

				$html .= "</tr>";
			}

			$html .= "</table>

					<button class=\"bords-ronds w3-button w3-black w3-right w3-section\"
					onclick=\"
						var tblProv = '';

						for (var i = 0; i < 81; i++) {
							if(document.getElementById('element-' + i).value == undefined) {
								tblProv += '' + document.getElementById('element-' + i).innerHTML + '§§';
							}
							else {
								tblProv += '' + document.getElementById('element-' + i).value + '§§';
							}
						}

						datas = tblProv;

						process();
					\"> Confirmer votre réponse</button>";
			
			return $html;
		}

		function is_correct_row($row,$sudoku){
			for($x=0;$x<=8;$x++){
				$row_temp[$x] = $sudoku[$row*9+$x];
			}
			return count(array_diff(array(1,2,3,4,5,6,7,8,9),$row_temp)) == 0;
		}

			

		function is_correct_col($col,$sudoku){
			for($x=0;$x<=8;$x++){
				$col_temp[$x] = $sudoku[$col+$x*9];
			}
			return count(array_diff(array(1,2,3,4,5,6,7,8,9),$col_temp)) == 0;
		}

		function is_correct_block($block,$sudoku){
			for($x=0;$x<=8;$x++){
				$block_temp[$x] = $sudoku[floor($block/3)*27+$x%3+9*floor($x/3)+3*($block%3)];
			}
			return count(array_diff(array(1,2,3,4,5,6,7,8,9),$block_temp)) == 0;
		}

		function is_solved_sudoku($sudoku){
			for($x=0;$x<=8;$x++){
				if(!$this->is_correct_block($x,$sudoku) or !$this->is_correct_row($x,$sudoku) or !$this->is_correct_col($x,$sudoku)){
					return false;
					break;
				}
			}
			return true;
		}

		function determine_possible_values($cell,$sudoku){
			$possible = array();
			for($x=1;$x<=9;$x++){
				if($this->is_possible_number($cell,$x,$sudoku)){
					array_unshift($possible,$x);
				} 
			}
			return $possible;
		}

		function determine_random_possible_value($possible,$cell){
			return $possible[$cell][rand(0,count($possible[$cell])-1)];
		}

		function scan_sudoku_for_unique($sudoku){
			for($x=0;$x<=80;$x++){
				if($sudoku[$x] == 0){
					$possible[$x] = $this->determine_possible_values($x,$sudoku);
					if(count($possible[$x])==0){
						return(false);
						break;
					}
				}
			}
			return($possible);
		}

		function remove_attempt($attempt_array,$number){
			$new_array = array();
			for($x=0;$x<count($attempt_array);$x++){
				if($attempt_array[$x] != $number){
					array_unshift($new_array,$attempt_array[$x]);
				}
			}
			return $new_array;
		}
				

		function print_possible($possible){
			$html = "<table bgcolor = \"#ff0000\" cellspacing = \"1\" cellpadding = \"2\">";
			for($x=0;$x<=8;$x++){
				$html .= "<tr bgcolor = \"yellow\" align = \"center\">";
				for($y=0;$y<=8;$y++){
					$values = "";
					for($z=0;$z<=count($possible[$x*9+$y]);$z++){
						$values .= $possible[$x*9+$y][$z];
					}
					$html.= "<td width = \"20\" height = \"20\">$values</td>";
				}
				$html .= "</tr>";
			}
			$html .= "</table>";
			return $html;
		}	

		function next_random($possible){
			$max = 9;
			for($x=0;$x<=80;$x++){
				if ((count($possible[$x])<=$max) and (count($possible[$x])>0)){
					$max = count($possible[$x]);
					$min_choices = $x;
				}
			}
			return $min_choices;
		}
			
		 
		function solve($sudoku){
			$start = microtime();
			$saved = array();	
			$saved_sud = array();
			while(!$this->is_solved_sudoku($sudoku)){
				$x+=1;
				$next_move = $this->scan_sudoku_for_unique($sudoku);
				if($next_move == false){
					$next_move = array_pop($saved);
					$sudoku = array_pop($saved_sud);
				}
				$what_to_try = $this->next_random($next_move);	
				$attempt = $this->determine_random_possible_value($next_move,$what_to_try);
				if(count($next_move[$what_to_try])>1){					
					$next_move[$what_to_try] = $this->remove_attempt($next_move[$what_to_try],$attempt);
					array_push($saved,$next_move);
					array_push($saved_sud,$sudoku);
				}
				$sudoku[$what_to_try] = $attempt;
			}
			$end = microtime();
			$ms_start = explode(" ",$start);
			$ms_end = explode(" ",$end);
			$total_time = round(($ms_end[1] - $ms_start[1] + $ms_end[0] - $ms_start[0]),2);
			return $sudoku;
		}
	}

