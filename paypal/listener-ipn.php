<?php
	include_once '../inc/html_inc/main_php.php';

	header('HTTP/1.1 200 OK');



	$res = 'cmd=_notify-validate';

	foreach ($_POST as $key => $value) {
		$var = urlencode(stripslashes($var));
		$res .= "&$key=$value";
	}



	$httphead = "POST /cgi-bin/webscr HTTP/1.0\r\n";
	$httphead .= "Content-Type: application/x-www-form-urlencoded\r\n";
	$httphead .= "Content-Length: " . strlen($res) . "\r\n\r\n";


	$errno = '';
	$errstr = '';
	 


	$fp = fsockopen('ssl://www.paypal.com', 443, $errno, $errstr, 30);

	if (!$fp) {
		echo 'Erreur de connexion avec paypal.';
	}

	else {
		fputs($fp, $httphead . $res);

		while(!feof($fp)) {
			$readresp = fgets($fp, 1024);

			if(strcmp($readresp, "VERIFIED") == 0) {
				echo 'IPN verifiée.<br />';

				// ------ RECUPERATION DES DONNEES ------
				// --- informations sur le receveur de l'achat ---
				$receveur_infos = 								
					strip_tags($_POST['receiver_email']) 				// email
					. "§§" . strip_tags($_POST['receiver_id'])			// id
					. "§§" . strip_tags($_POST['residence_country']);	// pays de résidence


				// --- informations sur la transaction ---
				$txn_id = strip_tags($_POST['txn_id']);					// id
				$txn_type = strip_tags($_POST['txn_type']);				// type


				// --- informations sur l'acheteur ---
				$payer_email = strip_tags($_POST['payer_email']);		// email
				$payer_id = strip_tags($_POST['payer_id']);				// id

				$payer_names = 
					strip_tags($_POST['first_name'])					// prénom
					. "§§" . strip_tags($_POST['last_name']);			// nom de famille


				// --- informations sur le paiement ---
				$paiement_currency = strip_tags($_POST['mc_currency']); // type de monnaie utilisée
				$paiement_fee = strip_tags($_POST['mc_fee']);			// montant de la taxe
				$paiement_gross = strip_tags($_POST['mc_gross']);		// montant brut du paiement (sans frais)
				$paiement_quantity = strip_tags($_POST['quantity']);	// quantité
				$paiement_date = strip_tags($_POST['payment_date']);	// date du paiement
				$payment_status = strip_tags($_POST['payment_status']); // status du paiement -> Completed si complété

				// variable custom
				$custom_var_raw = strip_tags($_POST['custom']);
				$custom_var = explode("§§", $custom_var_raw);



				if($payment_status != "Completed") {
					echo 'Status non valide.';
				}

				else {
					$payement_id = explode("=", $custom_var[0]);
					$user_id = explode("=", $custom_var[1]);

					if(
						strcmp($payement_id[0], "payement_id") === 0
						&& strcmp($payement_id[1], "1") === 0
						&& strcmp($user_id[0], "user_id") === 0
						&& floatval($paiement_currency) === $params->getSudokuTicketPrice()
					)
					{ // ticket de sudoku
						$req = $bdd->prepare("SELECT parties_sudoku_left FROM account WHERE id=:id");
						$req->execute(array('id' => $user_id[1]));
						$parties_sudoku_left = 0;

						while ($donnees = $req->fetch()) {
							$parties_sudoku_left = intval($donnees['parties_sudoku_left']);
						}

						$req2 = $bdd->prepare("UPDATE account SET parties_sudoku_left=:s_left WHERE id=:id");
						$req2->execute(array('s_left' => ($parties_sudoku_left + 1), 'id' =>$user_id[1]));
					}

					$req3 = $bdd->prepare("INSERT INTO payments(receveur_infos, txn_id, txn_type, paiement_gross, paiement_currency, paiement_fee, paiement_quantity, paiement_date, payer_id, payer_email, payer_names, custom, lot_added) VALUES (:receveur_infos, :txn_id, :txn_type, :paiement_gross, :paiement_currency, :paiement_fee, :paiement_quantity, :paiement_date, :payer_id, :payer_email, :payer_names, :custom, 0)");
					$req3->execute(array('receveur_infos' => $receveur_infos, 'txn_id' => $txn_id, 'txn_type' => $txn_type, 'paiement_gross' => $paiement_gross, 'paiement_currency' => $paiement_currency, 'paiement_fee' => $paiement_fee, 'paiement_quantity' => $paiement_quantity, 'paiement_date' => $paiement_date, 'payer_id' => $payer_id, 'payer_email' => $payer_email, 'payer_names' => $payer_names, 'custom' => $custom_var_raw));

					


					echo 'Le status est valide et les données ont bien insérées dans la base de donnée.';
				}
			}

			else if(strcmp($readresp, "INVALID") == 0) {
				echo 'IPN invalide.<br />';
			}
		}

		fclose($fp);
	}