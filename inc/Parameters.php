<?php

class Parameters {
	private static $instance;

	static function getInstance($config) {
		if(!self::$instance) {
			self::$instance = new Parameters($config);
		}
		return self::$instance;
	}




	private $win_percentage;		// pourcentage de chance de gagner un lot
	private $nb_parties_max;		// nombre maximum de parties par jour
	private $sudoku_gess_nb;		// nombre de cases du Sudoku a trouver par partie
	private $sudoku_ticket_price;	// prix d'un ticket de Sudoku en euros
	private $morpion_ticket_price;	// prix d'un ticket de Morpion payant en euros
	private $paypal_use_sandbox; 	// true : paypal utilise l'API sandbox / false : paypal utilise l'API classique


	public function __construct($config) {
		$this->win_percentage = $config['win_percentage'];
		$this->nb_parties_max = $config['nb_parties_max'];
		$this->sudoku_gess_nb = $config['sudoku_gess_nb'];
		$this->sudoku_ticket_price = $config['sudoku_ticket_price'];
		$this->morpion_ticket_price = $config['morpion_ticket_price'];
		$this->morpion_ticket_price = $config['morpion_ticket_price'];
		$this->paypal_use_sandbox = $config['paypal_use_sandbox']; 	
	}


	public function hasWin() {
		$nb = rand(0, 100);

		if($nb <= $this->win_percentage) {
			return true;
		}

		return false;
	}

	public function getNbPartiesMax() {
		return $this->nb_parties_max;
	}

	public function getWinPercentage() {
		return $this->win_percentage;
	}

	public function getNbCasesVidesSudoku() {
		return $this->sudoku_gess_nb;
	}

	public function getSudokuTicketPrice() {
		return $this->sudoku_ticket_price;
	}

	public function getMorpionTicketPrice() {
		return $this->morpion_ticket_price;
	}

	public function isPaypalUsingSandbox() {
		return $this->paypal_use_sandbox;
	}
}